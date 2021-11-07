<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [\App\Http\Controllers\PortfolioController::class, 'create'])
    ->name('portfolio.view');

Route::get('/dashboard', function () {
    return redirect('/dashboard/overview');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard/apply', [\App\Http\Controllers\ApplicationController::class, 'createApplicationForm'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.apply');

Route::post('/dashboard/apply', [\App\Http\Controllers\ApplicationController::class, 'apply'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.apply.save');

Route::get('/t/email/{key}', [\App\Http\Controllers\ApplicationController::class, 'trackEmail'])
    ->name('portfolio.track.email');

Route::get('/dashboard/overview', [\App\Http\Controllers\OverviewController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.overview');

Route::get('/dashboard/templates', [\App\Http\Controllers\TemplateController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.templates');

Route::post('/dashboard/templates/', [\App\Http\Controllers\TemplateController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.templates.store');

Route::post('/dashboard/templates/update', [\App\Http\Controllers\TemplateController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.templates.update');

Route::post('/dashboard/templates/delete', [\App\Http\Controllers\TemplateController::class, 'delete'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.templates.delete');

Route::get('/dashboard/templates/download/{templateId}', [\App\Http\Controllers\TemplateController::class, 'download'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.templates.download');

Route::get('/dashboard/templates/export/{templateId}', [\App\Http\Controllers\TemplateController::class, 'export'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.templates.export');

Route::get('/dashboard/respondents', [\App\Http\Controllers\RespondentController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.respondents');

Route::post('/dashboard/respondents/toggle', [\App\Http\Controllers\RespondentController::class, 'toggle'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.respondents.toggle');

Route::post('/dashboard/respondents/delete', [\App\Http\Controllers\RespondentController::class, 'delete'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.respondents.delete');

Route::post('/dashboard/respondents/store', [\App\Http\Controllers\RespondentController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.respondents.store');

Route::get('/dashboard/applications', [\App\Http\Controllers\ApplicationController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.applications');

Route::post('/dashboard/applications/delete', [\App\Http\Controllers\ApplicationController::class, 'delete'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.applications.delete');

Route::post('/dashboard/applications/resend', [\App\Http\Controllers\ApplicationController::class, 'resend'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.applications.resend');

Route::get('/dashboard/applications/statistics/{applicationId}', [\App\Http\Controllers\ApplicationController::class, 'statistics'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.applications.statistics');

Route::get('/dashboard/settings', [\App\Http\Controllers\SettingsController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.settings');

Route::post('/dashboard/settings', [\App\Http\Controllers\SettingsController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.settings.store');

Route::get('/dashboard/account', [\App\Http\Controllers\AccountController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.account');

Route::post('/dashboard/account', [\App\Http\Controllers\AccountController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.account.store');

Route::get('/dashboard/account/recovery-codes', [\App\Http\Controllers\AccountController::class, 'downloadRecoveryCodes'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.account.recovery-codes');

Route::post('/dashboard/account/totp/verify', [\App\Http\Controllers\AccountController::class, 'verifyTOTP'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.account.verify-totp');

Route::get('/error/key-and-session-missing', function (Request $request) {
    return Inertia::render('Errors/KeyAndSessionMissing');
})->name('error.key-and-session-missing');

Route::get('/error/key-invalid', function (Request $request) {
    return Inertia::render('Errors/KeyInvalid');
})->name('error.key-invalid');

Route::get('/error/key-disabled', function (Request $request) {
    return Inertia::render('Errors/KeyDisabled');
})->name('error.key-disabled');
