
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('structure_levels', function (Blueprint $table) {
            $table->id();

            $table->foreignId('structure_id')
                ->constrained('structures')
                ->restrictOnDelete();

            // Ejemplos: -1 subterráneo, 0 nivel calle, 1 primer piso
            $table->integer('level_number');

            $table->string('name', 100)->nullable();
            $table->string('description', 500)->nullable();

            $table->timestamps();

            $table->unique(['structure_id', 'level_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('structure_levels');
    }
};
