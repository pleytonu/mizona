<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\LocationType;
use Illuminate\Database\Seeder;

class LocationTypeSeeder extends Seeder
{
    public function run(): void
    {
        $chile = Country::where('iso2', 'CL')->firstOrFail();

        $region = LocationType::updateOrCreate(
            [
                'country_id' => $chile->id,
                'slug' => 'region',
            ],
            [
                'parent_id' => null,
                'name' => 'Región',
                'level' => 1,
                'is_administrative' => true,
                'active' => true,
            ]
        );

        $provincia = LocationType::updateOrCreate(
            [
                'country_id' => $chile->id,
                'slug' => 'provincia',
            ],
            [
                'parent_id' => $region->id,
                'name' => 'Provincia',
                'level' => 2,
                'is_administrative' => true,
                'active' => true,
            ]
        );

        $comuna = LocationType::updateOrCreate(
            [
                'country_id' => $chile->id,
                'slug' => 'comuna',
            ],
            [
                'parent_id' => $provincia->id,
                'name' => 'Comuna',
                'level' => 3,
                'is_administrative' => true,
                'active' => true,
            ]
        );

        LocationType::updateOrCreate(
            [
                'country_id' => $chile->id,
                'slug' => 'sector',
            ],
            [
                'parent_id' => $comuna->id,
                'name' => 'Sector',
                'level' => 4,
                'is_administrative' => false,
                'active' => true,
            ]
        );
    }
}