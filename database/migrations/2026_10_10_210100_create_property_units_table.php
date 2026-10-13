
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_units', function (Blueprint $table) {
            $table->id();

            $table->foreignId('structure_level_id')
                ->constrained('structure_levels')
                ->restrictOnDelete();

            $table->string('unit_code', 80);
            $table->string('name', 180)->nullable();

            // oficina, local, taller, departamento, bodega...
            $table->string('unit_type', 50)->default('local');

            $table->decimal('area_m2', 12, 2)->nullable();
            $table->string('description', 1000)->nullable();

            $table->boolean('active')->default(true);

            $table->timestamps();

            $table->unique(['structure_level_id', 'unit_code']);
            $table->index(['unit_type', 'active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_units');
    }
};
