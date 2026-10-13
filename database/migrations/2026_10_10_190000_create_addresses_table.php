
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            // Ubicación administrativa, por ejemplo, una comuna
            $table->foreignId('location_id')
                ->constrained('locations')
                ->restrictOnDelete();

            // Sector opcional
            $table->foreignId('territorial_zone_id')
                ->nullable()
                ->constrained('territorial_zones')
                ->restrictOnDelete();

            $table->string('street_name', 180)->nullable();
            $table->string('street_number', 30)->nullable();

            // Oficina, local, departamento, piso, etc.
            $table->string('unit', 100)->nullable();
            $table->string('postal_code', 20)->nullable();

            $table->string('reference', 500)->nullable();

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->boolean('active')->default(true);

            $table->timestamps();

            $table->index([
                'location_id',
                'street_name',
                'street_number',
            ]);

            $table->index(['territorial_zone_id', 'active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
