<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Location;
use App\Models\LocationType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ChileCommuneSeeder extends Seeder
{
    public function run(): void
    {
        $chile = Country::where('iso2', 'CL')->firstOrFail();

        $communeType = LocationType::where('country_id', $chile->id)
            ->where('slug', 'comuna')
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Provincias de Chile
        |--------------------------------------------------------------------------
        |
        | Buscamos las provincias por su código. De esta forma no dependemos
        | de IDs fijos de la base de datos.
        |
        */

        $provinces = Location::where('country_id', $chile->id)
            ->whereHas('type', function ($query) {
                $query->where('slug', 'provincia');
            })
            ->get()
            ->keyBy('code');

        /*
        |--------------------------------------------------------------------------
        | Comunas
        |--------------------------------------------------------------------------
        */

        $communes = [

            /*
            |--------------------------------------------------------------------------
            | Región Metropolitana
            |--------------------------------------------------------------------------
            */

            // Provincia de Santiago
            [
                'province_code' => '131',
                'name' => 'Santiago',
                'code' => '13101',
            ],
            [
                'province_code' => '131',
                'name' => 'Cerrillos',
                'code' => '13102',
            ],
            [
                'province_code' => '131',
                'name' => 'Cerro Navia',
                'code' => '13103',
            ],
            [
                'province_code' => '131',
                'name' => 'Conchalí',
                'code' => '13104',
            ],
            [
                'province_code' => '131',
                'name' => 'El Bosque',
                'code' => '13105',
            ],
            [
                'province_code' => '131',
                'name' => 'Estación Central',
                'code' => '13106',
            ],
            [
                'province_code' => '131',
                'name' => 'Huechuraba',
                'code' => '13107',
            ],
            [
                'province_code' => '131',
                'name' => 'Independencia',
                'code' => '13108',
            ],
            [
                'province_code' => '131',
                'name' => 'La Cisterna',
                'code' => '13109',
            ],
            [
                'province_code' => '131',
                'name' => 'La Florida',
                'code' => '13110',
            ],
            [
                'province_code' => '131',
                'name' => 'La Granja',
                'code' => '13111',
            ],
            [
                'province_code' => '131',
                'name' => 'La Pintana',
                'code' => '13112',
            ],
            [
                'province_code' => '131',
                'name' => 'La Reina',
                'code' => '13113',
            ],
            [
                'province_code' => '131',
                'name' => 'Las Condes',
                'code' => '13114',
            ],
            [
                'province_code' => '131',
                'name' => 'Lo Barnechea',
                'code' => '13115',
            ],
            [
                'province_code' => '131',
                'name' => 'Lo Espejo',
                'code' => '13116',
            ],
            [
                'province_code' => '131',
                'name' => 'Lo Prado',
                'code' => '13117',
            ],
            [
                'province_code' => '131',
                'name' => 'Macul',
                'code' => '13118',
            ],
            [
                'province_code' => '131',
                'name' => 'Maipú',
                'code' => '13119',
            ],
            [
                'province_code' => '131',
                'name' => 'Ñuñoa',
                'code' => '13120',
            ],
            [
                'province_code' => '131',
                'name' => 'Pedro Aguirre Cerda',
                'code' => '13121',
            ],
            [
                'province_code' => '131',
                'name' => 'Peñalolén',
                'code' => '13122',
            ],
            [
                'province_code' => '131',
                'name' => 'Providencia',
                'code' => '13123',
            ],
            [
                'province_code' => '131',
                'name' => 'Pudahuel',
                'code' => '13124',
            ],
            [
                'province_code' => '131',
                'name' => 'Quilicura',
                'code' => '13125',
            ],
            [
                'province_code' => '131',
                'name' => 'Quinta Normal',
                'code' => '13126',
            ],
            [
                'province_code' => '131',
                'name' => 'Recoleta',
                'code' => '13127',
            ],
            [
                'province_code' => '131',
                'name' => 'Renca',
                'code' => '13128',
            ],
            [
                'province_code' => '131',
                'name' => 'San Joaquín',
                'code' => '13129',
            ],
            [
                'province_code' => '131',
                'name' => 'San Miguel',
                'code' => '13130',
            ],
            [
                'province_code' => '131',
                'name' => 'San Ramón',
                'code' => '13131',
            ],
            [
                'province_code' => '131',
                'name' => 'Vitacura',
                'code' => '13132',
            ],

            // Provincia de Cordillera
            [
                'province_code' => '132',
                'name' => 'Puente Alto',
                'code' => '13201',
            ],
            [
                'province_code' => '132',
                'name' => 'Pirque',
                'code' => '13202',
            ],
            [
                'province_code' => '132',
                'name' => 'San José de Maipo',
                'code' => '13203',
            ],

            // Provincia de Chacabuco
            [
                'province_code' => '133',
                'name' => 'Colina',
                'code' => '13301',
            ],
            [
                'province_code' => '133',
                'name' => 'Lampa',
                'code' => '13302',
            ],
            [
                'province_code' => '133',
                'name' => 'Tiltil',
                'code' => '13303',
            ],

            // Provincia de Maipo
            [
                'province_code' => '134',
                'name' => 'San Bernardo',
                'code' => '13401',
            ],
            [
                'province_code' => '134',
                'name' => 'Buin',
                'code' => '13402',
            ],
            [
                'province_code' => '134',
                'name' => 'Calera de Tango',
                'code' => '13403',
            ],
            [
                'province_code' => '134',
                'name' => 'Paine',
                'code' => '13404',
            ],

            // Provincia de Melipilla
            [
                'province_code' => '135',
                'name' => 'Melipilla',
                'code' => '13501',
            ],
            [
                'province_code' => '135',
                'name' => 'Alhué',
                'code' => '13502',
            ],
            [
                'province_code' => '135',
                'name' => 'Curacaví',
                'code' => '13503',
            ],
            [
                'province_code' => '135',
                'name' => 'María Pinto',
                'code' => '13504',
            ],
            [
                'province_code' => '135',
                'name' => 'San Pedro',
                'code' => '13505',
            ],

            // Provincia de Talagante
            [
                'province_code' => '136',
                'name' => 'Talagante',
                'code' => '13601',
            ],
            [
                'province_code' => '136',
                'name' => 'El Monte',
                'code' => '13602',
            ],
            [
                'province_code' => '136',
                'name' => 'Isla de Maipo',
                'code' => '13603',
            ],
            [
                'province_code' => '136',
                'name' => 'Padre Hurtado',
                'code' => '13604',
            ],
            [
                'province_code' => '136',
                'name' => 'Peñaflor',
                'code' => '13605',
            ],
            // ============================================================
// REGIÓN DE ARICA Y PARINACOTA
// ============================================================

[
    'province_code' => '151',
    'code' => '15101',
    'name' => 'Arica',
],
[
    'province_code' => '151',
    'code' => '15102',
    'name' => 'Camarones',
],
[
    'province_code' => '152',
    'code' => '15201',
    'name' => 'Putre',
],
[
    'province_code' => '152',
    'code' => '15202',
    'name' => 'General Lagos',
],


// ============================================================
// REGIÓN DE TARAPACÁ
// ============================================================

[
    'province_code' => '011',
    'code' => '01101',
    'name' => 'Iquique',
],
[
    'province_code' => '011',
    'code' => '01107',
    'name' => 'Alto Hospicio',
],
[
    'province_code' => '014',
    'code' => '01401',
    'name' => 'Pozo Almonte',
],
[
    'province_code' => '014',
    'code' => '01402',
    'name' => 'Camiña',
],
[
    'province_code' => '014',
    'code' => '01403',
    'name' => 'Colchane',
],
[
    'province_code' => '014',
    'code' => '01404',
    'name' => 'Huara',
],
[
    'province_code' => '014',
    'code' => '01405',
    'name' => 'Pica',
],


// ============================================================
// REGIÓN DE ANTOFAGASTA
// ============================================================

[
    'province_code' => '021',
    'code' => '02101',
    'name' => 'Antofagasta',
],
[
    'province_code' => '021',
    'code' => '02102',
    'name' => 'Mejillones',
],
[
    'province_code' => '021',
    'code' => '02103',
    'name' => 'Sierra Gorda',
],
[
    'province_code' => '021',
    'code' => '02104',
    'name' => 'Taltal',
],
[
    'province_code' => '022',
    'code' => '02201',
    'name' => 'Calama',
],
[
    'province_code' => '022',
    'code' => '02202',
    'name' => 'Ollagüe',
],
[
    'province_code' => '022',
    'code' => '02203',
    'name' => 'San Pedro de Atacama',
],
[
    'province_code' => '023',
    'code' => '02301',
    'name' => 'Tocopilla',
],
[
    'province_code' => '023',
    'code' => '02302',
    'name' => 'María Elena',
],


// ============================================================
// REGIÓN DE ATACAMA
// ============================================================

[
    'province_code' => '031',
    'code' => '03101',
    'name' => 'Copiapó',
],
[
    'province_code' => '031',
    'code' => '03102',
    'name' => 'Caldera',
],
[
    'province_code' => '031',
    'code' => '03103',
    'name' => 'Tierra Amarilla',
],
[
    'province_code' => '032',
    'code' => '03201',
    'name' => 'Chañaral',
],
[
    'province_code' => '032',
    'code' => '03202',
    'name' => 'Diego de Almagro',
],
[
    'province_code' => '033',
    'code' => '03301',
    'name' => 'Vallenar',
],
[
    'province_code' => '033',
    'code' => '03302',
    'name' => 'Alto del Carmen',
],
[
    'province_code' => '033',
    'code' => '03303',
    'name' => 'Freirina',
],
[
    'province_code' => '033',
    'code' => '03304',
    'name' => 'Huasco',
],
// ============================================================
// REGIÓN DE COQUIMBO — 15 COMUNAS
// ============================================================

// Provincia de Elqui
[
    'province_code' => '041',
    'code' => '04101',
    'name' => 'La Serena',
],
[
    'province_code' => '041',
    'code' => '04102',
    'name' => 'Coquimbo',
],
[
    'province_code' => '041',
    'code' => '04103',
    'name' => 'Andacollo',
],
[
    'province_code' => '041',
    'code' => '04104',
    'name' => 'La Higuera',
],
[
    'province_code' => '041',
    'code' => '04105',
    'name' => 'Paiguano',
],
[
    'province_code' => '041',
    'code' => '04106',
    'name' => 'Vicuña',
],

// Provincia de Choapa
[
    'province_code' => '042',
    'code' => '04201',
    'name' => 'Illapel',
],
[
    'province_code' => '042',
    'code' => '04202',
    'name' => 'Canela',
],
[
    'province_code' => '042',
    'code' => '04203',
    'name' => 'Los Vilos',
],
[
    'province_code' => '042',
    'code' => '04204',
    'name' => 'Salamanca',
],

// Provincia de Limarí
[
    'province_code' => '043',
    'code' => '04301',
    'name' => 'Ovalle',
],
[
    'province_code' => '043',
    'code' => '04302',
    'name' => 'Combarbalá',
],
[
    'province_code' => '043',
    'code' => '04303',
    'name' => 'Monte Patria',
],
[
    'province_code' => '043',
    'code' => '04304',
    'name' => 'Punitaqui',
],
[
    'province_code' => '043',
    'code' => '04305',
    'name' => 'Río Hurtado',
],
// ============================================================
// REGIÓN DE VALPARAÍSO — 38 COMUNAS
// ============================================================

// Provincia de Valparaíso
[
    'province_code' => '051',
    'code' => '05101',
    'name' => 'Valparaíso',
],
[
    'province_code' => '051',
    'code' => '05102',
    'name' => 'Casablanca',
],
[
    'province_code' => '051',
    'code' => '05103',
    'name' => 'Concón',
],
[
    'province_code' => '051',
    'code' => '05104',
    'name' => 'Juan Fernández',
],
[
    'province_code' => '051',
    'code' => '05105',
    'name' => 'Puchuncaví',
],
[
    'province_code' => '051',
    'code' => '05107',
    'name' => 'Quintero',
],
[
    'province_code' => '051',
    'code' => '05109',
    'name' => 'Viña del Mar',
],

// Provincia de Isla de Pascua
[
    'province_code' => '052',
    'code' => '05201',
    'name' => 'Isla de Pascua',
],

// Provincia de Los Andes
[
    'province_code' => '053',
    'code' => '05301',
    'name' => 'Los Andes',
],
[
    'province_code' => '053',
    'code' => '05302',
    'name' => 'Calle Larga',
],
[
    'province_code' => '053',
    'code' => '05303',
    'name' => 'Rinconada',
],
[
    'province_code' => '053',
    'code' => '05304',
    'name' => 'San Esteban',
],

// Provincia de Petorca
[
    'province_code' => '054',
    'code' => '05401',
    'name' => 'La Ligua',
],
[
    'province_code' => '054',
    'code' => '05402',
    'name' => 'Cabildo',
],
[
    'province_code' => '054',
    'code' => '05403',
    'name' => 'Papudo',
],
[
    'province_code' => '054',
    'code' => '05404',
    'name' => 'Petorca',
],
[
    'province_code' => '054',
    'code' => '05405',
    'name' => 'Zapallar',
],

// Provincia de Quillota
[
    'province_code' => '055',
    'code' => '05501',
    'name' => 'Quillota',
],
[
    'province_code' => '055',
    'code' => '05502',
    'name' => 'Calera',
],
[
    'province_code' => '055',
    'code' => '05503',
    'name' => 'Hijuelas',
],
[
    'province_code' => '055',
    'code' => '05504',
    'name' => 'La Cruz',
],
[
    'province_code' => '055',
    'code' => '05505',
    'name' => 'Nogales',
],

// Provincia de San Antonio
[
    'province_code' => '056',
    'code' => '05601',
    'name' => 'San Antonio',
],
[
    'province_code' => '056',
    'code' => '05602',
    'name' => 'Algarrobo',
],
[
    'province_code' => '056',
    'code' => '05603',
    'name' => 'Cartagena',
],
[
    'province_code' => '056',
    'code' => '05604',
    'name' => 'El Quisco',
],
[
    'province_code' => '056',
    'code' => '05605',
    'name' => 'El Tabo',
],
[
    'province_code' => '056',
    'code' => '05606',
    'name' => 'Santo Domingo',
],

// Provincia de San Felipe de Aconcagua
[
    'province_code' => '057',
    'code' => '05701',
    'name' => 'San Felipe',
],
[
    'province_code' => '057',
    'code' => '05702',
    'name' => 'Catemu',
],
[
    'province_code' => '057',
    'code' => '05703',
    'name' => 'Llaillay',
],
[
    'province_code' => '057',
    'code' => '05704',
    'name' => 'Panquehue',
],
[
    'province_code' => '057',
    'code' => '05705',
    'name' => 'Putaendo',
],
[
    'province_code' => '057',
    'code' => '05706',
    'name' => 'Santa María',
],

// Provincia de Marga Marga
[
    'province_code' => '058',
    'code' => '05801',
    'name' => 'Limache',
],
[
    'province_code' => '058',
    'code' => '05802',
    'name' => 'Quilpué',
],
[
    'province_code' => '058',
    'code' => '05803',
    'name' => 'Villa Alemana',
],
[
    'province_code' => '058',
    'code' => '05804',
    'name' => 'Olmué',
],
// ============================================================
// REGIÓN DEL LIBERTADOR GENERAL BERNARDO O'HIGGINS — 33 COMUNAS
// ============================================================

// Provincia de Cachapoal
[
    'province_code' => '061',
    'code' => '06101',
    'name' => 'Rancagua',
],
[
    'province_code' => '061',
    'code' => '06102',
    'name' => 'Codegua',
],
[
    'province_code' => '061',
    'code' => '06103',
    'name' => 'Coinco',
],
[
    'province_code' => '061',
    'code' => '06104',
    'name' => 'Coltauco',
],
[
    'province_code' => '061',
    'code' => '06105',
    'name' => 'Doñihue',
],
[
    'province_code' => '061',
    'code' => '06106',
    'name' => 'Graneros',
],
[
    'province_code' => '061',
    'code' => '06107',
    'name' => 'Las Cabras',
],
[
    'province_code' => '061',
    'code' => '06108',
    'name' => 'Machalí',
],
[
    'province_code' => '061',
    'code' => '06109',
    'name' => 'Malloa',
],
[
    'province_code' => '061',
    'code' => '06110',
    'name' => 'Mostazal',
],
[
    'province_code' => '061',
    'code' => '06111',
    'name' => 'Olivar',
],
[
    'province_code' => '061',
    'code' => '06112',
    'name' => 'Peumo',
],
[
    'province_code' => '061',
    'code' => '06113',
    'name' => 'Pichidegua',
],
[
    'province_code' => '061',
    'code' => '06114',
    'name' => 'Quinta de Tilcoco',
],
[
    'province_code' => '061',
    'code' => '06115',
    'name' => 'Rengo',
],
[
    'province_code' => '061',
    'code' => '06116',
    'name' => 'Requínoa',
],
[
    'province_code' => '061',
    'code' => '06117',
    'name' => 'San Vicente',
],

// Provincia de Cardenal Caro
[
    'province_code' => '062',
    'code' => '06201',
    'name' => 'Pichilemu',
],
[
    'province_code' => '062',
    'code' => '06202',
    'name' => 'La Estrella',
],
[
    'province_code' => '062',
    'code' => '06203',
    'name' => 'Litueche',
],
[
    'province_code' => '062',
    'code' => '06204',
    'name' => 'Marchigüe',
],
[
    'province_code' => '062',
    'code' => '06205',
    'name' => 'Navidad',
],
[
    'province_code' => '062',
    'code' => '06206',
    'name' => 'Paredones',
],

// Provincia de Colchagua
[
    'province_code' => '063',
    'code' => '06301',
    'name' => 'San Fernando',
],
[
    'province_code' => '063',
    'code' => '06302',
    'name' => 'Chépica',
],
[
    'province_code' => '063',
    'code' => '06303',
    'name' => 'Chimbarongo',
],
[
    'province_code' => '063',
    'code' => '06304',
    'name' => 'Lolol',
],
[
    'province_code' => '063',
    'code' => '06305',
    'name' => 'Nancagua',
],
[
    'province_code' => '063',
    'code' => '06306',
    'name' => 'Palmilla',
],
[
    'province_code' => '063',
    'code' => '06307',
    'name' => 'Peralillo',
],
[
    'province_code' => '063',
    'code' => '06308',
    'name' => 'Placilla',
],
[
    'province_code' => '063',
    'code' => '06309',
    'name' => 'Pumanque',
],
[
    'province_code' => '063',
    'code' => '06310',
    'name' => 'Santa Cruz',
],
// ============================================================
// REGIÓN DEL MAULE — 30 COMUNAS
// ============================================================

// Provincia de Talca
[
    'province_code' => '071',
    'code' => '07101',
    'name' => 'Talca',
],
[
    'province_code' => '071',
    'code' => '07102',
    'name' => 'Constitución',
],
[
    'province_code' => '071',
    'code' => '07103',
    'name' => 'Curepto',
],
[
    'province_code' => '071',
    'code' => '07104',
    'name' => 'Empedrado',
],
[
    'province_code' => '071',
    'code' => '07105',
    'name' => 'Maule',
],
[
    'province_code' => '071',
    'code' => '07106',
    'name' => 'Pelarco',
],
[
    'province_code' => '071',
    'code' => '07107',
    'name' => 'Pencahue',
],
[
    'province_code' => '071',
    'code' => '07108',
    'name' => 'Río Claro',
],
[
    'province_code' => '071',
    'code' => '07109',
    'name' => 'San Clemente',
],
[
    'province_code' => '071',
    'code' => '07110',
    'name' => 'San Rafael',
],

// Provincia de Cauquenes
[
    'province_code' => '072',
    'code' => '07201',
    'name' => 'Cauquenes',
],
[
    'province_code' => '072',
    'code' => '07202',
    'name' => 'Chanco',
],
[
    'province_code' => '072',
    'code' => '07203',
    'name' => 'Pelluhue',
],

// Provincia de Curicó
[
    'province_code' => '073',
    'code' => '07301',
    'name' => 'Curicó',
],
[
    'province_code' => '073',
    'code' => '07302',
    'name' => 'Hualañé',
],
[
    'province_code' => '073',
    'code' => '07303',
    'name' => 'Licantén',
],
[
    'province_code' => '073',
    'code' => '07304',
    'name' => 'Molina',
],
[
    'province_code' => '073',
    'code' => '07305',
    'name' => 'Rauco',
],
[
    'province_code' => '073',
    'code' => '07306',
    'name' => 'Romeral',
],
[
    'province_code' => '073',
    'code' => '07307',
    'name' => 'Sagrada Familia',
],
[
    'province_code' => '073',
    'code' => '07308',
    'name' => 'Teno',
],
[
    'province_code' => '073',
    'code' => '07309',
    'name' => 'Vichuquén',
],

// Provincia de Linares
[
    'province_code' => '074',
    'code' => '07401',
    'name' => 'Linares',
],
[
    'province_code' => '074',
    'code' => '07402',
    'name' => 'Colbún',
],
[
    'province_code' => '074',
    'code' => '07403',
    'name' => 'Longaví',
],
[
    'province_code' => '074',
    'code' => '07404',
    'name' => 'Parral',
],
[
    'province_code' => '074',
    'code' => '07405',
    'name' => 'Retiro',
],
[
    'province_code' => '074',
    'code' => '07406',
    'name' => 'San Javier',
],
[
    'province_code' => '074',
    'code' => '07407',
    'name' => 'Villa Alegre',
],
[
    'province_code' => '074',
    'code' => '07408',
    'name' => 'Yerbas Buenas',
],
// ============================================================
// REGIÓN DE ÑUBLE — 21 COMUNAS
// ============================================================

// Provincia de Diguillín
[
    'province_code' => '161',
    'code' => '16101',
    'name' => 'Chillán',
],
[
    'province_code' => '161',
    'code' => '16102',
    'name' => 'Bulnes',
],
[
    'province_code' => '161',
    'code' => '16103',
    'name' => 'Chillán Viejo',
],
[
    'province_code' => '161',
    'code' => '16104',
    'name' => 'El Carmen',
],
[
    'province_code' => '161',
    'code' => '16105',
    'name' => 'Pemuco',
],
[
    'province_code' => '161',
    'code' => '16106',
    'name' => 'Pinto',
],
[
    'province_code' => '161',
    'code' => '16107',
    'name' => 'Quillón',
],
[
    'province_code' => '161',
    'code' => '16108',
    'name' => 'San Ignacio',
],
[
    'province_code' => '161',
    'code' => '16109',
    'name' => 'Yungay',
],

// Provincia de Itata
[
    'province_code' => '162',
    'code' => '16201',
    'name' => 'Quirihue',
],
[
    'province_code' => '162',
    'code' => '16202',
    'name' => 'Cobquecura',
],
[
    'province_code' => '162',
    'code' => '16203',
    'name' => 'Coelemu',
],
[
    'province_code' => '162',
    'code' => '16204',
    'name' => 'Ninhue',
],
[
    'province_code' => '162',
    'code' => '16205',
    'name' => 'Portezuelo',
],
[
    'province_code' => '162',
    'code' => '16206',
    'name' => 'Ránquil',
],
[
    'province_code' => '162',
    'code' => '16207',
    'name' => 'Treguaco',
],

// Provincia de Punilla
[
    'province_code' => '163',
    'code' => '16301',
    'name' => 'San Carlos',
],
[
    'province_code' => '163',
    'code' => '16302',
    'name' => 'Coihueco',
],
[
    'province_code' => '163',
    'code' => '16303',
    'name' => 'Ñiquén',
],
[
    'province_code' => '163',
    'code' => '16304',
    'name' => 'San Fabián',
],
[
    'province_code' => '163',
    'code' => '16305',
    'name' => 'San Nicolás',
],
// ============================================================
// REGIÓN DEL BIOBÍO — 33 COMUNAS
// ============================================================

// Provincia de Concepción
[
    'province_code' => '081',
    'code' => '08101',
    'name' => 'Concepción',
],
[
    'province_code' => '081',
    'code' => '08102',
    'name' => 'Coronel',
],
[
    'province_code' => '081',
    'code' => '08103',
    'name' => 'Chiguayante',
],
[
    'province_code' => '081',
    'code' => '08104',
    'name' => 'Florida',
],
[
    'province_code' => '081',
    'code' => '08105',
    'name' => 'Hualqui',
],
[
    'province_code' => '081',
    'code' => '08106',
    'name' => 'Lota',
],
[
    'province_code' => '081',
    'code' => '08107',
    'name' => 'Penco',
],
[
    'province_code' => '081',
    'code' => '08108',
    'name' => 'San Pedro de la Paz',
],
[
    'province_code' => '081',
    'code' => '08109',
    'name' => 'Santa Juana',
],
[
    'province_code' => '081',
    'code' => '08110',
    'name' => 'Talcahuano',
],
[
    'province_code' => '081',
    'code' => '08111',
    'name' => 'Tomé',
],
[
    'province_code' => '081',
    'code' => '08112',
    'name' => 'Hualpén',
],

// Provincia de Arauco
[
    'province_code' => '082',
    'code' => '08201',
    'name' => 'Lebu',
],
[
    'province_code' => '082',
    'code' => '08202',
    'name' => 'Arauco',
],
[
    'province_code' => '082',
    'code' => '08203',
    'name' => 'Cañete',
],
[
    'province_code' => '082',
    'code' => '08204',
    'name' => 'Contulmo',
],
[
    'province_code' => '082',
    'code' => '08205',
    'name' => 'Curanilahue',
],
[
    'province_code' => '082',
    'code' => '08206',
    'name' => 'Los Álamos',
],
[
    'province_code' => '082',
    'code' => '08207',
    'name' => 'Tirúa',
],

// Provincia de Biobío
[
    'province_code' => '083',
    'code' => '08301',
    'name' => 'Los Ángeles',
],
[
    'province_code' => '083',
    'code' => '08302',
    'name' => 'Antuco',
],
[
    'province_code' => '083',
    'code' => '08303',
    'name' => 'Cabrero',
],
[
    'province_code' => '083',
    'code' => '08304',
    'name' => 'Laja',
],
[
    'province_code' => '083',
    'code' => '08305',
    'name' => 'Mulchén',
],
[
    'province_code' => '083',
    'code' => '08306',
    'name' => 'Nacimiento',
],
[
    'province_code' => '083',
    'code' => '08307',
    'name' => 'Negrete',
],
[
    'province_code' => '083',
    'code' => '08308',
    'name' => 'Quilaco',
],
[
    'province_code' => '083',
    'code' => '08309',
    'name' => 'Quilleco',
],
[
    'province_code' => '083',
    'code' => '08310',
    'name' => 'San Rosendo',
],
[
    'province_code' => '083',
    'code' => '08311',
    'name' => 'Santa Bárbara',
],
[
    'province_code' => '083',
    'code' => '08312',
    'name' => 'Tucapel',
],
[
    'province_code' => '083',
    'code' => '08313',
    'name' => 'Yumbel',
],
[
    'province_code' => '083',
    'code' => '08314',
    'name' => 'Alto Biobío',
],
// ============================================================
// REGIÓN DE LA ARAUCANÍA — 32 COMUNAS
// ============================================================

// Provincia de Cautín
[
    'province_code' => '091',
    'code' => '09101',
    'name' => 'Temuco',
],
[
    'province_code' => '091',
    'code' => '09102',
    'name' => 'Carahue',
],
[
    'province_code' => '091',
    'code' => '09103',
    'name' => 'Cunco',
],
[
    'province_code' => '091',
    'code' => '09104',
    'name' => 'Curarrehue',
],
[
    'province_code' => '091',
    'code' => '09105',
    'name' => 'Freire',
],
[
    'province_code' => '091',
    'code' => '09106',
    'name' => 'Galvarino',
],
[
    'province_code' => '091',
    'code' => '09107',
    'name' => 'Gorbea',
],
[
    'province_code' => '091',
    'code' => '09108',
    'name' => 'Lautaro',
],
[
    'province_code' => '091',
    'code' => '09109',
    'name' => 'Loncoche',
],
[
    'province_code' => '091',
    'code' => '09110',
    'name' => 'Melipeuco',
],
[
    'province_code' => '091',
    'code' => '09111',
    'name' => 'Nueva Imperial',
],
[
    'province_code' => '091',
    'code' => '09112',
    'name' => 'Padre Las Casas',
],
[
    'province_code' => '091',
    'code' => '09113',
    'name' => 'Perquenco',
],
[
    'province_code' => '091',
    'code' => '09114',
    'name' => 'Pitrufquén',
],
[
    'province_code' => '091',
    'code' => '09115',
    'name' => 'Pucón',
],
[
    'province_code' => '091',
    'code' => '09116',
    'name' => 'Saavedra',
],
[
    'province_code' => '091',
    'code' => '09117',
    'name' => 'Teodoro Schmidt',
],
[
    'province_code' => '091',
    'code' => '09118',
    'name' => 'Toltén',
],
[
    'province_code' => '091',
    'code' => '09119',
    'name' => 'Vilcún',
],
[
    'province_code' => '091',
    'code' => '09120',
    'name' => 'Villarrica',
],
[
    'province_code' => '091',
    'code' => '09121',
    'name' => 'Cholchol',
],

// Provincia de Malleco
[
    'province_code' => '092',
    'code' => '09201',
    'name' => 'Angol',
],
[
    'province_code' => '092',
    'code' => '09202',
    'name' => 'Collipulli',
],
[
    'province_code' => '092',
    'code' => '09203',
    'name' => 'Curacautín',
],
[
    'province_code' => '092',
    'code' => '09204',
    'name' => 'Ercilla',
],
[
    'province_code' => '092',
    'code' => '09205',
    'name' => 'Lonquimay',
],
[
    'province_code' => '092',
    'code' => '09206',
    'name' => 'Los Sauces',
],
[
    'province_code' => '092',
    'code' => '09207',
    'name' => 'Lumaco',
],
[
    'province_code' => '092',
    'code' => '09208',
    'name' => 'Purén',
],
[
    'province_code' => '092',
    'code' => '09209',
    'name' => 'Renaico',
],
[
    'province_code' => '092',
    'code' => '09210',
    'name' => 'Traiguén',
],
[
    'province_code' => '092',
    'code' => '09211',
    'name' => 'Victoria',
],
// ============================================================
// REGIÓN DE LOS RÍOS — 12 COMUNAS
// ============================================================

// Provincia de Valdivia
[
    'province_code' => '141',
    'code' => '14101',
    'name' => 'Valdivia',
],
[
    'province_code' => '141',
    'code' => '14102',
    'name' => 'Corral',
],
[
    'province_code' => '141',
    'code' => '14103',
    'name' => 'Lanco',
],
[
    'province_code' => '141',
    'code' => '14104',
    'name' => 'Los Lagos',
],
[
    'province_code' => '141',
    'code' => '14105',
    'name' => 'Máfil',
],
[
    'province_code' => '141',
    'code' => '14106',
    'name' => 'Mariquina',
],
[
    'province_code' => '141',
    'code' => '14107',
    'name' => 'Paillaco',
],
[
    'province_code' => '141',
    'code' => '14108',
    'name' => 'Panguipulli',
],

// Provincia de Ranco
[
    'province_code' => '142',
    'code' => '14201',
    'name' => 'La Unión',
],
[
    'province_code' => '142',
    'code' => '14202',
    'name' => 'Futrono',
],
[
    'province_code' => '142',
    'code' => '14203',
    'name' => 'Lago Ranco',
],
[
    'province_code' => '142',
    'code' => '14204',
    'name' => 'Río Bueno',
],
// ============================================================
// REGIÓN DE LOS LAGOS — 30 COMUNAS
// ============================================================

// Provincia de Llanquihue
[
    'province_code' => '101',
    'code' => '10101',
    'name' => 'Puerto Montt',
],
[
    'province_code' => '101',
    'code' => '10102',
    'name' => 'Calbuco',
],
[
    'province_code' => '101',
    'code' => '10103',
    'name' => 'Cochamó',
],
[
    'province_code' => '101',
    'code' => '10104',
    'name' => 'Fresia',
],
[
    'province_code' => '101',
    'code' => '10105',
    'name' => 'Frutillar',
],
[
    'province_code' => '101',
    'code' => '10106',
    'name' => 'Los Muermos',
],
[
    'province_code' => '101',
    'code' => '10107',
    'name' => 'Llanquihue',
],
[
    'province_code' => '101',
    'code' => '10108',
    'name' => 'Maullín',
],
[
    'province_code' => '101',
    'code' => '10109',
    'name' => 'Puerto Varas',
],

// Provincia de Chiloé
[
    'province_code' => '102',
    'code' => '10201',
    'name' => 'Castro',
],
[
    'province_code' => '102',
    'code' => '10202',
    'name' => 'Ancud',
],
[
    'province_code' => '102',
    'code' => '10203',
    'name' => 'Chonchi',
],
[
    'province_code' => '102',
    'code' => '10204',
    'name' => 'Curaco de Vélez',
],
[
    'province_code' => '102',
    'code' => '10205',
    'name' => 'Dalcahue',
],
[
    'province_code' => '102',
    'code' => '10206',
    'name' => 'Puqueldón',
],
[
    'province_code' => '102',
    'code' => '10207',
    'name' => 'Queilén',
],
[
    'province_code' => '102',
    'code' => '10208',
    'name' => 'Quellón',
],
[
    'province_code' => '102',
    'code' => '10209',
    'name' => 'Quemchi',
],
[
    'province_code' => '102',
    'code' => '10210',
    'name' => 'Quinchao',
],

// Provincia de Osorno
[
    'province_code' => '103',
    'code' => '10301',
    'name' => 'Osorno',
],
[
    'province_code' => '103',
    'code' => '10302',
    'name' => 'Puerto Octay',
],
[
    'province_code' => '103',
    'code' => '10303',
    'name' => 'Purranque',
],
[
    'province_code' => '103',
    'code' => '10304',
    'name' => 'Puyehue',
],
[
    'province_code' => '103',
    'code' => '10305',
    'name' => 'Río Negro',
],
[
    'province_code' => '103',
    'code' => '10306',
    'name' => 'San Juan de la Costa',
],
[
    'province_code' => '103',
    'code' => '10307',
    'name' => 'San Pablo',
],

// Provincia de Palena
[
    'province_code' => '104',
    'code' => '10401',
    'name' => 'Chaitén',
],
[
    'province_code' => '104',
    'code' => '10402',
    'name' => 'Futaleufú',
],
[
    'province_code' => '104',
    'code' => '10403',
    'name' => 'Hualaihué',
],
[
    'province_code' => '104',
    'code' => '10404',
    'name' => 'Palena',
],
// ============================================================
// REGIÓN DE AYSÉN DEL GENERAL CARLOS IBÁÑEZ DEL CAMPO
// — 10 COMUNAS
// ============================================================

// Provincia de Coyhaique
[
    'province_code' => '111',
    'code' => '11101',
    'name' => 'Coyhaique',
],
[
    'province_code' => '111',
    'code' => '11102',
    'name' => 'Lago Verde',
],

// Provincia de Aysén
[
    'province_code' => '112',
    'code' => '11201',
    'name' => 'Aysén',
],
[
    'province_code' => '112',
    'code' => '11202',
    'name' => 'Cisnes',
],
[
    'province_code' => '112',
    'code' => '11203',
    'name' => 'Guaitecas',
],

// Provincia de Capitán Prat
[
    'province_code' => '113',
    'code' => '11301',
    'name' => 'Cochrane',
],
[
    'province_code' => '113',
    'code' => '11302',
    'name' => 'O’Higgins',
],
[
    'province_code' => '113',
    'code' => '11303',
    'name' => 'Tortel',
],

// Provincia de General Carrera
[
    'province_code' => '114',
    'code' => '11401',
    'name' => 'Chile Chico',
],
[
    'province_code' => '114',
    'code' => '11402',
    'name' => 'Río Ibáñez',
],   
// ============================================================
// REGIÓN DE MAGALLANES Y DE LA ANTÁRTICA CHILENA
// — 11 COMUNAS
// ============================================================

// Provincia de Magallanes
[
    'province_code' => '121',
    'code' => '12101',
    'name' => 'Punta Arenas',
],
[
    'province_code' => '121',
    'code' => '12102',
    'name' => 'Laguna Blanca',
],
[
    'province_code' => '121',
    'code' => '12103',
    'name' => 'Río Verde',
],
[
    'province_code' => '121',
    'code' => '12104',
    'name' => 'San Gregorio',
],

// Provincia de Última Esperanza
[
    'province_code' => '122',
    'code' => '12201',
    'name' => 'Natales',
],
[
    'province_code' => '122',
    'code' => '12202',
    'name' => 'Torres del Paine',
],

// Provincia de Tierra del Fuego
[
    'province_code' => '123',
    'code' => '12301',
    'name' => 'Porvenir',
],
[
    'province_code' => '123',
    'code' => '12302',
    'name' => 'Primavera',
],
[
    'province_code' => '123',
    'code' => '12303',
    'name' => 'Timaukel',
],

// Provincia de Antártica Chilena
[
    'province_code' => '124',
    'code' => '12401',
    'name' => 'Cabo de Hornos',
],
[
    'province_code' => '124',
    'code' => '12402',
    'name' => 'Antártica',
],       
        ];

        foreach ($communes as $commune) {

            $province = $provinces->get($commune['province_code']);

            if (! $province) {
                continue;
            }

            Location::updateOrCreate(
                [
                    'country_id' => $chile->id,
                    'location_type_id' => $communeType->id,
                    'parent_id' => $province->id,
                    'code' => $commune['code'],
                ],
                [
                    'name' => $commune['name'],
                    'slug' => Str::slug($commune['name']),
                    'official_code' => $commune['code'],
                    'active' => true,
                ]
            );
        }
    }
}