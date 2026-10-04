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
        if (! Schema::hasColumn('hr_payrolls', 'loan_deduction')) {
            Schema::table('hr_payrolls', function (Blueprint $table) {
                $table->decimal('loan_deduction', 12, 2)->default(0)->after('manual_deductions');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('hr_payrolls', 'loan_deduction')) {
            Schema::table('hr_payrolls', function (Blueprint $table) {
                $table->dropColumn('loan_deduction');
            });
        }
    }
};
