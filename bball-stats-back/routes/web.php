<?php

use App\Http\Controllers\JoueurResource;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('/api/joueurs', JoueurResource::class);
