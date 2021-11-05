<?php

Route::get('/user-sac', function (Request $request) {
    return $request->user();
})->name('test.sac');
