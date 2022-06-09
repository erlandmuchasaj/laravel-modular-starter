<?php

use Illuminate\Support\Facades\Route;

use Modules\Core\Http\Controllers\LanguageController;

Route::get('/core-test', function () {
    return view('welcome');
})->name('core.test');

Route::get('/lang/{lang}', [LanguageController::class, 'swap'])
    ->name('lang.swap')
    ->where('lang', '[a-z]+');




