<?php

use App\Http\Controllers\BahanMakananController;
use App\Http\Controllers\FaktorController;
use App\Http\Controllers\KebutuhanGiziController;
use App\Http\Controllers\NaiveBayesController;
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
    Route::get("/{id}", [BahanMakananController::class, 'show']);
    Route::put("/{id}", [BahanMakananController::class, 'update']);
    Route::delete("/{id}", [BahanMakananController::class, 'destroy']);
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


Route::prefix("faktor")->group(function () {
    Route::prefix("aktifitas")->group(function () {
        Route::get("/", [FaktorController::class, 'getAktivitas']);
        Route::post("/", [FaktorController::class, 'storeAktivitas']);
        Route::get("/{id}", [FaktorController::class, 'getAktivitasById']);
        Route::put("/{id}", [FaktorController::class, 'updateAktivitas']);
        Route::delete("/{id}", [FaktorController::class, 'deleteAktivitas']);
    });
    Route::prefix("stress")->group(function () {
        Route::get("/", [FaktorController::class, 'getStress']);
        Route::post("/", [FaktorController::class, 'storeStress']);
        Route::get("/{id}", [FaktorController::class, 'getStressById']);
        Route::put("/{id}", [FaktorController::class, 'updateStress']);
        Route::delete("/{id}", [FaktorController::class, 'deleteStress']);
    });
});
Route::prefix("nv-bayes")->group(function () {
    Route::get("/", [NaiveBayesController::class, 'naiveBayes']);
    Route::get("/nvb", [NaiveBayesController::class, 'nvBayes']);
    Route::get("/get-data", [NaiveBayesController::class, 'getSaran']);
    Route::get("/probability", [NaiveBayesController::class, 'probability']);
    Route::get("/mean", [NaiveBayesController::class, 'mean']);
    Route::get("/stdev", [NaiveBayesController::class, 'deviasi']);
    Route::get("/normal", [NaiveBayesController::class, 'normalDist']);
});
