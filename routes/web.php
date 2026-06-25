<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SampleController;
use App\Http\Controllers\TestResultController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TestParameterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return redirect()->route('login'); });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('samples', SampleController::class);

    Route::get('samples/{sample}/tests', [TestResultController::class, 'index'])->name('samples.tests.index');
    Route::post('samples/{sample}/tests/{sampleTest}/result', [TestResultController::class, 'store'])->name('samples.tests.result.store');
    Route::put('samples/{sample}/tests/{sampleTest}/result', [TestResultController::class, 'update'])->name('samples.tests.result.update');

    Route::get('approvals/pending', [ApprovalController::class, 'pending'])->name('approvals.pending');
    Route::post('approvals/{testResult}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('approvals/{testResult}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');

    Route::get('work-orders/{workOrder}/report', [ReportController::class, 'generate'])->name('reports.generate');
    Route::get('work-orders/{workOrder}/report/download', [ReportController::class, 'download'])->name('reports.download');

    Route::middleware(['admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::resource('test-parameters', TestParameterController::class);
    });
});

require __DIR__.'/auth.php';