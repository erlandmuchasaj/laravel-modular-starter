<?php

use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Routing\Router;

Route::get('/core-test', function () {
    //    dd('HERE', locale());
    return view('welcome');
});

Route::group(['as' => 'users.', 'prefix' => '/users'], function (Router $router) {
    Route::get('/core-test/{token}', [NewPasswordController::class, 'create'])
        ->name('test.reset');
});

