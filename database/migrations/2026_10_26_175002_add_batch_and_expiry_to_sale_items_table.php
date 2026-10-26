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
        Schema::table('sale_items', function (Blueprint $table) {
            if (!Schema::hasColumn('sale_items', 'batch_no')) {
                $table->string('batch_no')->nullable()->after('color');
            }
            if (!Schema::hasColumn('sale_items', 'mfg_date')) {
                $table->date('mfg_date')->nullable()->after('batch_no');
            }
            if (!Schema::hasColumn('sale_items', 'exp_date')) {
                $table->date('exp_date')->nullable()->after('mfg_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropColumn(['batch_no', 'mfg_date', 'exp_date']);
        });
    }
};
