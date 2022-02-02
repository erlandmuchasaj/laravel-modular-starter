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
    // $input = ['date_from' => '29/01/2022 03:55:00'];
    // $tz = \Carbon\CarbonTimeZone::createFromMinuteOffset('-150');
    // // $date = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $input['date_from'], $tz);
    // $date = \Carbon\Carbon::parse( $input['date_from'], $tz);
    // dd($input, $tz, $date,  $date->setTimezone(config('app.timezone'))->format('Y, m, d H:i:s'));

    // dd('HERE', locale(), app()->basePath('stubs'));
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});

require __DIR__.'/auth.php';
