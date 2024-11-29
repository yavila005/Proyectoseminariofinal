<?php

use App\Http\Controllers\AlumnoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
//ruta de tipo src
Route::resource('alumnos', AlumnoController::class);
