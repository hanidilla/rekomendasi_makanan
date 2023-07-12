<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('pages.admin.makanan');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/hitung-kalori', function () {
    return view('pages.pasien.hitung-kalori');
})->middleware("auth");

Route::get('/pasien', function () {
    return view("pages.admin.pasien");
})->middleware("auth");

// Route::get("/nv-bayes", function () {
//     return view('pages.pasien.naive-bayes');
// });
Route::post('/login_user_gizi', [App\Http\Controllers\Auth\LoginController::class, 'loginUser']);
Route::get('/nv-bayes', [App\Http\Controllers\NaiveBayesController::class, 'getSaran'])->middleware("auth");

Route::get("/faktor-aktivitas", function () {
    return view('pages.admin.faktor-aktivitas');
})->middleware("auth");

Route::get("/faktor-stress", function () {
    return view('pages.admin.faktor-stress');
})->middleware("auth");

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
