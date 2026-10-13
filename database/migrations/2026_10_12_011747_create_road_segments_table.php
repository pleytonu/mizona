<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('road_segments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('road_id')
                ->constrained('roads')
                ->restrictOnDelete();

            $table->foreignId('location_id')
                ->constrained('locations')
                ->restrictOnDelete();

            $table->string('segment_name', 180)->nullable();

            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();

            $table->decimal('start_latitude', 10, 7)->nullable();
            $table->decimal('start_longitude', 10, 7)->nullable();
            $table->decimal('end_latitude', 10, 7)->nullable();
            $table->decimal('end_longitude', 10, 7)->nullable();

            $table->json('geometry')->nullable();

            $table->boolean('active')->default(true);

            $table->timestamps();

            $table->index(['road_id', 'location_id']);
            $table->index(['location_id', 'active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('road_segments');
    }
};