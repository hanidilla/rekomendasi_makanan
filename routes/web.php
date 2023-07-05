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
});

Route::get('/hitung-kalori', function () {
    return view('pages.pasien.hitung-kalori');
});

Route::get('/pasien', function () {
    return view("pages.admin.pasien");
});

Route::get("/nv-bayes", function () {
    return view('pages.pasien.naive-bayes');
});
Route::get("/faktor-aktivitas", function () {
    return view('pages.admin.faktor-aktivitas');
});
Route::get("/faktor-stress", function () {
    return view('pages.admin.faktor-stress');
});
