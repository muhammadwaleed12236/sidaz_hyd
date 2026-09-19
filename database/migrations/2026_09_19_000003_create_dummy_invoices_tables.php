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
        Schema::create('dummy_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->date('invoice_date');
            $table->date('order_date')->nullable();
            $table->string('customer_name');
            $table->text('customer_address')->nullable();
            $table->string('gate_pass_no')->nullable();
            $table->string('licence_no')->nullable();
            $table->string('licence_expiry')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->text('warranty_text')->nullable();
            $table->string('signatory_name')->default('SEEMA KHAN');
            $table->string('signatory_title')->default('Production Incharge');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('dummy_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dummy_invoice_id')->constrained('dummy_invoices')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name');
            $table->string('pack')->nullable();
            $table->string('batch_no')->nullable();
            $table->string('mfg_date')->nullable();
            $table->string('exp_date')->nullable();
            $table->decimal('qty', 15, 2)->default(0);
            $table->decimal('mrp', 15, 2)->default(0);
            $table->decimal('tp', 15, 2)->default(0);
            $table->decimal('dp', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dummy_invoice_items');
        Schema::dropIfExists('dummy_invoices');
    }
};
