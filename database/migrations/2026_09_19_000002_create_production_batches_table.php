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
        Schema::create('production_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_no')->unique();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('sale_id')->nullable()->constrained('sales')->onDelete('set null');
            $table->foreignId('warehouse_id')->default(1)->constrained('warehouses')->onDelete('cascade');
            $table->decimal('quantity', 12, 4)->default(0);
            $table->date('mfg_date')->nullable();
            $table->date('exp_date')->nullable();
            $table->enum('status', ['produced', 'ready_for_delivery', 'dispatched'])->default('produced');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('production_batch_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_batch_id')->constrained('production_batches')->onDelete('cascade');
            $table->foreignId('raw_material_id')->constrained('raw_materials')->onDelete('cascade');
            $table->decimal('consumed_qty', 12, 4)->default(0);
            $table->string('unit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_batch_items');
        Schema::dropIfExists('production_batches');
    }
};
