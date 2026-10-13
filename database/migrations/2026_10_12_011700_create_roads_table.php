<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roads', function (Blueprint $table) {
            $table->id();

            // Identificación de la vía
            $table->string('road_type', 50)->default('calle');
            $table->string('current_name', 180);
            $table->string('official_code', 100)->nullable();
            $table->text('description')->nullable();

            // Estado del registro
            $table->boolean('active')->default(true);

            $table->timestamps();

            // Índices no únicos: se permiten nombres repetidos
            $table->index(['current_name', 'road_type']);
            $table->index('official_code');
            $table->index('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roads');
    }
};
