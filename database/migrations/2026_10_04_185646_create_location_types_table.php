<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('location_types', function (Blueprint $table) {
            $table->id();

            $table->foreignId('country_id')
                ->constrained('countries')
                ->cascadeOnDelete();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('location_types')
                ->nullOnDelete();

            $table->string('name', 100);
            $table->string('slug', 100);

            $table->unsignedInteger('level')->default(1);

            $table->boolean('is_administrative')->default(true);
            $table->boolean('active')->default(true);

            $table->timestamps();

            $table->unique(['country_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('location_types');
    }
};