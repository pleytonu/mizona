<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->foreignId('road_id')
                ->nullable()
                ->after('location_id')
                ->constrained('roads')
                ->restrictOnDelete();

            $table->foreignId('road_segment_id')
                ->nullable()
                ->after('road_id')
                ->constrained('road_segments')
                ->restrictOnDelete();

            $table->index(['road_id', 'street_number']);
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropIndex(['road_id', 'street_number']);

            $table->dropConstrainedForeignId('road_segment_id');
            $table->dropConstrainedForeignId('road_id');
        });
    }
};
