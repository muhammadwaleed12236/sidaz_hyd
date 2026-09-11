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
        Schema::table('formulations', function (Blueprint $table) {
            $table->string('doc_no')->nullable()->after('formulation_code');
            $table->string('batch_no')->nullable()->after('doc_no');
            $table->integer('qty_of_dropper')->nullable()->after('batch_no');
            $table->decimal('weight_per_bottle', 10, 2)->nullable()->after('qty_of_dropper');
            $table->string('bmr_no')->nullable()->after('weight_per_bottle');
            $table->integer('total_packs')->nullable()->after('bmr_no');
            $table->date('issue_date')->nullable()->after('total_packs');
            $table->date('mfg_date')->nullable()->after('issue_date');
            $table->date('exp_date')->nullable()->after('mfg_date');
            $table->string('company_name')->nullable()->after('exp_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formulations', function (Blueprint $table) {
            $table->dropColumn([
                'doc_no', 'batch_no', 'qty_of_dropper', 'weight_per_bottle', 
                'bmr_no', 'total_packs', 'issue_date', 'mfg_date', 'exp_date', 'company_name'
            ]);
        });
    }
};
