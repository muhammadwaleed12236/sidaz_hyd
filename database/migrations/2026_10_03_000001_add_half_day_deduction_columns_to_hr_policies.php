<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hr_policies', function (Blueprint $table) {
            if (!Schema::hasColumn('hr_policies', 'half_day_deduction_type')) {
                $table->enum('half_day_deduction_type', ['half_day_salary', 'fixed', 'none'])->default('half_day_salary')->after('min_hours_half_day');
            }
            if (!Schema::hasColumn('hr_policies', 'half_day_fixed_amount')) {
                $table->decimal('half_day_fixed_amount', 10, 2)->default(0.00)->after('half_day_deduction_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hr_policies', function (Blueprint $table) {
            if (Schema::hasColumn('hr_policies', 'half_day_deduction_type')) {
                $table->dropColumn('half_day_deduction_type');
            }
            if (Schema::hasColumn('hr_policies', 'half_day_fixed_amount')) {
                $table->dropColumn('half_day_fixed_amount');
            }
        });
    }
};
