<?php

use Modules\Core\Http\Controllers\LanguageController;

Route::get('/core-test', function () {
    //    dd('HERE', locale());
    return view('welcome');
});

Route::get('/lang/{lang}', [LanguageController::class, 'swap'])
    ->name('lang.swap')
    ->where('lang', '[a-z]+');




