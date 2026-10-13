<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();

            // Sector o zona territorial a la que pertenece
            $table->foreignId('territorial_zone_id')
                ->nullable()
                ->constrained('territorial_zones')
                ->restrictOnDelete();

            // Identificador interno de Mizona
            $table->string('name', 180)->nullable();
            $table->string('slug', 200)->unique();

            // Identificación catastral, si está disponible
            $table->string('cadastral_code', 100)->nullable();

            // Tipo general de predio
            $table->string('property_type', 50)->default('terreno');

            // Superficie en metros cuadrados, si se conoce
            $table->decimal('area_m2', 14, 2)->nullable();

            $table->string('description', 1000)->nullable();

            $table->boolean('active')->default(true);

            $table->timestamps();

            $table->index(['territorial_zone_id', 'active']);
            $table->index('cadastral_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
