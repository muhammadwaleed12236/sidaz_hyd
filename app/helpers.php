<?php

if (! function_exists('format_minutes')) {
    /**
     * Format minutes into human-readable hours and minutes (e.g. 146m -> 2h 26m, 45m -> 45m).
     */
    function format_minutes($minutes) {
        $mins = (int) $minutes;
        if ($mins <= 0) {
            return '0m';
        }
        $hrs = floor($mins / 60);
        $rem = $mins % 60;
        
        if ($hrs > 0 && $rem > 0) {
            return "{$hrs}h {$rem}m";
        } elseif ($hrs > 0) {
            return "{$hrs}h";
        } else {
            return "{$rem}m";
        }
    }
}
