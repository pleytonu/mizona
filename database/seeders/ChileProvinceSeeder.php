<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Location;
use App\Models\LocationType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ChileProvinceSeeder extends Seeder
{
    public function run(): void
    {
        $chile = Country::where('iso2', 'CL')->firstOrFail();

        $provinceType = LocationType::where('country_id', $chile->id)
            ->where('slug', 'provincia')
            ->firstOrFail();

        $regions = Location::where('country_id', $chile->id)
            ->whereHas('type', function ($query) {
                $query->where('slug', 'region');
            })
            ->get()
            ->keyBy('code');

        $provinces = [

            // Región de Arica y Parinacota
            [
                'region_code' => '15',
                'name' => 'Provincia de Arica',
                'code' => '151',
            ],
            [
                'region_code' => '15',
                'name' => 'Provincia de Parinacota',
                'code' => '152',
            ],

            // Región de Tarapacá
            [
                'region_code' => '01',
                'name' => 'Provincia de Iquique',
                'code' => '011',
            ],
            [
                'region_code' => '01',
                'name' => 'Provincia del Tamarugal',
                'code' => '014',
            ],

            // Región de Antofagasta
            [
                'region_code' => '02',
                'name' => 'Provincia de Antofagasta',
                'code' => '021',
            ],
            [
                'region_code' => '02',
                'name' => 'Provincia de El Loa',
                'code' => '022',
            ],
            [
                'region_code' => '02',
                'name' => 'Provincia de Tocopilla',
                'code' => '023',
            ],

            // Región de Atacama
            [
                'region_code' => '03',
                'name' => 'Provincia de Copiapó',
                'code' => '031',
            ],
            [
                'region_code' => '03',
                'name' => 'Provincia de Chañaral',
                'code' => '032',
            ],
            [
                'region_code' => '03',
                'name' => 'Provincia de Huasco',
                'code' => '033',
            ],

            // Región de Coquimbo
            [
                'region_code' => '04',
                'name' => 'Provincia de Elqui',
                'code' => '041',
            ],
            [
                'region_code' => '04',
                'name' => 'Provincia de Limarí',
                'code' => '042',
            ],
            [
                'region_code' => '04',
                'name' => 'Provincia de Choapa',
                'code' => '043',
            ],

            // Región de Valparaíso
            [
                'region_code' => '05',
                'name' => 'Provincia de Valparaíso',
                'code' => '051',
            ],
            [
                'region_code' => '05',
                'name' => 'Provincia de Isla de Pascua',
                'code' => '052',
            ],
            [
                'region_code' => '05',
                'name' => 'Provincia de Los Andes',
                'code' => '053',
            ],
            [
                'region_code' => '05',
                'name' => 'Provincia de Petorca',
                'code' => '054',
            ],
            [
                'region_code' => '05',
                'name' => 'Provincia de Quillota',
                'code' => '055',
            ],
            [
                'region_code' => '05',
                'name' => 'Provincia de San Antonio',
                'code' => '056',
            ],
            [
                'region_code' => '05',
                'name' => 'Provincia de San Felipe de Aconcagua',
                'code' => '057',
            ],
            [
                'region_code' => '05',
                'name' => 'Provincia de Marga Marga',
                'code' => '058',
            ],

            // Región Metropolitana de Santiago
            [
                'region_code' => '13',
                'name' => 'Provincia de Santiago',
                'code' => '131',
            ],
            [
                'region_code' => '13',
                'name' => 'Provincia de Cordillera',
                'code' => '132',
            ],
            [
                'region_code' => '13',
                'name' => 'Provincia de Chacabuco',
                'code' => '133',
            ],
            [
                'region_code' => '13',
                'name' => 'Provincia de Maipo',
                'code' => '134',
            ],
            [
                'region_code' => '13',
                'name' => 'Provincia de Melipilla',
                'code' => '135',
            ],
            [
                'region_code' => '13',
                'name' => 'Provincia de Talagante',
                'code' => '136',
            ],

            // Región de O'Higgins
            [
                'region_code' => '06',
                'name' => 'Provincia de Cachapoal',
                'code' => '061',
            ],
            [
                'region_code' => '06',
                'name' => 'Provincia de Colchagua',
                'code' => '062',
            ],
            [
                'region_code' => '06',
                'name' => 'Provincia de Cardenal Caro',
                'code' => '063',
            ],

            // Región del Maule
            [
                'region_code' => '07',
                'name' => 'Provincia de Talca',
                'code' => '071',
            ],
            [
                'region_code' => '07',
                'name' => 'Provincia de Curicó',
                'code' => '072',
            ],
            [
                'region_code' => '07',
                'name' => 'Provincia de Linares',
                'code' => '073',
            ],
            [
                'region_code' => '07',
                'name' => 'Provincia de Cauquenes',
                'code' => '074',
            ],

            // Región de Ñuble
            [
                'region_code' => '16',
                'name' => 'Provincia de Diguillín',
                'code' => '161',
            ],
            [
                'region_code' => '16',
                'name' => 'Provincia de Itata',
                'code' => '162',
            ],
            [
                'region_code' => '16',
                'name' => 'Provincia de Punilla',
                'code' => '163',
            ],

            // Región del Biobío
            [
                'region_code' => '08',
                'name' => 'Provincia de Concepción',
                'code' => '081',
            ],
            [
                'region_code' => '08',
                'name' => 'Provincia de Arauco',
                'code' => '082',
            ],
            [
                'region_code' => '08',
                'name' => 'Provincia de Biobío',
                'code' => '083',
            ],

            // Región de La Araucanía
            [
                'region_code' => '09',
                'name' => 'Provincia de Cautín',
                'code' => '091',
            ],
            [
                'region_code' => '09',
                'name' => 'Provincia de Malleco',
                'code' => '092',
            ],

            // Región de Los Ríos
            [
                'region_code' => '14',
                'name' => 'Provincia de Valdivia',
                'code' => '141',
            ],
            [
                'region_code' => '14',
                'name' => 'Provincia del Ranco',
                'code' => '142',
            ],

            // Región de Los Lagos
            [
                'region_code' => '10',
                'name' => 'Provincia de Llanquihue',
                'code' => '101',
            ],
            [
                'region_code' => '10',
                'name' => 'Provincia de Chiloé',
                'code' => '102',
            ],
            [
                'region_code' => '10',
                'name' => 'Provincia de Osorno',
                'code' => '103',
            ],
            [
                'region_code' => '10',
                'name' => 'Provincia de Palena',
                'code' => '104',
            ],

            // Región de Aysén
            [
                'region_code' => '11',
                'name' => 'Provincia de Coyhaique',
                'code' => '111',
            ],
            [
                'region_code' => '11',
                'name' => 'Provincia de Aysén',
                'code' => '112',
            ],
            [
                'region_code' => '11',
                'name' => 'Provincia de General Carrera',
                'code' => '113',
            ],
            [
                'region_code' => '11',
                'name' => 'Provincia de Capitán Prat',
                'code' => '114',
            ],

            // Región de Magallanes
            [
                'region_code' => '12',
                'name' => 'Provincia de Magallanes',
                'code' => '121',
            ],
            [
                'region_code' => '12',
                'name' => 'Provincia de Última Esperanza',
                'code' => '122',
            ],
            [
                'region_code' => '12',
                'name' => 'Provincia de Tierra del Fuego',
                'code' => '123',
            ],
            [
                'region_code' => '12',
                'name' => 'Provincia de la Antártica Chilena',
                'code' => '124',
            ],
        ];

        foreach ($provinces as $province) {
            $region = $regions->get($province['region_code']);

            if (! $region) {
                continue;
            }

            Location::updateOrCreate(
                [
                    'country_id' => $chile->id,
                    'location_type_id' => $provinceType->id,
                    'parent_id' => $region->id,
                    'code' => $province['code'],
                ],
                [
                    'name' => $province['name'],
                    'slug' => Str::slug($province['name']),
                    'official_code' => $province['code'],
                    'active' => true,
                ]
            );
        }
    }
}