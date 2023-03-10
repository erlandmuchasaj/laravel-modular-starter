<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes [test]
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your module. These
| routes are loaded by the test within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/test', function (Request $request) {
    return 'Test module configured successfully!';
})->name('test');
