<?php

use App\Http\Controllers\BahanMakananController;
use App\Http\Controllers\FaktorController;
use App\Http\Controllers\KebutuhanGiziController;
use App\Http\Controllers\PasienController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/all-factor', [FaktorController::class, 'getAllFactor']);

Route::prefix('bahan-makanan')->group(function () {
    Route::get("/", [BahanMakananController::class, 'index']);
    Route::post('/', [BahanMakananController::class, 'store']);
});


Route::prefix('kebutuhan-gizi')->group(function () {
    Route::get("/", [KebutuhanGiziController::class, 'index']);
    Route::post('/', [KebutuhanGiziController::class, 'store']);
});


Route::prefix('pasien')->group(function () {
    Route::get("/", [PasienController::class, 'index']);
    Route::post('/', [PasienController::class, 'store']);
    Route::get('/{id}', [PasienController::class, 'show']);
    Route::put('/{id}', [PasienController::class, 'update']);
    Route::delete('/{id}', [PasienController::class, 'destroy']);
});
