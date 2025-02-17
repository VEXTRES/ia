<?php

use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\IA;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/ia');
});

Route::get('/alumnos',[AlumnoController::class,'index'])->name('alumnos');
Route::post('/alumnos',[AlumnoController::class,'store'])->name('alumnos.store');
Route::delete('/alumnos/{id}',[AlumnoController::class,'destroy'])->name('alumnos.destroy');

Route::get('/ia', [IA::class, 'index'])->name('ia.index');  // Cambiado de /user a /ia
Route::post('/ia', [IA::class, 'preguntarIA'])->name('preguntarIA');

