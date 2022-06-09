<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
|
| Here is where you can register user module routes for your application. These
| routes are loaded by the RouteServiceProvider.
| Now create something great!
|
*/

Route::get('/user', function (Request $request) {
    return 'User module configured successfully!';
})->name('user');
