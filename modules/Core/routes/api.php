<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user-core-api', function (Request $request) {
    return $request->user();
})->name('core.user');
