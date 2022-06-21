<?php

use Illuminate\Support\Facades\Route;

use Modules\Core\Http\Controllers\LanguageController;


Route::get('/home', function () {
    return view('core::dashboard');
})->middleware(['auth'])->name('dashboard');


Route::get('/lang/{lang}', [LanguageController::class, 'swap'])
    ->name('lang.swap')
    ->where('lang', '[a-z]+');




