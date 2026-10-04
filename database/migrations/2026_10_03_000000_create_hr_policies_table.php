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
        Schema::create('hr_policies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('applies_to', ['all', 'specific'])->default('all');
            $table->json('employee_ids')->nullable(); // Array of employee IDs if applies_to == 'specific'
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);

            // Overtime Settings
            $table->boolean('overtime_enabled')->default(true);
            $table->integer('overtime_min_minutes')->default(60); // Minimum minutes after shift end before OT starts
            $table->enum('overtime_rate_type', ['hourly_formula', 'fixed'])->default('hourly_formula');
            $table->decimal('overtime_multiplier', 5, 2)->default(1.50); // e.g. 1.5x
            $table->decimal('overtime_fixed_rate', 10, 2)->default(0.00);

            // Late Arrival & Penalty Settings
            $table->integer('late_grace_minutes')->default(15);
            $table->enum('late_penalty_type', ['3_lates_1_day', 'fixed_per_instance', 'none'])->default('3_lates_1_day');
            $table->decimal('late_penalty_amount', 10, 2)->default(0.00);

            // Absence & Leave Deduction Settings
            $table->enum('absence_deduction_type', ['salary_divided_by_30', 'fixed'])->default('salary_divided_by_30');
            $table->decimal('absence_fixed_amount', 10, 2)->default(0.00);

            // Early Checkout & Half-Day Settings
            $table->enum('early_checkout_penalty_type', ['none', 'fixed_per_instance', '3_earlies_1_day'])->default('none');
            $table->decimal('early_checkout_penalty_amount', 10, 2)->default(0.00);
            $table->decimal('min_hours_half_day', 5, 2)->default(4.00);

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_policies');
    }
};
