<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('road_segments', function (Blueprint $table) {
            $table->decimal('length_meters', 12, 2)
                ->nullable()
                ->after('geometry')
                ->comment('Longitud calculada de la geometría en metros');
        });
    }

    public function down(): void
    {
        Schema::table('road_segments', function (Blueprint $table) {
            $table->dropColumn('length_meters');
        });
    }
};
