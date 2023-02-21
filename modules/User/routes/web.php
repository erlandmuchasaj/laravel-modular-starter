<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider
| Loaded by user module, within a group which
| contains the "web" middleware group.
| Now create something great!
|
*/

Route::get('/user', function (Request $request) {
    return 'User module configured successfully!';
})->name('user');
