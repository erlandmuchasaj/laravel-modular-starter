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

    // if (version_compare(app()->version(), '9.0.0') >= 0) {
    //     echo 'I am at least 9.0.0, my version: ' . app()->version() . "\n";
    // }
    // dd(app()->version(), app()->langPath('vendor/core'), resource_path("lang/vendor/core"));

    return view('welcome');
});

Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});
