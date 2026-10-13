<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\TerritorialZones\Index as TerritorialZonesIndex;
use App\Livewire\Properties\Index as PropertiesIndex;
use App\Livewire\Roads\Index as RoadsIndex;
use App\Livewire\RoadSegments\Index as RoadSegmentsIndex;



Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::get('/territorial-zones', TerritorialZonesIndex::class)
        ->name('territorial-zones.index');

    Route::get('/properties', PropertiesIndex::class)
        ->name('properties.index');

    Route::get('/roads', RoadsIndex::class)
        ->name('roads.index');

    Route::get('/road-segments', RoadSegmentsIndex::class)
        ->name('road-segments.index');


});


require __DIR__.'/settings.php';