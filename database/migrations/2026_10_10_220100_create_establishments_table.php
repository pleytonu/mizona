
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('establishments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->restrictOnDelete();

            $table->foreignId('address_id')
                ->nullable()
                ->constrained('addresses')
                ->restrictOnDelete();

            $table->foreignId('property_unit_id')
                ->nullable()
                ->constrained('property_units')
                ->restrictOnDelete();

            $table->string('name', 180);
            $table->string('slug', 200)->unique();

            $table->string('establishment_type', 50)
                ->default('sucursal');

            $table->text('description')->nullable();

            $table->boolean('active')->default(true);

            $table->timestamps();

            $table->index(['organization_id', 'active']);
            $table->index(['address_id', 'active']);
            $table->index(['property_unit_id', 'active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('establishments');
    }
};
