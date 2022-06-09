<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Auth\AuthenticatedSessionController;
use Modules\User\Http\Controllers\Auth\ConfirmablePasswordController;
use Modules\User\Http\Controllers\Auth\EmailVerificationNotificationController;
use Modules\User\Http\Controllers\Auth\EmailVerificationPromptController;
use Modules\User\Http\Controllers\Auth\NewPasswordController;
use Modules\User\Http\Controllers\Auth\PasswordResetLinkController;
use Modules\User\Http\Controllers\Auth\RegisteredUserController;
use Modules\User\Http\Controllers\Auth\VerifyEmailController;

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

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

