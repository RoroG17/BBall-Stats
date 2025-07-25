<?php

use App\Http\Controllers\EquipeResource;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('/api/equipes', EquipeResource::class);