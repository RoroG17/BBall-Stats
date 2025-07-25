<?php

use App\Http\Controllers\EquipeResource;
use App\Http\Controllers\JouerResource;
use App\Http\Controllers\JoueurResource;
use App\Http\Controllers\MatchResource;
use App\Http\Controllers\SaisonResource;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('/api/equipes', EquipeResource::class);
Route::resource('/api/joueurs', JoueurResource::class);
Route::resource('api/saisons', SaisonResource::class);
Route::resource('api/matchs', MatchResource::class);
Route::resource('api/stats', JouerResource::class);