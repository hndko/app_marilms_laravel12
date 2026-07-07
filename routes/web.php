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
    $packages = \App\Models\Central\TokenPackage::where('is_active', true)->orderBy('price')->get();
    return view('welcome', compact('packages'));
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

        // Owner Management
        Route::resource('owners', \App\Http\Controllers\SuperAdmin\OwnerController::class);
        Route::post('/owners/{owner}/toggle-unlimited', [\App\Http\Controllers\SuperAdmin\OwnerController::class, 'toggleUnlimited'])
            ->name('owners.toggle-unlimited');
        Route::post('/owners/{owner}/topup', [\App\Http\Controllers\SuperAdmin\OwnerController::class, 'topup'])
            ->name('owners.topup');
        Route::post('/owners/{owner}/reset-password', [\App\Http\Controllers\SuperAdmin\OwnerController::class, 'resetPassword'])
            ->name('owners.reset-password');
        Route::post('/owners/{owner}/impersonate', [\App\Http\Controllers\SuperAdmin\OwnerController::class, 'impersonate'])
            ->name('owners.impersonate');

        // Token Packages
        Route::resource('token-packages', \App\Http\Controllers\SuperAdmin\TokenPackageController::class);

        // System Settings
        Route::get('/settings', [\App\Http\Controllers\SuperAdmin\SettingsController::class, 'index'])
            ->name('settings.index');
        Route::put('/settings', [\App\Http\Controllers\SuperAdmin\SettingsController::class, 'update'])
            ->name('settings.update');

        // LLM Provider
        Route::resource('llm', \App\Http\Controllers\SuperAdmin\LlmProviderController::class);
        Route::post('/llm/{llm}/test', [\App\Http\Controllers\SuperAdmin\LlmProviderController::class, 'testConnection'])
            ->name('llm.test');

        // Gateways (Payment, Email, WhatsApp)
        Route::get('/gateways', [\App\Http\Controllers\SuperAdmin\GatewayController::class, 'index'])
            ->name('gateways.index');
        Route::put('/gateways/payment/{gateway}', [\App\Http\Controllers\SuperAdmin\GatewayController::class, 'updatePayment'])
            ->name('gateways.payment.update');
        Route::put('/gateways/email', [\App\Http\Controllers\SuperAdmin\GatewayController::class, 'updateEmail'])
            ->name('gateways.email.update');
        Route::put('/gateways/whatsapp/{id}', [\App\Http\Controllers\SuperAdmin\GatewayController::class, 'updateWhatsapp'])
            ->name('gateways.whatsapp.update');
        Route::post('/gateways/email/test', [\App\Http\Controllers\SuperAdmin\GatewayController::class, 'testEmail'])
            ->name('gateways.email.test');
        Route::post('/gateways/whatsapp/{id}/test', [\App\Http\Controllers\SuperAdmin\GatewayController::class, 'testWhatsapp'])
            ->name('gateways.whatsapp.test');

        // Activity Logs
        Route::get('/logs', [\App\Http\Controllers\SuperAdmin\LogController::class, 'index'])
            ->name('logs.index');
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
Route::prefix('webhook')
    ->name('webhook.')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->group(function () {
        Route::post('/midtrans', [\App\Http\Controllers\Webhook\PaymentWebhookController::class, 'midtrans'])->name('midtrans');
        Route::post('/xendit', [\App\Http\Controllers\Webhook\PaymentWebhookController::class, 'xendit'])->name('xendit');
        Route::post('/ipaymu', [\App\Http\Controllers\Webhook\PaymentWebhookController::class, 'ipaymu'])->name('ipaymu');
        Route::post('/doku', [\App\Http\Controllers\Webhook\PaymentWebhookController::class, 'doku'])->name('doku');
        Route::post('/duitku', [\App\Http\Controllers\Webhook\PaymentWebhookController::class, 'duitku'])->name('duitku');
    });
