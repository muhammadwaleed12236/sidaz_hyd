<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds weekly_off_days JSON column to hr_shifts and hr_employees
     * Stores array of day numbers: 0=Sunday, 1=Monday ... 6=Saturday
     */
    public function up(): void
    {
        // Add to shifts table (shift-level default off days)
        Schema::table('hr_shifts', function (Blueprint $table) {
            $table->json('weekly_off_days')->nullable()->after('grace_minutes')
                ->comment('Array of day numbers (0=Sun,1=Mon..6=Sat) that are weekly off for this shift');
        });

        // Add to employees table (per-employee override of shift off days)
        Schema::table('hr_employees', function (Blueprint $table) {
            $table->json('weekly_off_days')->nullable()->after('punch_gap_minutes')
                ->comment('Per-employee weekly off day override. Overrides shift weekly_off_days if set.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hr_shifts', function (Blueprint $table) {
            $table->dropColumn('weekly_off_days');
        });

        Schema::table('hr_employees', function (Blueprint $table) {
            $table->dropColumn('weekly_off_days');
        });
    }
};
