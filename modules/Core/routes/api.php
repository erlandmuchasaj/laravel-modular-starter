<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user-sac', function (Request $request) {
    return $request->user();
})->name('test.sac');
