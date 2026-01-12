<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TelemarketingDashboardController;
use App\Http\Controllers\CrmDashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('home');
    });

    Route::get('home', function () {
        if(auth()->user()->hasRole('Marketing')) {
            return redirect()->route('surveys.create');
        }
        return view('index');
    })->name('home');

    Route::get('dashboard', [TelemarketingDashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::get('users/datatables', [UserController::class, 'datatables'])
    ->name('users.datatables');
    Route::resource('users', UserController::class);

    // Role Management
    Route::get('roles/datatables', [RoleController::class, 'datatables'])
    ->name('roles.datatables');
    Route::resource('roles', RoleController::class);

    // Permission Management
    Route::get('permissions/datatables', [\App\Http\Controllers\PermissionController::class, 'datatables'])
    ->name('permissions.datatables');
    Route::resource('permissions', \App\Http\Controllers\PermissionController::class);

    // Permission Subject Management
    Route::get('permission-subjects/datatables', [\App\Http\Controllers\PermissionSubjectController::class, 'datatables'])
    ->name('permission-subjects.datatables');
    Route::resource('permission-subjects', \App\Http\Controllers\PermissionSubjectController::class);

    // Product Management
    Route::get('products/datatables', [\App\Http\Controllers\ProductController::class, 'datatables'])
    ->name('products.datatables');
    Route::resource('products', \App\Http\Controllers\ProductController::class);

    // Common Type Management
    Route::get('common-type/datatables', [\App\Http\Controllers\TypeController::class, 'datatables'])
    ->name('common-type.datatables');
    Route::resource('common-type', \App\Http\Controllers\TypeController::class);

    // Common Merk Management
    Route::get('common-merk/datatables', [\App\Http\Controllers\MerkController::class, 'datatables'])
    ->name('common-merk.datatables');
    Route::resource('common-merk', \App\Http\Controllers\MerkController::class);

    // Common Payment Method Management
    Route::get('common-payment-method/datatables', [\App\Http\Controllers\PaymentMethodController::class, 'datatables'])
    ->name('common-payment-method.datatables');
    Route::resource('common-payment-method', \App\Http\Controllers\PaymentMethodController::class);

    // Common Position Management
    Route::get('common-position/datatables', [\App\Http\Controllers\PositionController::class, 'datatables'])
    ->name('common-position.datatables');
    Route::resource('common-position', \App\Http\Controllers\PositionController::class);

    // Campaign Reference Module
    Route::get('ref-compign/datatables', [\App\Http\Controllers\RefCompignController::class, 'datatables'])
    ->name('ref-compign.datatables');
    Route::post('ref-compign/update-status', [\App\Http\Controllers\RefCompignController::class, 'updateStatus'])
    ->name('ref-compign.update-status');
    Route::resource('ref-compign', \App\Http\Controllers\RefCompignController::class);

    // Commodity Reference Module
    Route::get('ref-commodity/datatables', [\App\Http\Controllers\RefCommodityController::class, 'datatables'])
    ->name('ref-commodity.datatables');
    Route::resource('ref-commodity', \App\Http\Controllers\RefCommodityController::class);
    //Contact Routes
    Route::get('contacts/datatables', [ContactController::class, 'datatables'])
    ->name('contacts.datatables');
    Route::get('contacts', [ContactController::class, 'list'])->name('contacts');

    //Leads Routes
    Route::get('leads', [LeadsController::class, 'list'])->name('leads');

    //Prospect Routes
    Route::get('prospects/datatables', [ProspectController::class, 'datatables'])->name('prospects.datatables');
    Route::post('prospects/{id}/promote', [ProspectController::class, 'promote'])->name('prospects.promote');
    Route::get('prospects', [ProspectController::class, 'list'])->name('prospects');

    //Customers Routes
    Route::get('customers', [CustomerController::class, 'index'])->name('customers');
    Route::get('customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::get('customers/datatables', [CustomerController::class, 'datatables'])->name('customers.datatables');
    Route::get('customers/{id}/summary', [CustomerController::class, 'summary'])->name('customers.summary');

    // Employee Routes
    Route::get('employees/datatables', [\App\Http\Controllers\EmployeeController::class, 'datatables'])->name('employees.datatables');
    Route::resource('employees', \App\Http\Controllers\EmployeeController::class);

    // Sales/Orders Routes
    Route::get('sales/datatables', [\App\Http\Controllers\SalesController::class, 'datatables'])
    ->name('sales.datatables');
    Route::resource('sales', \App\Http\Controllers\SalesController::class);
    Route::get('customers-dashboard', [\App\Http\Controllers\CustomerDashboardController::class, 'index'])
    ->name('customers-dashboard');
    Route::get('customers-dashboard/list', [\App\Http\Controllers\CustomerDashboardController::class, 'getCustomerList'])
    ->name('customers-dashboard.list');
    Route::get('products-dashboard', [\App\Http\Controllers\ProductDashboardController::class, 'index'])
    ->name('products-dashboard');
    Route::get('sales-dashboard', [\App\Http\Controllers\SalesDashboardController::class, 'index'])
    ->name('sales-dashboard');
    Route::get('crm-dashboard', [CrmDashboardController::class, 'index'])
    ->name('crm-dashboard');
    Route::get('employees-dashboard', [\App\Http\Controllers\EmployeeDashboardController::class, 'index'])
    ->name('employees-dashboard');
    // Reminder Routes
    Route::get('reminders', [\App\Http\Controllers\ReminderController::class, 'index'])->name('reminders.index');
    Route::get('reminders/last-order/{id}', [\App\Http\Controllers\ReminderController::class, 'getLastOrder'])->name('reminders.last-order');

    // Follow-up Routes
    Route::get('followups', [\App\Http\Controllers\FollowupController::class, 'index'])->name('followups.index');
    Route::post('followups/{id}/update-status', [\App\Http\Controllers\FollowupController::class, 'updateStatus'])->name('followups.update-status');

    // Activity Routes
    Route::get('activities/customers', [\App\Http\Controllers\ActivityController::class, 'getCustomers'])->name('activities.customers');
    Route::get('activities/datatables', [\App\Http\Controllers\ActivityController::class, 'datatables'])->name('activities.datatables');
    Route::resource('activities', \App\Http\Controllers\ActivityController::class);

    // Regional Data Routes
    Route::get('regional', [\App\Http\Controllers\RegionalDataController::class, 'index'])->name('regional.index');
    Route::get('regional/{province}/regencies', [\App\Http\Controllers\RegionalDataController::class, 'regencies'])->name('regional.regencies');
    Route::get('regional/regencies/{regency}/districts', [\App\Http\Controllers\RegionalDataController::class, 'districts'])->name('regional.districts');
    Route::get('regional/districts/{district}/villages', [\App\Http\Controllers\RegionalDataController::class, 'villages'])->name('regional.villages');

    // Regional Data JSON API
    Route::get('api/provinces', [\App\Http\Controllers\RegionalDataController::class, 'getProvinces'])->name('api.provinces');
    Route::get('api/provinces/{province}/regencies', [\App\Http\Controllers\RegionalDataController::class, 'getRegencies'])->name('api.regencies');
    Route::get('api/regencies/{regency}/districts', [\App\Http\Controllers\RegionalDataController::class, 'getDistricts'])->name('api.districts');
    Route::get('api/districts/{district}/villages', [\App\Http\Controllers\RegionalDataController::class, 'getVillages'])->name('api.villages');

    // Survey Routes
    Route::get('/surveys', [App\Http\Controllers\SurveyController::class, 'index'])->name('surveys.index');
    Route::get('/surveys/datatables', [App\Http\Controllers\SurveyController::class, 'datatables'])->name('surveys.datatables');
    Route::get('/surveys/create', [App\Http\Controllers\SurveyController::class, 'create'])->name('surveys.create');
    Route::post('/surveys', [App\Http\Controllers\SurveyController::class, 'store'])->name('surveys.store');
    Route::get('/surveys/repeat/{uid}', [App\Http\Controllers\SurveyController::class, 'repeat'])->name('surveys.repeat');
    Route::get('/surveys/{uid}', [App\Http\Controllers\SurveyController::class, 'getDetails'])->name('surveys.details');
    Route::get('/surveys/{uid}/show', [App\Http\Controllers\SurveyController::class, 'show'])->name('surveys.show');
    Route::post('/surveys/{uid}/promote', [App\Http\Controllers\SurveyController::class, 'promoteToProspect'])->name('surveys.promote');
    Route::post('/surveys/{uid}/assign-followup', [App\Http\Controllers\SurveyController::class, 'assignFollowupUser'])->name('surveys.assignFollowup');


    // Admin Survey Routes
    Route::get('/admin-surveys/create', [\App\Http\Controllers\AdminSurveyController::class, 'create'])->name('admin-surveys.create');
    Route::post('/admin-surveys', [\App\Http\Controllers\AdminSurveyController::class, 'store'])->name('admin-surveys.store');

    // Notification Routes
    Route::get('notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::post('notifications/{id}/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');

    // Delivery Schedule
    Route::get('delivery-schedule/invoices', [\App\Http\Controllers\DeliveryScheduleController::class, 'getInvoices'])->name('delivery-schedule.invoices');
    Route::resource('delivery-schedule', \App\Http\Controllers\DeliveryScheduleController::class);

    // Report Routes
    Route::get('sales-reports', [\App\Http\Controllers\SalesReportController::class, 'index'])->name('reports.sales.index');
    Route::get('sales-reports/datatables', [\App\Http\Controllers\SalesReportController::class, 'datatables'])->name('reports.sales.datatables');
    Route::get('sales-reports/{id}', [\App\Http\Controllers\SalesReportController::class, 'show'])->name('reports.sales.show');
    Route::get('sales-reports/export/pdf', [\App\Http\Controllers\SalesReportController::class, 'exportPdf'])->name('reports.sales.export.pdf');
    Route::get('sales-reports/export/excel', [\App\Http\Controllers\SalesReportController::class, 'exportExcel'])->name('reports.sales.export.excel');

    Route::get('sales-delivery-reports', [\App\Http\Controllers\SalesDeliveryReportController::class, 'index'])->name('reports.sales-delivery.index');
    Route::get('sales-delivery-reports/datatables', [\App\Http\Controllers\SalesDeliveryReportController::class, 'datatables'])->name('reports.sales-delivery.datatables');
    Route::get('sales-delivery-reports/export/pdf', [\App\Http\Controllers\SalesDeliveryReportController::class, 'exportPdf'])->name('reports.sales-delivery.export.pdf');
    Route::get('sales-delivery-reports/export/excel', [\App\Http\Controllers\SalesDeliveryReportController::class, 'exportExcel'])->name('reports.sales-delivery.export.excel');

    // Approval Center Routes
    Route::get('approvals/datatables', [\App\Http\Controllers\ApprovalController::class, 'datatables'])->name('approvals.datatables');
    Route::post('approvals/{id}/action', [\App\Http\Controllers\ApprovalController::class, 'action'])->name('approvals.action');
    Route::get('approvals', [\App\Http\Controllers\ApprovalController::class, 'index'])->name('approvals.index');
});
