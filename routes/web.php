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
    $packages = \App\Models\Central\TokenPackage::active()->ordered()->get();
    return view('welcome', compact('packages'));
})->name('landing');

// -------------------------------------------------------
// Unified Central Authentication Routes
// -------------------------------------------------------
Route::get('/login', [\App\Http\Controllers\Auth\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login'])->name('login.submit');

Route::get('/register', [\App\Http\Controllers\Auth\AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [\App\Http\Controllers\Auth\AuthController::class, 'register'])->name('register.submit');

// Legacy/Aliased Auth Redirects
Route::get('/superadmin/login', fn() => redirect()->route('login'))->name('superadmin.login');
Route::post('/superadmin/login', [\App\Http\Controllers\Auth\AuthController::class, 'login'])->name('superadmin.login.submit');
Route::get('/owner/login', fn() => redirect()->route('login'))->name('owner.login');
Route::post('/owner/login', [\App\Http\Controllers\Auth\AuthController::class, 'login'])->name('owner.login.submit');
Route::get('/owner/register', fn() => redirect()->route('register'))->name('owner.register');
Route::post('/owner/register', [\App\Http\Controllers\Auth\AuthController::class, 'register'])->name('owner.register.submit');

Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');

// -------------------------------------------------------
// SuperAdmin Routes (Modules Architecture)
// -------------------------------------------------------
Route::prefix('superadmin')->name('superadmin.')->group(function () {
    // Authenticated SuperAdmin routes
    Route::middleware('auth:web')->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [\App\Http\Controllers\Modules\SuperAdmin\DashboardController::class, 'index'])
            ->name('dashboard');

        // Profile Editing
        Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

        // Owner Management
        Route::resource('owners', \App\Http\Controllers\Modules\SuperAdmin\OwnerController::class);
        Route::post('/owners/{owner}/toggle-unlimited', [\App\Http\Controllers\Modules\SuperAdmin\OwnerController::class, 'toggleUnlimited'])
            ->name('owners.toggle-unlimited');
        Route::post('/owners/{owner}/topup', [\App\Http\Controllers\Modules\SuperAdmin\OwnerController::class, 'topup'])
            ->name('owners.topup');
        Route::post('/owners/{owner}/reset-password', [\App\Http\Controllers\Modules\SuperAdmin\OwnerController::class, 'resetPassword'])
            ->name('owners.reset-password');
        Route::post('/owners/{owner}/impersonate', [\App\Http\Controllers\Modules\SuperAdmin\OwnerController::class, 'impersonate'])
            ->name('owners.impersonate');

        // Token Package Catalog Management
        Route::resource('token-packages', \App\Http\Controllers\Modules\SuperAdmin\TokenPackageController::class);
        Route::post('/token-packages/{token_package}/toggle-active', [\App\Http\Controllers\Modules\SuperAdmin\TokenPackageController::class, 'toggleActive'])
            ->name('token-packages.toggle-active');

        // LLM Provider Settings
        Route::get('/llm', [\App\Http\Controllers\Modules\SuperAdmin\LlmProviderController::class, 'index'])->name('llm.index');
        Route::post('/llm', [\App\Http\Controllers\Modules\SuperAdmin\LlmProviderController::class, 'store'])->name('llm.store');
        Route::put('/llm/{llmProvider}', [\App\Http\Controllers\Modules\SuperAdmin\LlmProviderController::class, 'update'])->name('llm.update');
        Route::post('/llm/{llmProvider}/toggle-active', [\App\Http\Controllers\Modules\SuperAdmin\LlmProviderController::class, 'toggleActive'])->name('llm.toggle-active');
        Route::post('/llm/test-connection', [\App\Http\Controllers\Modules\SuperAdmin\LlmProviderController::class, 'testConnection'])->name('llm.test-connection');

        // Gateway Settings (Payment, Email, WhatsApp)
        Route::get('/gateways', [\App\Http\Controllers\Modules\SuperAdmin\GatewaySettingController::class, 'index'])->name('gateways.index');
        Route::put('/gateways/payment/{id}', [\App\Http\Controllers\Modules\SuperAdmin\GatewaySettingController::class, 'updatePayment'])->name('gateways.payment.update');
        Route::put('/gateways/email', [\App\Http\Controllers\Modules\SuperAdmin\GatewaySettingController::class, 'updateEmail'])->name('gateways.email.update');
        Route::put('/gateways/whatsapp', [\App\Http\Controllers\Modules\SuperAdmin\GatewaySettingController::class, 'updateWhatsApp'])->name('gateways.whatsapp.update');

        // System Settings & Activity Logs
        Route::get('/logs', [\App\Http\Controllers\Modules\SuperAdmin\SystemSettingController::class, 'logs'])->name('logs.index');
        Route::get('/settings', [\App\Http\Controllers\Modules\SuperAdmin\SystemSettingController::class, 'settings'])->name('settings.index');
        Route::put('/settings', [\App\Http\Controllers\Modules\SuperAdmin\SystemSettingController::class, 'updateSettings'])->name('settings.update');
    });
});

// -------------------------------------------------------
// Webhook Routes (Public / Unauthenticated)
// -------------------------------------------------------
Route::prefix('webhooks')->name('webhooks.')->group(function () {
    Route::post('/midtrans', [\App\Http\Controllers\WebhookController::class, 'midtrans'])->name('midtrans');
    Route::post('/xendit', [\App\Http\Controllers\WebhookController::class, 'xendit'])->name('xendit');
    Route::post('/ipaymu', [\App\Http\Controllers\WebhookController::class, 'ipaymu'])->name('ipaymu');
    Route::post('/doku', [\App\Http\Controllers\WebhookController::class, 'doku'])->name('doku');
    Route::post('/duitku', [\App\Http\Controllers\WebhookController::class, 'duitku'])->name('duitku');
});
