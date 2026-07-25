<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PerformanceReviewController;
use App\Http\Controllers\ActivityLogController;

// ====================== PUBLIC ROUTES ======================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// ====================== PROTECTED ROUTES ======================
Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // HR & Super Admin Routes
    Route::middleware('role:Super Admin,HR')->group(function () {
        
        // 1. PAYROLL CUSTOM ROUTES (ZIMEPANDISHWA JUU KUZUIA 404 CONFLICT)
        Route::get('/payrolls/reports', [PayrollController::class, 'reports'])
             ->name('payrolls.reports');
        Route::get('/payrolls/reports/monthly/{month?}', [PayrollController::class, 'monthlyReport'])
             ->name('payrolls.monthly.report');
        Route::get('/payrolls/reports/monthly/{month}/export', [PayrollController::class, 'exportMonthlyExcel'])
             ->name('payrolls.monthly.export');

        // Bulk Payroll Routes
        Route::get('/payrolls/bulk/create', [PayrollController::class, 'bulkCreate'])->name('payrolls.bulk.create');
        Route::post('/payrolls/bulk/preview', [PayrollController::class, 'bulkPreview'])->name('payrolls.bulk.preview');
        Route::post('/payrolls/bulk/store', [PayrollController::class, 'bulkStore'])->name('payrolls.bulk.store');

        // Payroll Edit, Update and Actions
        Route::get('/payrolls/{payroll}/edit', [PayrollController::class, 'edit'])->name('payrolls.edit');
        Route::put('/payrolls/{payroll}', [PayrollController::class, 'update'])->name('payrolls.update');
        Route::post('/payrolls/{payroll}/mark-paid', [PayrollController::class, 'markAsPaid'])->name('payrolls.mark-paid');


        // 2. RESOURCE ROUTES (ZIMEWEKWA CHINI YA CUSTOM ROUTES)
        Route::resource('payrolls', PayrollController::class);
        Route::resource('departments', DepartmentController::class);
        Route::resource('employees', EmployeeController::class);
        Route::resource('leave-types', LeaveTypeController::class);
        Route::resource('performance-reviews', PerformanceReviewController::class)->only(['index', 'show', 'create', 'store']);
        Route::resource('activity-logs', ActivityLogController::class)->only(['index', 'show']);
        

        // HR Approval Request
        Route::get('/leave-requests/pending', [LeaveRequestController::class, 'pending'])->name('leave-requests.pending');
        Route::post('/leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
        
        // Reject leave request
        Route::post('/leave-requests/{id}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
    });

    // All Authenticated Users (Employee, Manager, HR)
    Route::group([], function () {
        // Leave Self Service
        Route::get('/apply-leave', [LeaveRequestController::class, 'create'])->name('apply-leave');
        Route::post('/apply-leave', [LeaveRequestController::class, 'store'])->name('leave-requests.store');
        Route::get('/my-leaves', [LeaveRequestController::class, 'myLeaves'])->name('my-leaves');

        // Profile Routes
        Route::get('/my-profile', [DashboardController::class, 'myProfile'])->name('my-profile');
        Route::get('/profile/edit', [DashboardController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile/update', [DashboardController::class, 'updateProfile'])->name('profile.update');
        
        // Delete and edit leave request
        Route::get('/leave-requests/{leaveRequest}/edit', [LeaveRequestController::class, 'edit'])->name('leave-requests.edit');
        Route::put('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'update'])->name('leave-requests.update');
        Route::delete('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'destroy'])->name('leave-requests.destroy');

        // Employee Payroll
        Route::get('/my-payslips', [PayrollController::class, 'myPayslips'])->name('my-payslips');
        Route::get('/my-payslips/{payroll}', [PayrollController::class, 'showEmployeePayslip'])->name('my-payslip.show');
        
        // Download Payslip
        Route::get('/payrolls/{payroll}/download', [PayrollController::class, 'downloadPayslip'])->name('payrolls.download');

        // Change Password
        Route::get('/change-password', [DashboardController::class, 'changePassword'])->name('password.change');
        Route::put('/change-password', [DashboardController::class, 'updatePassword'])->name('password.update');
    });

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
