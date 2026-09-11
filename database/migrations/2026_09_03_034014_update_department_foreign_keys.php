<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update existing data to HR department ID 1 to prevent constraint violations
        $defaultHrDeptId = DB::table('hr_departments')->first()->id ?? 1;
        DB::table('raw_materials')->update(['department_id' => $defaultHrDeptId]);
        DB::table('packaging_materials')->update(['department_id' => $defaultHrDeptId]);
        DB::table('formulations')->update(['department_id' => $defaultHrDeptId]);

        // Safely drop old foreign keys using direct statements (which execute immediately and can be caught)
        $tables = [
            'raw_materials' => 'raw_materials_department_id_foreign',
            'packaging_materials' => 'packaging_materials_department_id_foreign',
            'formulations' => 'formulations_department_id_foreign'
        ];

        foreach ($tables as $table => $fk) {
            try {
                DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$fk}`");
            } catch (\Exception $e) {
                // Ignore if it doesn't exist
            }
        }

        // 1. Raw Materials
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->foreign('department_id')->references('id')->on('hr_departments')->onDelete('cascade');
        });

        // 2. Packaging Materials
        Schema::table('packaging_materials', function (Blueprint $table) {
            $table->foreign('department_id')->references('id')->on('hr_departments')->onDelete('cascade');
        });

        // 3. Formulations
        Schema::table('formulations', function (Blueprint $table) {
            $table->foreign('department_id')->references('id')->on('hr_departments')->onDelete('cascade');
        });

        // Drop departments table
        Schema::dropIfExists('departments');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add it back
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });

        Schema::table('raw_materials', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        });

        Schema::table('packaging_materials', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        });

        Schema::table('formulations', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        });
    }
};
