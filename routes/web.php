<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Admin SPA routes - all admin routes should return the SPA view
Route::get('/secure/administrator/{any}', function () {
    return view('admin');
})->where('any', '.*');

Route::get('/secure/administrator', function () {
    return view('admin');
});
