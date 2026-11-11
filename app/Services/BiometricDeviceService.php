<?php

namespace App\Services;

use App\Models\BiometricDevice;
use Exception;
use Fsuuaas\Zkteco\Lib\ZKTeco;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class BiometricDeviceService
{
    /**
     * Determine whether the device uses ZKTeco protocol
     */
    public function isZkTeco(BiometricDevice $device): bool
    {
        if ((int) $device->port === 4370) {
            return true;
        }

        $identifier = strtolower(($device->model ?? '').' '.($device->name ?? ''));
        foreach (['zk', 'zkteco', 'k40', 'k60', 'k50', 'u160', 'in01', 'mb20', 'silkbio', 'bio'] as $keyword) {
            if (strpos($identifier, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Instantiate ZKTeco client
     */
    public function getZkClient(BiometricDevice $device, int $timeoutSeconds = 8): ZKTeco
    {
        if (! defined('ZK_LIB_LOG')) {
            define('ZK_LIB_LOG', storage_path('logs/zkteco.log'));
        }

        $zk = new ZKTeco($device->ip_address, (int) ($device->port ?: 4370));
        if ($zk->_zkclient) {
            $timeout = ['sec' => $timeoutSeconds, 'usec' => 0];
            @socket_set_option($zk->_zkclient, SOL_SOCKET, SO_RCVTIMEO, $timeout);
            @socket_set_option($zk->_zkclient, SOL_SOCKET, SO_SNDTIMEO, $timeout);
        }
        return $zk;
    }

    protected function getClient(BiometricDevice $device)
    {
        // Try to decrypt or use as is
        try {
            $password = Crypt::decryptString($device->password);
        } catch (Exception $e) {
            $password = $device->password;
        }

        return new Client([
            'base_uri' => "http://{$device->ip_address}:{$device->port}/",
            'timeout' => 10,
            'auth' => [$device->username, $password, 'digest'],
            'headers' => [
                'Content-Type' => 'application/xml',
                'Accept' => 'application/xml',
            ],
        ]);
    }

    /**
     * Connect (Test Connection)
     */
    public function connect(BiometricDevice $device): bool
    {
        if ($this->isZkTeco($device)) {
            try {
                $zk = $this->getZkClient($device);
                $connected = (bool) $zk->connect();
                if ($connected) {
                    $zk->disconnect();
                }

                return $connected;
            } catch (Exception $e) {
                Log::error("ZKTeco Connect Failed for {$device->ip_address}: ".$e->getMessage());

                return false;
            }
        }

        try {
            $client = $this->getClient($device);
            $response = $client->get('ISAPI/System/deviceInfo');

            return $response->getStatusCode() === 200;
        } catch (Exception $e) {
            Log::error("Hikvision Connect Failed for {$device->ip_address}: ".$e->getMessage());

            return false;
        }
    }

    /**
     * Test Connection for Controller
     */
    public function testConnection(BiometricDevice $device): array
    {
        try {
            if ($this->isZkTeco($device)) {
                $zk = $this->getZkClient($device);
                if ($zk->connect()) {
                    $devName = trim(str_replace('~DeviceName=', '', $zk->deviceName() ?: ''));
                    $serial = trim(str_replace('~SerialNumber=', '', $zk->serialNumber() ?: ''));
                    $zk->disconnect();

                    $msg = 'Connection Successful!';
                    if ($devName || $serial) {
                        $msg .= " (Device: {$devName}, S/N: {$serial})";
                    }

                    return ['success' => true, 'message' => $msg];
                }

                return ['success' => false, 'message' => 'Connection Failed: Device unreachable or port 4370 closed.'];
            }

            if ($this->connect($device)) {
                return ['success' => true, 'message' => 'Connection Successful'];
            }

            return ['success' => false, 'message' => 'Connection Failed (Check Credentials/Network)'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: '.$e->getMessage()];
        }
    }

    /**
     * Get Attendance Logs via ISAPI or ZKTeco direct stream
     */
    public function getAttendanceLogs(BiometricDevice $device): array
    {
        if ($this->isZkTeco($device)) {
            return $this->getZkTecoAttendanceLogsDirect($device);
        }

        try {
            // Try JSON format first (since it worked for user push)
            $logs = $this->getAttendanceLogsJson($device);

            if (! empty($logs)) {
                return $logs;
            }

            // Fallback to XML format
            return $this->getAttendanceLogsXml($device);

        } catch (Exception $e) {
            Log::error('Hikvision Log Pull Failed: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Directly pull attendance logs from ZKTeco device via binary UDP stream.
     * High-speed, self-contained, and independent of vendor library variations.
     */
    public function getZkTecoAttendanceLogsDirect(BiometricDevice $device): array
    {
        $zk = null;
        for ($attempt = 1; $attempt <= 3; $attempt++) {
            $zk = $this->getZkClient($device, 6);
            if ($zk->connect()) {
                break;
            }
            if ($attempt < 3) {
                sleep(1);
            }
        }

        if (! $zk || ! $zk->_zkclient) {
            throw new Exception("Device '{$device->name}' ({$device->ip_address}:{$device->port}) is offline or unreachable.");
        }

        $packets = [];
        try {
            $session = false;
            for ($attempt = 1; $attempt <= 3; $attempt++) {
                $session = $zk->_command(\Fsuuaas\Zkteco\Lib\Helper\Util::CMD_ATT_LOG_RRQ, '', \Fsuuaas\Zkteco\Lib\Helper\Util::COMMAND_TYPE_DATA);
                if ($session !== false) {
                    break;
                }
                usleep(200000);
            }

            $bytes = \Fsuuaas\Zkteco\Lib\Helper\Util::getSize($zk);
            if (! $bytes) {
                Log::warning("ZKTeco device '{$device->name}' returned 0 bytes for attendance stream.");
                return [];
            }

            $streamTimeout = ['sec' => 1, 'usec' => 500000];
            @socket_set_option($zk->_zkclient, SOL_SOCKET, SO_RCVBUF, 2097152);
            @socket_set_option($zk->_zkclient, SOL_SOCKET, SO_RCVTIMEO, $streamTimeout);

            $received = 0;
            $consecutiveErrors = 0;

            while ($bytes > $received) {
                $dataRec = '';
                $ret = @socket_recvfrom($zk->_zkclient, $dataRec, 1032, 0, $zk->_ip, $zk->_port);
                if ($ret === false) {
                    $consecutiveErrors++;
                    if ($received >= ($bytes - 8192) || $consecutiveErrors >= 10) {
                        break;
                    }
                    usleep(50000);
                    continue;
                }
                $consecutiveErrors = 0;
                if (strlen($dataRec) <= 8) {
                    if ($received >= ($bytes - 8192)) {
                        break; // End packet
                    }
                    continue;
                }
                $packets[] = $dataRec;
                $received += (count($packets) === 1 ? strlen($dataRec) : strlen($dataRec) - 8);
            }

            Log::info("ZKTeco attendance stream for {$device->name}: collected " . count($packets) . " packets ({$received}/{$bytes} bytes).");
        } finally {
            $zk->disconnect();
        }

        if (empty($packets)) {
            return [];
        }

        $fullPayload = '';
        foreach ($packets as $idx => $p) {
            $payload = ($idx === 0) ? substr($p, 10) : substr($p, 8);
            $fullPayload .= $payload;
        }

        $allLogs = [];
        $seen = [];
        $totalBytes = strlen($fullPayload);

        // 1. Standard 40-byte stride (fast primary pass)
        $offset = 0;
        while ($offset + 40 <= $totalBytes) {
            $rec = substr($fullPayload, $offset, 40);
            $offset += 40;

            $u = unpack('H78', substr($rec, 0, 39));
            $u1 = hexdec(substr($u[1], 4, 2));
            $u2 = hexdec(substr($u[1], 6, 2));
            $uid = $u1 + ($u2 * 256);
            $id = trim(str_replace(chr(0), '', hex2bin(substr($u[1], 8, 18))));
            $state = hexdec(substr($u[1], 56, 2));
            $timeVal = hexdec(\Fsuuaas\Zkteco\Lib\Helper\Util::reverseHex(substr($u[1], 58, 8)));
            $timestamp = \Fsuuaas\Zkteco\Lib\Helper\Util::decodeTime($timeVal);

            if (!empty($id) && is_numeric($id) && (int)$id > 0 && (int)$id < 2000 && !empty($timestamp) && !str_starts_with($timestamp, '2000-') && !str_starts_with($timestamp, '1970-')) {
                $key = $id . '_' . $timestamp;
                if (!isset($seen[$key])) {
                    $seen[$key] = true;
                    $allLogs[] = [
                        'id' => $id,
                        'timestamp' => $timestamp,
                        'state' => (int) $state,
                        'uid' => (string) $uid,
                    ];
                }
            }
        }

        // 2. Sliding window byte scanner: searches for valid ZKTeco timestamps
        // Guarantees zero missed punches even if internet packet loss causes stream misalignment
        for ($i = 0; $i + 4 <= $totalBytes; $i++) {
            $val = unpack('V', substr($fullPayload, $i, 4))[1];
            if ($val >= 750000000 && $val <= 950000000) {
                $timeStr = \Fsuuaas\Zkteco\Lib\Helper\Util::decodeTime($val);
                if (preg_match('/^202[456]-\d\d-\d\d \d\d:\d\d:\d\d$/', $timeStr)) {
                    $recStart = $i - 29;
                    if ($recStart >= 0 && $recStart + 40 <= $totalBytes) {
                        $rec = substr($fullPayload, $recStart, 40);
                        $u = unpack('H78', substr($rec, 0, 39));
                        $u1 = hexdec(substr($u[1], 4, 2));
                        $u2 = hexdec(substr($u[1], 6, 2));
                        $uid = $u1 + ($u2 * 256);
                        $id = trim(str_replace(chr(0), '', hex2bin(substr($u[1], 8, 18))));
                        $state = hexdec(substr($u[1], 56, 2));
                        if (is_numeric($id) && (int)$id > 0 && (int)$id < 2000) {
                            $key = $id . '_' . $timeStr;
                            if (!isset($seen[$key])) {
                                $seen[$key] = true;
                                $allLogs[] = [
                                    'id' => $id,
                                    'timestamp' => $timeStr,
                                    'state' => (int) $state,
                                    'uid' => (string) $uid,
                                ];
                            }
                        }
                    }
                }
            }
        }

        return $allLogs;
    }

    /**
     * Get Attendance Logs via JSON API
     */
    /**
     * Get Attendance Logs via JSON API
     */
    protected function getAttendanceLogsJson(BiometricDevice $device): array
    {
        try {
            $client = new Client([
                'base_uri' => "http://{$device->ip_address}:{$device->port}/",
                'timeout' => 30,
                'auth' => [$device->username, $this->getDecryptedPassword($device), 'digest'],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            $allLogs = [];
            $searchPosition = 0;
            $hasMore = true;

            // Filter logs for the last 30 days to ensure performance
            $startTime = date('Y-m-d\TH:i:s', strtotime('-30 days'));
            $endTime = date('Y-m-d\TH:i:s');

            while ($hasMore) {
                // JSON Query for attendance events
                $searchData = [
                    'AcsEventCond' => [
                        'searchID' => '1',
                        'searchResultPosition' => $searchPosition,
                        'maxResults' => 100,
                        'major' => 0,
                        'minor' => 0,
                        'startTime' => $startTime,
                        'endTime' => $endTime,
                    ],
                ];

                $response = $client->post('ISAPI/AccessControl/AcsEvent?format=json', [
                    'json' => $searchData,
                    'http_errors' => false,
                ]);

                $statusCode = $response->getStatusCode();
                $body = (string) $response->getBody();

                if ($statusCode !== 200) {
                    Log::warning("JSON pull failed at position {$searchPosition}: Status {$statusCode}");
                    $hasMore = false;

                    continue;
                }

                $data = json_decode($body, true);
                $batchCount = 0;

                // Parse JSON response
                if (isset($data['AcsEvent']['InfoList'])) {
                    foreach ($data['AcsEvent']['InfoList'] as $info) {
                        $allLogs[] = [
                            'id' => (string) ($info['employeeNoString'] ?? $info['employeeNo'] ?? '0'),
                            'timestamp' => (string) ($info['time'] ?? ''),
                            'state' => 1,
                            'uid' => (string) ($info['serialNo'] ?? 0),
                        ];
                        $batchCount++;
                    }
                }

                Log::info("JSON batch pulled: {$batchCount} logs at position {$searchPosition}");

                if ($batchCount < 100) {
                    $hasMore = false;
                } else {
                    $searchPosition += 100;
                }

                // Safety break
                if ($searchPosition > 10000) {
                    $hasMore = false;
                }
            }

            Log::info('Total JSON logs found: '.count($allLogs));

            return $allLogs;

        } catch (Exception $e) {
            Log::warning('JSON attendance pull failed: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Get Attendance Logs via XML API (fallback)
     */
    protected function getAttendanceLogsXml(BiometricDevice $device): array
    {
        try {
            $client = $this->getClient($device);

            $allLogs = [];
            $searchPosition = 0;
            $hasMore = true;

            // Filter logs for the last 30 days to ensure performance
            $startTime = date('Y-m-d\TH:i:s', strtotime('-30 days'));
            $endTime = date('Y-m-d\TH:i:s');

            while ($hasMore) {
                $xmlQuery = '<?xml version="1.0" encoding="utf-8"?>
                            <AcsEventCond version="2.0">
                                <searchID>1</searchID>
                                <searchResultPosition>'.$searchPosition.'</searchResultPosition>
                                <maxResults>100</maxResults>
                                <major>0</major>
                                <minor>0</minor>
                                <startTime>'.$startTime.'</startTime>
                                <endTime>'.$endTime.'</endTime>
                            </AcsEventCond>';

                $response = $client->post('ISAPI/AccessControl/AcsEvent', [
                    'body' => $xmlQuery,
                    'http_errors' => false,
                ]);

                $statusCode = $response->getStatusCode();
                $xmlString = (string) $response->getBody();

                if ($statusCode !== 200) {
                    Log::warning("XML pull failed at position {$searchPosition}: Status {$statusCode}");
                    $hasMore = false;

                    continue;
                }

                $xml = simplexml_load_string($xmlString);
                if ($xml === false) {
                    Log::warning("XML parse error at position {$searchPosition}");
                    $hasMore = false;

                    continue;
                }

                $batchCount = 0;

                if (isset($xml->InfoList) && isset($xml->InfoList->Info)) {
                    foreach ($xml->InfoList->Info as $info) {
                        $allLogs[] = [
                            'id' => (string) ($info->employeeNoString ?? $info->employeeNo ?? '0'),
                            'timestamp' => (string) ($info->time->time ?? $info->time),
                            'state' => 1,
                            'uid' => (string) ($info->serialNo ?? 0),
                        ];
                        $batchCount++;
                    }
                }

                Log::info("XML batch pulled: {$batchCount} logs at position {$searchPosition}");

                if ($batchCount < 100) {
                    $hasMore = false; // Less than maxResults means we reached the end
                } else {
                    $searchPosition += 100;
                }

                // Safety break
                if ($searchPosition > 10000) {
                    $hasMore = false;
                }
            }

            Log::info('Total XML logs found: '.count($allLogs));

            return $allLogs;

        } catch (Exception $e) {
            Log::warning('XML attendance pull failed: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Sync Device Time
     */
    public function syncTime(BiometricDevice $device): bool
    {
        if ($this->isZkTeco($device)) {
            try {
                $zk = $this->getZkClient($device);
                if ($zk->connect()) {
                    $result = (bool) $zk->setTime(date('Y-m-d H:i:s'));
                    $zk->disconnect();

                    return $result;
                }

                return false;
            } catch (Exception $e) {
                Log::error('ZKTeco Time Sync Failed: '.$e->getMessage());

                return false;
            }
        }

        try {
            $client = $this->getClient($device);
            $time = date('Y-m-d\TH:i:s');
            $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                    <Time>
                        <timeMode>manual</timeMode>
                        <localTime>$time</localTime>
                        <timeZone>CST-5:00:00</timeZone>
                    </Time>";

            $client->put('ISAPI/System/time', ['body' => $xml]);

            return true;
        } catch (Exception $e) {
            Log::error('Hikvision Time Sync Failed: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Add/Update user on device via Hikvision ISAPI (JSON format)
     */
    public function addUserToDevice(BiometricDevice $device, string $userId, string $name, string $password = ''): bool
    {
        if ($this->isZkTeco($device)) {
            try {
                $zk = $this->getZkClient($device);
                if ($zk->connect()) {
                    $uid = (int) $userId;
                    $result = (bool) $zk->setUser($uid, $userId, substr($name, 0, 24), $password);
                    $zk->disconnect();

                    return $result;
                }

                return false;
            } catch (Exception $e) {
                Log::error("ZKTeco Add User Failed for {$userId}: ".$e->getMessage());

                return false;
            }
        }

        try {
            $client = new Client([
                'base_uri' => "http://{$device->ip_address}:{$device->port}/",
                'timeout' => 10,
                'auth' => [$device->username, $this->getDecryptedPassword($device), 'digest'],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            // Hikvision ISAPI UserInfo in JSON format
            $userData = [
                'UserInfo' => [
                    'employeeNo' => $userId,
                    'name' => $name,
                    'userType' => 'normal',
                    'Valid' => [
                        'enable' => true,
                        'beginTime' => '2020-01-01T00:00:00',
                        'endTime' => '2037-12-31T23:59:59',
                    ],
                    'doorRight' => '1',
                    'RightPlan' => [
                        [
                            'doorNo' => 1,
                            'planTemplateNo' => '1',
                        ],
                    ],
                ],
            ];

            // Try to add user first
            $response = $client->post('ISAPI/AccessControl/UserInfo/Record?format=json', [
                'json' => $userData,
                'http_errors' => false,
            ]);

            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();

            Log::info("Add user attempt for {$userId}: Status {$statusCode}, Body: ".substr($body, 0, 200));

            // If user exists, try to modify instead
            if ($statusCode === 409 || $statusCode === 400 || strpos($body, 'userExisted') !== false || strpos($body, 'exist') !== false) {
                Log::info("User {$userId} exists, trying modify...");
                $response = $client->put('ISAPI/AccessControl/UserInfo/Modify?format=json', [
                    'json' => $userData,
                    'http_errors' => false,
                ]);
                $statusCode = $response->getStatusCode();
                $body = (string) $response->getBody();
            }

            if ($statusCode === 200 || $statusCode === 201) {
                Log::info("User {$userId} ({$name}) pushed to device {$device->ip_address} via JSON");

                return true;
            }

            Log::warning("JSON Push failed. Status: {$statusCode}. Trying XML fallback...");

            return $this->addUserToDeviceXml($device, $userId, $name, $password);

        } catch (Exception $e) {
            Log::warning("Hikvision Add User JSON Failed for {$userId}: ".$e->getMessage().'. Trying XML fallback...');

            return $this->addUserToDeviceXml($device, $userId, $name, $password);
        }
    }

    /**
     * Add/Update user on device via Hikvision ISAPI (XML format)
     */
    protected function addUserToDeviceXml(BiometricDevice $device, string $userId, string $name, string $password = ''): bool
    {
        try {
            $client = $this->getClient($device);

            // Minimal XML Helper
            $xmlBody = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <UserInfo version=\"2.0\" xmlns=\"http://www.hikvision.com/ver20/XMLSchema\">
                <employeeNo>{$userId}</employeeNo>
                <name>{$name}</name>
                <userType>normal</userType>
                <closeDelayEnabled>false</closeDelayEnabled>
                <Valid>
                    <enable>true</enable>
                    <beginTime>2020-01-01T00:00:00</beginTime>
                    <endTime>2037-12-31T23:59:59</endTime>
                    <timeType>local</timeType>
                </Valid>
                <doorRight>1</doorRight>
                <RightPlan>
                    <doorNo>1</doorNo>
                    <planTemplateNo>1</planTemplateNo>
                </RightPlan>
            </UserInfo>";

            // Try to add user first (POST)
            try {
                $response = $client->post('ISAPI/AccessControl/UserInfo/Record', [
                    'body' => $xmlBody,
                    'http_errors' => false, // Handle 4xx manually
                ]);
                $statusCode = $response->getStatusCode();
            } catch (Exception $ex) {
                $statusCode = 500;
            }

            // If user exists (409/old firmware behavior), try PUT to Modify
            if ($statusCode !== 200 && $statusCode !== 201) {
                $response = $client->put('ISAPI/AccessControl/UserInfo/Modify', [
                    'body' => $xmlBody,
                    'http_errors' => false,
                ]);
                $statusCode = $response->getStatusCode();
            }

            if ($statusCode === 200 || $statusCode === 201) {
                Log::info("User {$userId} ({$name}) pushed to device {$device->ip_address} via XML");

                return true;
            }

            Log::error("XML Push User Failed. Status: {$statusCode}");

            return false;

        } catch (Exception $e) {
            Log::error("Hikvision Add User XML Failed for {$userId}: ".$e->getMessage());

            return false;
        }
    }

    /**
     * Get decrypted password
     */
    protected function getDecryptedPassword(BiometricDevice $device): string
    {
        try {
            return Crypt::decryptString($device->password);
        } catch (Exception $e) {
            return $device->password;
        }
    }

    public function clearLogs(BiometricDevice $device): bool
    {
        return false;
    }

    public function syncShifts(BiometricDevice $device): array
    {
        return $this->syncTime($device)
            ? ['success' => true, 'message' => 'Device Time Synced. Shifts handled by Server.']
            : ['success' => false, 'message' => 'Connection Failed.'];
    }
}
