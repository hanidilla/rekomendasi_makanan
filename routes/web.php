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
})->middleware('web:admin');

Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('web:admin');

Route::get('/hitung-kalori', function () {
    return view('pages.pasien.hitung-kalori');
})->middleware('web:admin');

Route::get('/pasien', function () {
    return view("pages.admin.pasien");
})->middleware('web:admin');

Route::get("/nv-bayes", function () {
    return view('pages.pasien.naive-bayes');
})->middleware('web:admin');

Route::get("/faktor-aktivitas", function () {
    return view('pages.admin.faktor-aktivitas');
})->middleware('web:admin');

Route::get("/faktor-stress", function () {
    return view('pages.admin.faktor-stress');
})->middleware('web:admin');
