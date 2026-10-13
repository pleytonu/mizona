<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('road_name_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('road_id')
                ->constrained('roads')
                ->restrictOnDelete();

            $table->string('previous_name', 180)->nullable();
            $table->string('new_name', 180);

            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();

            $table->string('change_type', 50)->default('cambio_nombre');
            $table->text('reason')->nullable();

            $table->string('document_type', 100)->nullable();
            $table->string('document_number', 100)->nullable();
            $table->date('document_date')->nullable();
            $table->string('document_reference', 500)->nullable();

            $table->string('verification_status', 30)->default('pendiente');

            $table->timestamps();

            $table->index(['road_id', 'effective_from']);
            $table->index('verification_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('road_name_histories');
    }
};
