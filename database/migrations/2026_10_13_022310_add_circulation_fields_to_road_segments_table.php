<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('road_segments', function (Blueprint $table) {
            $table->string('direction_type', 30)
                ->default('unknown')
                ->after('geometry');

            $table->boolean('forward_allowed')
                ->default(false)
                ->after('direction_type');

            $table->boolean('reverse_allowed')
                ->default(false)
                ->after('forward_allowed');

            $table->string('circulation_source', 100)
                ->nullable()
                ->after('reverse_allowed');

            $table->timestamp('circulation_verified_at')
                ->nullable()
                ->after('circulation_source');
        });
    }

    public function down(): void
    {
        Schema::table('road_segments', function (Blueprint $table) {
            $table->dropColumn([
                'direction_type',
                'forward_allowed',
                'reverse_allowed',
                'circulation_source',
                'circulation_verified_at',
            ]);
        });
    }
};