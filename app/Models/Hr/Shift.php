<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $table = 'hr_shifts';

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'break_start',
        'break_end',
        'grace_minutes',
        'is_default',
        'weekly_off_days',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'weekly_off_days' => 'array',
    ];


    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Check if a given time is late based on shift start time and grace period
     */
    public function isLate($checkInTime)
    {
        $shiftStart = \Carbon\Carbon::parse($this->start_time);
        $graceEnd = $shiftStart->copy()->addMinutes($this->grace_minutes);
        $checkIn = \Carbon\Carbon::parse($checkInTime);
        
        return $checkIn->gt($graceEnd);
    }

    /**
     * Calculate late minutes
     */
    public function getLateMinutes($checkInTime)
    {
        $shiftStart = \Carbon\Carbon::parse($this->start_time);
        $checkIn = \Carbon\Carbon::parse($checkInTime);
        
        if ($checkIn->gt($shiftStart)) {
            return $checkIn->diffInMinutes($shiftStart);
        }
        return 0;
    }

    /**
     * Check if early leave
     */
    public function isEarlyLeave($checkOutTime)
    {
        $shiftEnd = \Carbon\Carbon::parse($this->end_time);
        $checkOut = \Carbon\Carbon::parse($checkOutTime);
        
        return $checkOut->lt($shiftEnd);
    }

    /**
     * Get the effective weekly off days for this shift
     * Returns array of day numbers (0=Sun, 1=Mon ... 6=Sat)
     * Defaults to [0] (Sunday) if not configured
     */
    public function getWeeklyOffDaysArray(): array
    {
        if (!empty($this->weekly_off_days)) {
            return (array) $this->weekly_off_days;
        }
        return [0]; // Default: Sunday only
    }

    /**
     * Get default shift
     */
    public static function getDefault()
    {
        return self::where('is_default', true)->first();
    }
}
