<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes [test]
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your module.
| These routes are loaded by the RouteServiceProvider
| of test module, within a group which
| contains the "web" middleware group.
| Now create something great!
|
*/

Route::get('/test', function (Request $request) {
    return 'Test module configured successfully!';
})->name('test');
