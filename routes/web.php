<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDocumentController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PerformanceReviewController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\PublicHolidayController;
use App\Http\Controllers\OfficeSettingController;
use App\Http\Controllers\NotificationController;

// ====================== PUBLIC ROUTES ======================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// ====================== PROTECTED ROUTES ======================
Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // HR & Super Admin Routes
    Route::middleware('role:Super Admin,HR')->group(function () {

        // PAYROLL CUSTOM ROUTES
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

        // RESOURCE ROUTES
        Route::resource('payrolls', PayrollController::class);
        Route::resource('departments', DepartmentController::class);
        Route::resource('employees', EmployeeController::class);
        Route::resource('leave-types', LeaveTypeController::class);
        Route::resource('performance-reviews', PerformanceReviewController::class)->only(['index', 'create', 'store']);
        Route::resource('activity-logs', ActivityLogController::class)->only(['index', 'show']);

        // HR Approval Request
        Route::get('/leave-requests/pending', [LeaveRequestController::class, 'pending'])->name('leave-requests.pending');
        Route::post('/leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('leave-requests.approve');

        // Reject leave request
        Route::post('/leave-requests/{id}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');

        Route::get('/reports/leave-usage', [DashboardController::class, 'leaveUsageReport'])
            ->name('reports.leave-usage');

        // Attendance
        Route::get('/mark-attendance', [DashboardController::class, 'markAttendanceForm'])->name('attendance.mark');
        Route::post('/mark-attendance', [DashboardController::class, 'storeAttendance'])->name('attendance.store');
        Route::get('/hr-attendance', [DashboardController::class, 'hrAttendance'])->name('hr.attendance');

        // Public holidays
        Route::resource('public-holidays', PublicHolidayController::class);
        Route::get('/holiday-calendar', [PublicHolidayController::class, 'calendar'])->name('public-holidays.calendar');

        // Documents
        Route::get('/employees/{employee}/documents', [EmployeeDocumentController::class, 'index'])->name('employees.documents.index');
        Route::get('/employees/{employee}/documents/create', [EmployeeDocumentController::class, 'create'])->name('employees.documents.create');
        Route::post('/employees/{employee}/documents', [EmployeeDocumentController::class, 'store'])->name('employees.documents.store');
        Route::delete('/documents/{document}', [EmployeeDocumentController::class, 'destroy'])->name('documents.destroy');

        // Announcements 
        Route::resource('announcements', AnnouncementController::class)->except(['show']);

        // Settings
        Route::get('/settings', [OfficeSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings/general', [OfficeSettingController::class, 'updateGeneral'])->name('settings.general');
        Route::post('/settings/notifications', [OfficeSettingController::class, 'updateNotifications'])->name('settings.notifications');
        Route::post('/settings/security', [OfficeSettingController::class, 'updateSecurity'])->name('settings.security');
        Route::post('/settings/appearance', [OfficeSettingController::class, 'updateAppearance'])->name('settings.appearance');
    });

    // Super Admin, HR, Manager
    Route::middleware(['auth', 'role:Super Admin,HR,Manager'])->group(function () {
        Route::get('/performance-reviews', [PerformanceReviewController::class, 'index'])->name('performance-reviews.index');
        Route::get('/performance-reviews/create', [PerformanceReviewController::class, 'create'])->name('performance-reviews.create');
        Route::post('/performance-reviews', [PerformanceReviewController::class, 'store'])->name('performance-reviews.store');
        // Route::get('/performance-reviews/{performanceReview}', [PerformanceReviewController::class, 'show'])->name('performance-reviews.show');
    });

    // Manager Routes
    Route::middleware('role:Manager')->group(function () {
        Route::get('/team-members', [DashboardController::class, 'teamMembers'])->name('team.members');
        Route::get('/team-leaves', [DashboardController::class, 'teamLeaves'])->name('team.leaves');
        Route::get('/team-attendance', [DashboardController::class, 'teamAttendance'])->name('team.attendance');
        Route::get('/mark-attendance', [DashboardController::class, 'markAttendanceForm'])->name('attendance.mark');
        Route::post('/mark-attendance', [DashboardController::class, 'storeAttendance'])->name('attendance.store');
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

        // Announcements 
        Route::get('/announcement-board', [AnnouncementController::class, 'board'])->name('announcements.board');

        // Documents
        Route::get('/documents/{document}/download', [EmployeeDocumentController::class, 'download'])->name('documents.download');
        Route::get('/my-documents', [EmployeeDocumentController::class, 'myDocuments'])->name('my.documents');
        Route::get('/documents/{document}/download', [EmployeeDocumentController::class, 'download'])->name('documents.download');

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');

        // Performance
        Route::get('/performance-reviews/{performanceReview}', [PerformanceReviewController::class, 'show'])->name('performance-reviews.show');
    });

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
