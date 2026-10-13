
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('structures', function (Blueprint $table) {
            $table->id();

            $table->foreignId('property_id')
                ->constrained('properties')
                ->restrictOnDelete();

            $table->string('name', 180);
            $table->string('structure_type', 50)->default('edificio');

            $table->decimal('built_area_m2', 14, 2)->nullable();

            $table->string('description', 1000)->nullable();

            $table->boolean('active')->default(true);

            $table->timestamps();

            $table->index(['property_id', 'active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('structures');
    }
};
