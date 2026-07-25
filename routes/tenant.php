<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes (Path-Based - Modules Architecture)
|--------------------------------------------------------------------------
| These routes are tenant-aware. The tenant is resolved from the URL path:
| domain.com/{tenant_slug}/...
|
| All routes here are automatically scoped to the active tenant's database.
*/

Route::middleware([
    'web',
    InitializeTenancyByPath::class,
    \App\Http\Middleware\SetTenantUrlDefaults::class,
])->prefix('/{tenant}')->group(function () {

    // -------------------------------------------------------
    // Participant Auth Routes (within tenant)
    // -------------------------------------------------------
    Route::get('/login', [\App\Http\Controllers\Auth\AuthController::class, 'showLogin'])
        ->name('tenant.login');
    Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login'])
        ->name('tenant.login.submit');
    Route::get('/register/{token}', [\App\Http\Controllers\Auth\AuthController::class, 'showRegister'])
        ->name('tenant.register');
    Route::post('/register/{token}', [\App\Http\Controllers\Auth\AuthController::class, 'register'])
        ->name('tenant.register.submit');

    Route::middleware('auth:participant')->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout'])
            ->name('tenant.logout');
    });

    // -------------------------------------------------------
    // Owner Dashboard Routes (within tenant context)
    // -------------------------------------------------------
    Route::middleware('auth:owner')->prefix('/dashboard')->name('tenant.owner.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Modules\Owner\DashboardController::class, 'index'])
            ->name('dashboard');

        // Profile Editing for Owner
        Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])
            ->name('profile.update');

        // Quiz Management
        Route::post('/quizzes/generate', [\App\Http\Controllers\Modules\Owner\QuizController::class, 'generate'])
            ->name('quizzes.generate');
        Route::resource('quizzes', \App\Http\Controllers\Modules\Owner\QuizController::class);

        // Participant Management
        Route::resource('participants', \App\Http\Controllers\Modules\Owner\ParticipantController::class);
        Route::post('/participants/import', [\App\Http\Controllers\Modules\Owner\ParticipantController::class, 'import'])
            ->name('participants.import');
        Route::post('/participants/{participant}/reset-password', [\App\Http\Controllers\Modules\Owner\ParticipantController::class, 'resetPassword'])
            ->name('participants.reset-password');

        // Reports
        Route::get('/reports', [\App\Http\Controllers\Modules\Owner\ReportController::class, 'index'])
            ->name('reports');
        Route::get('/reports/quiz/{quiz}', [\App\Http\Controllers\Modules\Owner\ReportController::class, 'quizReport'])
            ->name('reports.quiz');
        Route::get('/reports/export/{type}', [\App\Http\Controllers\Modules\Owner\ReportController::class, 'export'])
            ->name('reports.export');

        // Token
        Route::get('/tokens', [\App\Http\Controllers\Modules\Owner\TokenController::class, 'index'])
            ->name('tokens');
        Route::post('/tokens/purchase', [\App\Http\Controllers\Modules\Owner\TokenController::class, 'purchase'])
            ->name('tokens.purchase');
        Route::get('/tokens/simulate/{order}', [\App\Http\Controllers\Modules\Owner\TokenController::class, 'simulatePayment'])
            ->name('tokens.simulate');
        Route::post('/tokens/simulate/{order}', [\App\Http\Controllers\Modules\Owner\TokenController::class, 'processSimulation'])
            ->name('tokens.simulate.process');

        // Settings & WhatsApp
        Route::get('/settings', [\App\Http\Controllers\Modules\Owner\SettingsController::class, 'index'])
            ->name('settings');
        Route::put('/settings', [\App\Http\Controllers\Modules\Owner\SettingsController::class, 'update'])
            ->name('settings.update');
        Route::get('/whatsapp', [\App\Http\Controllers\Modules\Owner\SettingsController::class, 'index'])
            ->name('whatsapp');
    });

    // -------------------------------------------------------
    // Participant Routes (within tenant context)
    // -------------------------------------------------------
    Route::middleware('auth:participant')->name('tenant.participant.')->group(function () {
        Route::get('/participant/dashboard', [\App\Http\Controllers\Modules\Participant\DashboardController::class, 'index'])
            ->name('dashboard');

        // Profile Editing for Participant
        Route::get('/participant/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::put('/participant/profile', [\App\Http\Controllers\ProfileController::class, 'update'])
            ->name('profile.update');

        // Quiz Execution Engine
        Route::get('/quiz/{quiz}', [\App\Http\Controllers\Modules\Participant\QuizExecutionController::class, 'show'])
            ->name('quiz.show');
        Route::post('/quiz/{quiz}/start', [\App\Http\Controllers\Modules\Participant\QuizExecutionController::class, 'start'])
            ->name('quiz.start');
        Route::get('/attempt/{attempt}', [\App\Http\Controllers\Modules\Participant\QuizExecutionController::class, 'attempt'])
            ->name('attempt.show');
        Route::post('/attempt/{attempt}/answer', [\App\Http\Controllers\Modules\Participant\QuizExecutionController::class, 'saveAnswer'])
            ->name('attempt.answer');
        Route::post('/attempt/{attempt}/submit', [\App\Http\Controllers\Modules\Participant\QuizExecutionController::class, 'submit'])
            ->name('attempt.submit');
        Route::post('/attempt/{attempt}/flag', [\App\Http\Controllers\Modules\Participant\QuizExecutionController::class, 'flagCheat'])
            ->name('attempt.flag');
        Route::get('/attempt/{attempt}/result', [\App\Http\Controllers\Modules\Participant\QuizExecutionController::class, 'result'])
            ->name('attempt.result');

        // History
        Route::get('/history', [\App\Http\Controllers\Modules\Participant\QuizExecutionController::class, 'history'])
            ->name('history');
    });
});
