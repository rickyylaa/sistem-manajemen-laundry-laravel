<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Laundry\Auth\RoleController;

use App\Http\Controllers\Laundry\Admin\OwnerController as AdminOwnerController;
use App\Http\Controllers\Laundry\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Laundry\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Laundry\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Laundry\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Laundry\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Laundry\Admin\EmployeeController as AdminEmployeeController;
use App\Http\Controllers\Laundry\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Laundry\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Laundry\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Laundry\Admin\NotificationController as AdminNotificationController;

use App\Http\Controllers\Laundry\Owner\ReportController as OwnerReportController;
use App\Http\Controllers\Laundry\Owner\ProfileController as OwnerProfileController;
use App\Http\Controllers\Laundry\Owner\DashboardController as OwnerDashboardController;

use App\Http\Controllers\Laundry\Employee\CouponController as EmployeeCouponController;
use App\Http\Controllers\Laundry\Employee\ProfileController as EmployeeProfileController;
use App\Http\Controllers\Laundry\Employee\CustomerController as EmployeeCustomerController;
use App\Http\Controllers\Laundry\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Laundry\Employee\TransactionController as EmployeeTransactionController;

use App\Http\Controllers\Laundry\Customer\CouponController as CustomerCouponController;
use App\Http\Controllers\Laundry\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Laundry\Customer\TransactionController as CustomerTransactionController;
use App\Http\Controllers\Laundry\Customer\DashboardController as CustomerDashboardController;

/* ROUTE */

Auth::routes();
Route::middleware(['auth'])->group(function () {
    Route::get('/roles', RoleController::class);

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('/admin/profile/{username}', [AdminProfileController::class, 'index'])->name('admin.profile');
        Route::put('/admin/profile/{username}', [AdminProfileController::class, 'update'])->name('admin.profileUpdate');

        Route::get('/admin/package', [AdminPackageController::class, 'index'])->name('admin.package');
        Route::post('/admin/package', [AdminPackageController::class, 'store'])->name('admin.packageStore');
        Route::get('/admin/package/{id}', [AdminPackageController::class, 'edit'])->name('admin.packageEdit');
        Route::put('/admin/package/{id}', [AdminPackageController::class, 'update'])->name('admin.packageUpdate');
        Route::delete('/admin/package/{id}', [AdminPackageController::class, 'destroy'])->name('admin.packageDestroy');

        Route::get('/admin/owner', [AdminOwnerController::class, 'index'])->name('admin.owner');
        Route::post('/admin/owner', [AdminOwnerController::class, 'store'])->name('admin.ownerStore');
        Route::get('/admin/owner/{username}', [AdminOwnerController::class, 'edit'])->name('admin.ownerEdit');
        Route::put('/admin/owner/{username}', [AdminOwnerController::class, 'update'])->name('admin.ownerUpdate');
        Route::delete('/admin/owner/{username}', [AdminOwnerController::class, 'destroy'])->name('admin.ownerDestroy');

        Route::get('/admin/employee', [AdminEmployeeController::class, 'index'])->name('admin.employee');
        Route::post('/admin/employee', [AdminEmployeeController::class, 'store'])->name('admin.employeeStore');
        Route::get('/admin/employee/{username}', [AdminEmployeeController::class, 'edit'])->name('admin.employeeEdit');
        Route::put('/admin/employee/{username}', [AdminEmployeeController::class, 'update'])->name('admin.employeeUpdate');
        Route::delete('/admin/employee/{username}', [AdminEmployeeController::class, 'destroy'])->name('admin.employeeDestroy');

        Route::get('/admin/customer', [AdminCustomerController::class, 'index'])->name('admin.customer');
        Route::post('/admin/customer', [AdminCustomerController::class, 'store'])->name('admin.customerStore');
        Route::get('/admin/customer/{username}', [AdminCustomerController::class, 'edit'])->name('admin.customerEdit');
        Route::put('/admin/customer/{username}', [AdminCustomerController::class, 'update'])->name('admin.customerUpdate');
        Route::delete('/admin/customer/{username}', [AdminCustomerController::class, 'destroy'])->name('admin.customerDestroy');

        Route::get('/admin/transaction', [AdminTransactionController::class, 'index'])->name('admin.transaction');
        Route::post('/admin/transaction', [AdminTransactionController::class, 'store'])->name('admin.transactionStore');
        Route::post('/admin/transactionCustomer', [AdminTransactionController::class, 'storeCustomer'])->name('admin.transactionStoreCustomer');
        Route::delete('/admin/transaction/{invoice}', [AdminTransactionController::class, 'destroy'])->name('admin.transactionDestroy');

        Route::post('/admin/processed/{invoice}', [AdminTransactionController::class, 'processed'])->name('admin.transactionProcessed');
        Route::post('/admin/complete/{invoice}', [AdminTransactionController::class, 'complete'])->name('admin.transactionComplete');
        Route::post('/admin/retrieved/{invoice}', [AdminTransactionController::class, 'retrieved'])->name('admin.transactionRetrieved');
        Route::get('/admin/transaction/{customer_id}', [AdminTransactionController::class, 'getCoupons'])->name('admin.getCoupons');
        Route::get('/admin/transaction/{invoice}/receipt', [AdminTransactionController::class, 'transactionReceipt'])->name('admin.transactionReceipt');

        Route::get('/admin/report', [AdminReportController::class, 'index'])->name('admin.report');
        Route::get('/admin/report/pdf/{dateRange}', [AdminReportController::class, 'pdf'])->name('admin.reportPDF');

        Route::get('/admin/coupon', [AdminCouponController::class, 'index'])->name('admin.coupon');
        Route::post('/admin/receive/coupon/{id}', [AdminCouponController::class, 'receive'])->name('admin.couponReceive');
        Route::delete('/admin/coupon/{id}', [AdminCouponController::class, 'destroy'])->name('admin.couponDestroy');

        Route::get('/admin/review', [AdminReviewController::class, 'index'])->name('admin.review');
        Route::put('/admin/review/{id}', [AdminReviewController::class, 'update'])->name('admin.reviewUpdate');
        Route::delete('/admin/review/{id}', [AdminReviewController::class, 'destroy'])->name('admin.reviewDestroy');
        Route::get('admin/review/loadMore', [AdminReviewController::class, 'reviewLoadMore'])->name('admin.reviewLoadMore');

        Route::post('/admin/markAsRead', [AdminNotificationController::class, 'markAsRead'])->name('admin.markAsRead');
        Route::post('/admin/markAllAsRead', [AdminNotificationController::class, 'markAllAsRead'])->name('admin.markAllAsRead');
    });

    Route::middleware(['role:owner'])->group(function () {
        Route::get('/owner/dashboard', [OwnerDashboardController::class, 'index'])->name('owner.dashboard');

        Route::get('/owner/profile/{username}', [OwnerProfileController::class, 'index'])->name('owner.profile');
        Route::put('/owner/profile/{username}', [OwnerProfileController::class, 'update'])->name('owner.profileUpdate');

        Route::get('/owner/report', [OwnerReportController::class, 'index'])->name('owner.report');
        Route::get('/owner/report/pdf/{dateRange}', [OwnerReportController::class, 'pdf'])->name('owner.reportPDF');
    });

    Route::middleware(['role:employee'])->group(function () {
        Route::get('/employee/dashboard', [EmployeeDashboardController::class, 'index'])->name('employee.dashboard');

        Route::get('/employee/profile/{username}', [EmployeeProfileController::class, 'index'])->name('employee.profile');
        Route::put('/employee/profile/{username}', [EmployeeProfileController::class, 'update'])->name('employee.profileUpdate');

        Route::get('/employee/customer', [EmployeeCustomerController::class, 'index'])->name('employee.customer');
        Route::post('/employee/customer', [EmployeeCustomerController::class, 'store'])->name('employee.customerStore');
        Route::get('/employee/customer/{username}', [EmployeeCustomerController::class, 'edit'])->name('employee.customerEdit');
        Route::put('/employee/customer/{username}', [EmployeeCustomerController::class, 'update'])->name('employee.customerUpdate');
        Route::delete('/employee/customer/{username}', [EmployeeCustomerController::class, 'destroy'])->name('employee.customerDestroy');

        Route::get('/employee/transaction', [EmployeeTransactionController::class, 'index'])->name('employee.transaction');
        Route::post('/employee/transaction', [EmployeeTransactionController::class, 'store'])->name('employee.transactionStore');
        Route::post('/employee/transactionCustomer', [EmployeeTransactionController::class, 'storeCustomer'])->name('employee.transactionStoreCustomer');
        Route::delete('/employee/transaction/{invoice}', [EmployeeTransactionController::class, 'destroy'])->name('employee.transactionDestroy');

        Route::post('/employee/processed/{invoice}', [EmployeeTransactionController::class, 'processed'])->name('employee.transactionProcessed');
        Route::post('/employee/complete/{invoice}', [EmployeeTransactionController::class, 'complete'])->name('employee.transactionComplete');
        Route::post('/employee/retrieved/{invoice}', [EmployeeTransactionController::class, 'retrieved'])->name('employee.transactionRetrieved');
        Route::get('/employee/transaction/{customer_id}', [EmployeeTransactionController::class, 'getCoupons'])->name('employee.getCoupons');
        Route::get('/employee/transaction/{invoice}/receipt', [EmployeeTransactionController::class, 'transactionReceipt'])->name('employee.transactionReceipt');

        Route::get('/employee/coupon', [EmployeeCouponController::class, 'index'])->name('employee.coupon');
        Route::post('/employee/receive/coupon/{id}', [EmployeeCouponController::class, 'receive'])->name('employee.couponReceive');
        Route::delete('/employee/coupon/{id}', [EmployeeCouponController::class, 'destroy'])->name('employee.couponDestroy');
    });

    Route::middleware(['role:customer'])->group(function () {
        Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');

        Route::get('/customer/profile/{username}', [CustomerProfileController::class, 'index'])->name('customer.profile');
        Route::put('/customer/profile/{username}', [CustomerProfileController::class, 'update'])->name('customer.profileUpdate');

        Route::get('/customer/transaction', [CustomerTransactionController::class, 'index'])->name('customer.transaction');
        Route::get('/customer/transaction/{invoice}/receipt', [CustomerTransactionController::class, 'transactionReceipt'])->name('customer.transactionReceipt');
        Route::get('/customer/coupon', [CustomerCouponController::class, 'index'])->name('customer.coupon');

        Route::post('/customer/review', [CustomerDashboardController::class, 'reviewStore'])->name('customer.reviewStore');
        Route::get('customer/review/load-more', [CustomerDashboardController::class, 'reviewLoadMore'])->name('customer.reviewLoadMore');
    });
});

/* ROUTE */
