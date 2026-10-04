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
        Schema::table('hr_payrolls', function (Blueprint $table) {
            if (! Schema::hasColumn('hr_payrolls', 'account_id')) {
                $table->foreignId('account_id')->nullable()->constrained('accounts')->nullOnDelete()->after('payment_date');
            }
            if (! Schema::hasColumn('hr_payrolls', 'payment_method')) {
                $table->string('payment_method')->nullable()->default('cash')->after('account_id');
            }
            if (! Schema::hasColumn('hr_payrolls', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('payment_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hr_payrolls', function (Blueprint $table) {
            if (Schema::hasColumn('hr_payrolls', 'account_id')) {
                $table->dropForeign(['account_id']);
                $table->dropColumn(['account_id', 'payment_method', 'payment_reference']);
            }
        });
    }
};
