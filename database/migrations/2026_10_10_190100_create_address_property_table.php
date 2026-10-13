
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('address_property', function (Blueprint $table) {
            $table->id();

            $table->foreignId('property_id')
                ->constrained('properties')
                ->cascadeOnDelete();

            $table->foreignId('address_id')
                ->constrained('addresses')
                ->cascadeOnDelete();

            // principal, secundaria, referencia
            $table->string('relation_type', 30)
                ->default('secundaria');

            $table->boolean('is_primary')->default(false);

            $table->string('notes', 500)->nullable();

            $table->timestamps();

            $table->unique(['property_id', 'address_id']);

            $table->index(['property_id', 'is_primary']);
            $table->index(['address_id', 'relation_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('address_property');
    }
};
