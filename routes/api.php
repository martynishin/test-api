<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\TokenController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/tokens', [TokenController::class, 'store']);

Route::controller(EventController::class)
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/list', 'index');
        Route::get('/{id}', 'show')->whereNumber('id');
        Route::put('/{id}', 'replace')->whereNumber('id')->name('replace');
        Route::patch('/{id}', 'update')->whereNumber('id')->name('update');
        Route::delete('/{id}', 'destroy')->whereNumber('id');
    });
