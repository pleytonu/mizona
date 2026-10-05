<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        Country::updateOrCreate(
            ['iso2' => 'CL'],
            [
                'name' => 'Chile',
                'slug' => 'chile',
                'iso3' => 'CHL',
                'phone_code' => '+56',
                'active' => true,
            ]
        );
    }
}