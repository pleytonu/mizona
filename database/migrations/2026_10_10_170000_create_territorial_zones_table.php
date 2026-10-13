<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('territorial_zones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('location_id')
                ->constrained('locations')
                ->restrictOnDelete();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('territorial_zones')
                ->restrictOnDelete();

            $table->string('name', 150);
            $table->string('slug', 180)->unique();
            $table->string('zone_type', 50)->default('sector');
            $table->text('description')->nullable();

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->boolean('active')->default(true);

            $table->timestamps();

            $table->index(['location_id', 'active']);
            $table->index(['parent_id', 'active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('territorial_zones');
    }
};
