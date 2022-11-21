<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

//Update User Details
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/editarPassword', [App\Http\Controllers\HomeController::class, 'editarPassword'])->name('editarPassword');
Route::post('/edituser', [App\Http\Controllers\HomeController::class, 'edituser'])->name('edituser');

Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

//clubes details
Route::post('/club', [App\Http\Controllers\HomeController::class, 'updateClub'])->name('updateClub');
Route::post('/clubeditar', [App\Http\Controllers\HomeController::class, 'editarClub'])->name('editarClub');
Route::post('/clubeliminar', [App\Http\Controllers\HomeController::class, 'deleteClub'])->name('deleteClub');

//inscripciones
Route::post('/inscribirse', [App\Http\Controllers\HomeController::class, 'inscribirse'])->name('inscribirse');

//eventos
Route::post('/creaEvento', [App\Http\Controllers\HomeController::class, 'creaEvento'])->name('creaEvento');

//confi_eventos
Route::post('/asignarCoordinador', [App\Http\Controllers\HomeController::class, 'asignarCoordinador'])->name('asignarCoordinador');

//autoridades
Route::post('/agregarAutoridad', [App\Http\Controllers\HomeController::class, 'agregarAutoridad'])->name('agregarAutoridad');
Route::get('/eliminarAutoridad/{id}', [App\Http\Controllers\HomeController::class, 'eliminarAutoridad'])->name('eliminarAutoridad');

//Archivos
Route::post('/subirArchivo', [App\Http\Controllers\HomeController::class, 'subirArchivo'])->name('subirArchivo');
Route::get('/eliminarArchivo/{id}', [App\Http\Controllers\HomeController::class, 'eliminarArchivo'])->name('eliminarArchivo');
Route::get('/toogleArchivo/{id}', [App\Http\Controllers\HomeController::class, 'toogleArchivo'])->name('toogleArchivo');

//Evidencias
Route::post('/subirEvidencia', [App\Http\Controllers\HomeController::class, 'subirEvidencia'])->name('subirEvidencia');
Route::get('/eliminarEvidencia/{id}', [App\Http\Controllers\HomeController::class, 'eliminarEvidencia'])->name('eliminarEvidencia');
