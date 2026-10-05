<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Location;
use App\Models\LocationType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ChileLocationSeeder extends Seeder
{
    public function run(): void
    {
        $chile = Country::where('iso2', 'CL')->firstOrFail();

        $regionType = LocationType::where('country_id', $chile->id)
            ->where('slug', 'region')
            ->firstOrFail();

        $regions = [
            [
                'name' => 'Región de Arica y Parinacota',
                'code' => '15',
                'official_code' => 'XV',
                'latitude' => -18.4783,
                'longitude' => -70.3126,
            ],
            [
                'name' => 'Región de Tarapacá',
                'code' => '01',
                'official_code' => 'I',
                'latitude' => -20.2141,
                'longitude' => -70.1524,
            ],
            [
                'name' => 'Región de Antofagasta',
                'code' => '02',
                'official_code' => 'II',
                'latitude' => -23.6509,
                'longitude' => -70.3975,
            ],
            [
                'name' => 'Región de Atacama',
                'code' => '03',
                'official_code' => 'III',
                'latitude' => -27.3668,
                'longitude' => -70.3322,
            ],
            [
                'name' => 'Región de Coquimbo',
                'code' => '04',
                'official_code' => 'IV',
                'latitude' => -29.9533,
                'longitude' => -71.3436,
            ],
            [
                'name' => 'Región de Valparaíso',
                'code' => '05',
                'official_code' => 'V',
                'latitude' => -33.0472,
                'longitude' => -71.6127,
            ],
            [
                'name' => 'Región Metropolitana de Santiago',
                'code' => '13',
                'official_code' => 'RM',
                'latitude' => -33.4489,
                'longitude' => -70.6693,
            ],
            [
                'name' => 'Región del Libertador General Bernardo O’Higgins',
                'code' => '06',
                'official_code' => 'VI',
                'latitude' => -34.1708,
                'longitude' => -70.7444,
            ],
            [
                'name' => 'Región del Maule',
                'code' => '07',
                'official_code' => 'VII',
                'latitude' => -35.4264,
                'longitude' => -71.6554,
            ],
            [
                'name' => 'Región de Ñuble',
                'code' => '16',
                'official_code' => 'XVI',
                'latitude' => -36.6066,
                'longitude' => -72.1034,
            ],
            [
                'name' => 'Región del Biobío',
                'code' => '08',
                'official_code' => 'VIII',
                'latitude' => -36.8201,
                'longitude' => -73.0444,
            ],
            [
                'name' => 'Región de La Araucanía',
                'code' => '09',
                'official_code' => 'IX',
                'latitude' => -38.7359,
                'longitude' => -72.5904,
            ],
            [
                'name' => 'Región de Los Ríos',
                'code' => '14',
                'official_code' => 'XIV',
                'latitude' => -39.8196,
                'longitude' => -73.2452,
            ],
            [
                'name' => 'Región de Los Lagos',
                'code' => '10',
                'official_code' => 'X',
                'latitude' => -41.4693,
                'longitude' => -72.9424,
            ],
            [
                'name' => 'Región de Aysén del General Carlos Ibáñez del Campo',
                'code' => '11',
                'official_code' => 'XI',
                'latitude' => -45.5712,
                'longitude' => -72.0683,
            ],
            [
                'name' => 'Región de Magallanes y de la Antártica Chilena',
                'code' => '12',
                'official_code' => 'XII',
                'latitude' => -53.1638,
                'longitude' => -70.9171,
            ],
        ];

        foreach ($regions as $region) {
            Location::updateOrCreate(
                [
                    'country_id' => $chile->id,
                    'location_type_id' => $regionType->id,
                    'parent_id' => null,
                    'code' => $region['code'],
                ],
                [
                    'name' => $region['name'],
                    'slug' => Str::slug($region['name']),
                    'official_code' => $region['official_code'],
                    'latitude' => $region['latitude'],
                    'longitude' => $region['longitude'],
                    'active' => true,
                ]
            );
        }
    }
}