<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes (Path-Based)
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
    Route::middleware('guest:participant')->group(function () {
        Route::get('/login', [\App\Http\Controllers\Auth\ParticipantAuthController::class, 'showLogin'])
            ->name('tenant.login');
        Route::post('/login', [\App\Http\Controllers\Auth\ParticipantAuthController::class, 'login'])
            ->name('tenant.login.submit');
        Route::get('/register/{token}', [\App\Http\Controllers\Auth\ParticipantAuthController::class, 'showRegister'])
            ->name('tenant.register');
        Route::post('/register/{token}', [\App\Http\Controllers\Auth\ParticipantAuthController::class, 'register'])
            ->name('tenant.register.submit');
    });

    Route::middleware('auth:participant')->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Auth\ParticipantAuthController::class, 'logout'])
            ->name('tenant.logout');
    });

    // -------------------------------------------------------
    // Owner Dashboard Routes (within tenant context)
    // -------------------------------------------------------
    Route::middleware('auth:owner')->prefix('/dashboard')->name('tenant.owner.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Owner\DashboardController::class, 'index'])
            ->name('dashboard');

        // Quiz Management
        Route::post('/quizzes/generate', [\App\Http\Controllers\Owner\QuizController::class, 'generate'])
            ->name('quizzes.generate');
        Route::resource('quizzes', \App\Http\Controllers\Owner\QuizController::class);

        // Participant Management
        Route::resource('participants', \App\Http\Controllers\Owner\ParticipantController::class);
        Route::post('/participants/import', [\App\Http\Controllers\Owner\ParticipantController::class, 'import'])
            ->name('participants.import');
        Route::post('/participants/{participant}/reset-password', [\App\Http\Controllers\Owner\ParticipantController::class, 'resetPassword'])
            ->name('participants.reset-password');

        // Reports
        Route::get('/reports', [\App\Http\Controllers\Owner\ReportController::class, 'index'])
            ->name('reports');
        Route::get('/reports/quiz/{quiz}', [\App\Http\Controllers\Owner\ReportController::class, 'quizReport'])
            ->name('reports.quiz');
        Route::get('/reports/export/{type}', [\App\Http\Controllers\Owner\ReportController::class, 'export'])
            ->name('reports.export');

        // Token
        Route::get('/tokens', [\App\Http\Controllers\Owner\TokenController::class, 'index'])
            ->name('tokens');
        Route::post('/tokens/purchase', [\App\Http\Controllers\Owner\TokenController::class, 'purchase'])
            ->name('tokens.purchase');
        Route::get('/tokens/simulate/{order}', [\App\Http\Controllers\Owner\TokenController::class, 'simulatePayment'])
            ->name('tokens.simulate');
        Route::post('/tokens/simulate/{order}', [\App\Http\Controllers\Owner\TokenController::class, 'processSimulation'])
            ->name('tokens.simulate.process');

        // Settings
        Route::get('/settings', [\App\Http\Controllers\Owner\SettingsController::class, 'index'])
            ->name('settings');
        Route::put('/settings', [\App\Http\Controllers\Owner\SettingsController::class, 'update'])
            ->name('settings.update');
    });

    // -------------------------------------------------------
    // Participant Quiz Routes (within tenant context)
    // -------------------------------------------------------
    Route::middleware('auth:participant')->name('tenant.participant.')->group(function () {
        Route::get('/home', [\App\Http\Controllers\Participant\DashboardController::class, 'index'])
            ->name('dashboard');

        // Quiz taking
        Route::get('/quiz/{quiz}', [\App\Http\Controllers\Participant\QuizController::class, 'show'])
            ->name('quiz.show');
        Route::post('/quiz/{quiz}/attempt/start', [\App\Http\Controllers\Participant\QuizController::class, 'startAttempt'])
            ->name('quiz.attempt.start');
        Route::get('/quiz/attempt/{attempt}', [\App\Http\Controllers\Participant\QuizController::class, 'takeQuiz'])
            ->name('quiz.attempt.take');
        Route::post('/quiz/attempt/{attempt}/answer', [\App\Http\Controllers\Participant\QuizController::class, 'saveAnswer'])
            ->name('quiz.attempt.answer');
        Route::post('/quiz/attempt/{attempt}/submit', [\App\Http\Controllers\Participant\QuizController::class, 'submitAttempt'])
            ->name('quiz.attempt.submit');
        Route::get('/quiz/attempt/{attempt}/result', [\App\Http\Controllers\Participant\QuizController::class, 'showResult'])
            ->name('quiz.attempt.result');

        // Timer sync endpoint
        Route::get('/quiz/attempt/{attempt}/remaining', [\App\Http\Controllers\Participant\QuizController::class, 'getRemainingTime'])
            ->name('quiz.attempt.remaining');

        // Quiz history
        Route::get('/history', [\App\Http\Controllers\Participant\QuizController::class, 'history'])
            ->name('history');
    });

    // -------------------------------------------------------
    // Force Submit (no auth — uses sendBeacon with signed token)
    // -------------------------------------------------------
    Route::post('/quiz/attempt/{attempt}/force-submit', [\App\Http\Controllers\Participant\QuizForceSubmitController::class, 'forceSubmit'])
        ->name('tenant.quiz.attempt.force-submit')
        ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
});
