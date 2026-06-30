<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\SampleController;
use App\Http\Controllers\TestResultController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TestParameterController;
use App\Http\Controllers\ClientController;
use App\Models\TestParameter;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Work Orders
    Route::resource('work-orders', WorkOrderController::class);
    Route::post('purposes', [WorkOrderController::class, 'storePurpose'])->name('purposes.store');

    // ==================== SAMPLE REGISTRATION ====================
    // List work orders for sample registration
    Route::get('samples', [SampleController::class, 'index'])->name('samples.index');

    // Create a new sample for a specific work order
    Route::get('samples/{workOrder}/create', [SampleController::class, 'create'])->name('samples.create');
    Route::post('samples/{workOrder}', [SampleController::class, 'store'])->name('samples.store');

    // AJAX endpoints for cascading geo dropdowns
    Route::get('samples/districts/{provinceId}', [SampleController::class, 'getDistricts'])->name('samples.districts');
    Route::get('samples/communes/{districtId}', [SampleController::class, 'getCommunes'])->name('samples.communes');
    Route::get('samples/villages/{communeId}', [SampleController::class, 'getVillages'])->name('samples.villages');

    // ==================== TEST RESULTS ====================
    Route::get('samples/{sample}/tests', [TestResultController::class, 'index'])->name('samples.tests.index');
    Route::post('samples/{sample}/tests/{sampleTest}/result', [TestResultController::class, 'store'])->name('samples.tests.result.store');
    Route::put('samples/{sample}/tests/{sampleTest}/result', [TestResultController::class, 'update'])->name('samples.tests.result.update');

    // ==================== APPROVALS ====================
    Route::get('approvals/pending', [ApprovalController::class, 'pending'])->name('approvals.pending');
    Route::post('approvals/{testResult}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('approvals/{testResult}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');

    // ==================== REPORTS ====================
    Route::get('work-orders/{workOrder}/report', [ReportController::class, 'generate'])->name('reports.generate');
    Route::get('work-orders/{workOrder}/report/download', [ReportController::class, 'download'])->name('reports.download');

    // ==================== TEST PARAMETERS JSON ====================
    Route::get('test-parameters/{testParameter}/json', function (TestParameter $testParameter) {
        return response()->json($testParameter);
    })->name('test-parameters.json');

    // ==================== ADMIN-ONLY ROUTES ====================
    Route::middleware(['admin'])->group(function () {
        Route::resource('clients', ClientController::class);
        Route::get('clients/search', [ClientController::class, 'search'])->name('clients.search');
        Route::resource('test-parameters', TestParameterController::class);
        Route::resource('users', UserController::class);
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    });
});

require __DIR__.'/auth.php';