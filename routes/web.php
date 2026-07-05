<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Central)
|--------------------------------------------------------------------------
| These routes are for the central application:
| - Landing page
| - SuperAdmin panel
| - Owner authentication (login/register)
| - Payment webhooks
*/

// Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('landing');

// -------------------------------------------------------
// SuperAdmin Routes
// -------------------------------------------------------
Route::prefix('superadmin')->name('superadmin.')->group(function () {
    // Guest routes
    Route::middleware('guest:web')->group(function () {
        Route::get('/login', [\App\Http\Controllers\Auth\SuperAdminAuthController::class, 'showLogin'])
            ->name('login');
        Route::post('/login', [\App\Http\Controllers\Auth\SuperAdminAuthController::class, 'login'])
            ->name('login.submit');
    });

    // Authenticated SuperAdmin routes
    Route::middleware('auth:web')->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Auth\SuperAdminAuthController::class, 'logout'])
            ->name('logout');
        Route::get('/dashboard', [\App\Http\Controllers\SuperAdmin\DashboardController::class, 'index'])
            ->name('dashboard');
    });
});

// -------------------------------------------------------
// Owner Auth Routes (Central - not tenant-scoped)
// -------------------------------------------------------
Route::prefix('owner')->name('owner.')->group(function () {
    Route::middleware('guest:owner')->group(function () {
        Route::get('/login', [\App\Http\Controllers\Auth\OwnerAuthController::class, 'showLogin'])
            ->name('login');
        Route::post('/login', [\App\Http\Controllers\Auth\OwnerAuthController::class, 'login'])
            ->name('login.submit');
        Route::get('/register', [\App\Http\Controllers\Auth\OwnerAuthController::class, 'showRegister'])
            ->name('register');
        Route::post('/register', [\App\Http\Controllers\Auth\OwnerAuthController::class, 'register'])
            ->name('register.submit');
    });

    Route::middleware('auth:owner')->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Auth\OwnerAuthController::class, 'logout'])
            ->name('logout');
    });
});

// -------------------------------------------------------
// Payment Webhooks (no auth, verified by signature)
// -------------------------------------------------------
Route::prefix('webhook')->name('webhook.')->group(function () {
    Route::post('/midtrans', [\App\Http\Controllers\Webhook\PaymentWebhookController::class, 'midtrans'])->name('midtrans');
    Route::post('/xendit', [\App\Http\Controllers\Webhook\PaymentWebhookController::class, 'xendit'])->name('xendit');
    Route::post('/ipaymu', [\App\Http\Controllers\Webhook\PaymentWebhookController::class, 'ipaymu'])->name('ipaymu');
    Route::post('/doku', [\App\Http\Controllers\Webhook\PaymentWebhookController::class, 'doku'])->name('doku');
    Route::post('/duitku', [\App\Http\Controllers\Webhook\PaymentWebhookController::class, 'duitku'])->name('duitku');
});
