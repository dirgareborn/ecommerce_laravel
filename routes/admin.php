<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    AdminController,
    DashboardController,
    AdminRoleController,
    EmployeeController,
    CategoryController,
    ProductController,
    BannerController,
    AccountBankController,
    CouponController,
    CmsController,
    OrderController,
    CalendarController,
    FileController,
    SystemInfoController,
    DatabaseController,
    UserController,
    InformationController,
    MitraController,
    DocumentController
};

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Login
Route::match(['get','post'], 'admin/login', [AdminController::class, 'login'])->name('admin.login');

// Admin routes with middleware
Route::prefix('admin')->middleware(['auth:admin'])->group(function(){

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');

    // Admin profile
    Route::match(['get','post'], 'update-password', [AdminController::class, 'updatePassword'])->name('admin.update-password');
    Route::match(['get','post'], 'profil', [AdminController::class, 'updateDetail'])->name('admin.profil');
    Route::post('check-current-password', [AdminController::class, 'checkCurrentPassword'])->name('admin.check-current-password');
    Route::get('logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::resource('mitra',MitraController::class, [
           'names' => [
            'index' => 'admin.mitra.index',
            'create' => 'admin.mitra.create',
            'store' => 'admin.mitra.store',
            'show' => 'admin.mitra.show',
            'edit' => 'admin.mitra.edit',
            'update' => 'admin.mitra.update',
            'destroy' => 'admin.mitra.destroy',
        ]
    ]);
    Route::patch('mitra/{mitra}/toggle-status', [MitraController::class, 'toggleStatus'])->name('admin.mitra.toggle');

    // Roles
    Route::resource('roles', AdminRoleController::class, [
        'names' => [
            'index' => 'admin.roles.index',
            'create' => 'admin.roles.create',
            'store' => 'admin.roles.store',
            'show' => 'admin.roles.show',
            'edit' => 'admin.roles.edit',
            'update' => 'admin.roles.update',
            'destroy' => 'admin.roles.destroy',
        ]
    ]);

    Route::resource('information',InformationController::class, [
        'names' => [
            'index' => 'admin.information.index',
            'create' => 'admin.information.create',
            'store' => 'admin.information.store',
            'edit' => 'admin.information.edit',
            'update' => 'admin.information.update',
            'destroy' => 'admin.information.destroy',
        ]
    ]);
    Route::post('information/upload',[InformationController::class,'upload'])->name('admin.information.upload');

    Route::resource('document',DocumentController::class, [
        'names' => [
            'index' => 'admin.document.index',
            'create' => 'admin.document.create',
            'store' => 'admin.document.store',
            'edit' => 'admin.document.edit',
            'update' => 'admin.document.update',
            'destroy' => 'admin.document.destroy',
        ]
    ]);
    Route::get('document/download/{document}',[DocumentController::class,'download'])->name('admin.document.download');
    Route::get('document/preview/{document}',[DocumentController::class,'preview'])->name('admin.document.preview');

    // Employees
    Route::get('employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

    // CMS Pages
    Route::get('cms-pages', [CmsController::class, 'index'])->name('admin.cms.index');
    Route::post('update-cms-page-status', [CmsController::class, 'update'])->name('admin.cms.update-status');
    Route::match(['get','post'], 'add-edit-cms-page/{id?}', [CmsController::class, 'edit'])->name('admin.cms.edit');
    Route::get('delete-cms-page/{id?}', [CmsController::class, 'destroy'])->name('admin.cms.destroy');

    // Subadmins
    Route::get('subadmins', [AdminController::class, 'subadmins'])->name('admin.subadmins');
    Route::post('update-subadmin-status', [AdminController::class, 'updateSubadminStatus'])->name('admin.subadmins.update-status');
    Route::match(['get','post'], 'add-subadmin/{id?}', [AdminController::class, 'edit'])->name('admin.subadmins.edit');
    Route::get('delete-subadmin/{id?}', [AdminController::class, 'deleteSubAdmin'])->name('admin.subadmins.delete');

    // Roles Permissions (update-role)
    Route::match(['get','post'], 'update-role/{id?}', [AdminController::class, 'updateRole'])->name('admin.update-role');

    // Categories
    Route::get('categories', [CategoryController::class, 'categories'])->name('admin.categories.index');
    Route::post('update-category-status', [CategoryController::class, 'updateStatus'])->name('admin.categories.update-status');
    Route::get('delete-category/{id?}', [CategoryController::class, 'deleteCategory'])->name('admin.categories.delete');
    Route::get('delete-category-image/{id?}', [CategoryController::class, 'deleteCategoryImage'])->name('admin.categories.delete-image');
    Route::match(['get','post'], 'add-edit-category/{id?}', [CategoryController::class, 'edit'])->name('admin.categories.edit');

    // Products
    Route::get('products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::post('update-product-status', [ProductController::class, 'updateStatus'])->name('admin.products.update-status');
    Route::get('delete-product/{id?}', [ProductController::class, 'deleteProduct'])->name('admin.products.delete');
    Route::get('delete-product-image/{id?}', [ProductController::class, 'deleteProductImage'])->name('admin.products.delete-image');
    Route::get('delete-product-image-slide/{id?}', [ProductController::class, 'deleteSlideImage'])->name('admin.products.delete-image-slide');
    Route::match(['get','post'], 'add-edit-product/{id?}', [ProductController::class, 'edit'])->name('admin.products.edit');

    // Banners
    Route::get('banners', [BannerController::class, 'banners'])->name('admin.banners.index');
    Route::post('update-banner-status', [BannerController::class, 'updateStatus'])->name('admin.banners.update-status');
    Route::get('delete-banner/{id?}', [BannerController::class, 'deleteBanner'])->name('admin.banners.delete');
    Route::get('delete-banner-image/{id?}', [BannerController::class, 'deleteBannerImage'])->name('admin.banners.delete-image');
    Route::match(['get','post'], 'add-edit-banner/{id?}', [BannerController::class, 'edit'])->name('admin.banners.edit');

    // Account Banks
    Route::get('account-banks', [AccountBankController::class, 'accountbanks'])->name('admin.account-banks.index');
    Route::post('update-account-bank-status', [AccountBankController::class, 'updateStatus'])->name('admin.account-banks.update-status');
    Route::get('delete-account-bank/{id?}', [AccountBankController::class, 'deleteAccountBank'])->name('admin.account-banks.delete');
    Route::get('delete-account-bank-icon/{id?}', [AccountBankController::class, 'deleteAccountBankImage'])->name('admin.account-banks.delete-icon');
    Route::match(['get','post'], 'add-edit-account-bank/{id?}', [AccountBankController::class, 'edit'])->name('admin.account-banks.edit');

    // Coupons
    Route::get('coupons', [CouponController::class, 'coupons'])->name('admin.coupons.index');
    Route::post('update-coupon-status', [CouponController::class, 'updateStatus'])->name('admin.coupons.update-status');
    Route::get('delete-coupon/{id?}', [CouponController::class, 'deletCoupon'])->name('admin.coupons.delete');
    Route::match(['get','post'], 'add-edit-coupon/{id?}', [CouponController::class, 'edit'])->name('admin.coupons.edit');

    // Customers
    Route::get('customers', [UserController::class, 'users'])->name('admin.customers.index');

    // Orders
    Route::get('orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::post('check-availability', [OrderController::class, 'checkAvailability'])->name('admin.orders.check-availability');
    Route::post('get-price', [OrderController::class, 'getPrice'])->name('admin.orders.get-price');
    Route::post('add-order', [OrderController::class, 'store'])->name('admin.orders.store');
    Route::get('order-details/{id}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::get('edit-order/{id}', [OrderController::class, 'edit'])->name('admin.orders.edit');
    Route::put('order/{order}', [OrderController::class, 'update'])->name('admin.orders.update');
    Route::get('orders/{id}/payment-proof', [OrderController::class, 'showPaymentProof'])->name('admin.orders.paymentProof');
    Route::get('orders/export', [OrderController::class, 'export'])->name('admin.orders.export');
    Route::get('orders/export-pdf', [OrderController::class, 'exportPdf'])->name('admin.orders.exportPdf');

    // Calendar
    Route::get('calendar', [CalendarController::class, 'index'])->name('admin.calendar.index');
    Route::get('calendar/events', [CalendarController::class, 'events'])->name('admin.calendar.events');
    Route::get('orders/{id}/detail', [CalendarController::class, 'detailAjax'])->name('admin.calendar.detailAjax');

    // File Manager
    Route::get('files', [FileController::class, 'index'])->name('admin.files.index');
    Route::post('files/upload', [FileController::class, 'upload'])->name('admin.files.upload');
    Route::post('files/folder', [FileController::class, 'createFolder'])->name('admin.files.folder');
    Route::post('files/rename', [FileController::class, 'rename'])->name('admin.files.rename');
    Route::post('files/move', [FileController::class, 'move'])->name('admin.files.move');
    Route::get('files/download/{folder?}/{filename}', [FileController::class, 'download'])->name('admin.files.download');
    Route::delete('files/delete', [FileController::class, 'delete'])->name('admin.files.delete');
    Route::get('files/search', [FileController::class, 'search'])->name('admin.files.search');

    // System Info & Logs
    Route::get('system', [SystemInfoController::class, 'index'])->name('admin.system');
    Route::get('system/download', [SystemInfoController::class, 'download'])->name('admin.system.download');

    // Database Backup & Restore
    Route::get('database', [DatabaseController::class, 'index'])->name('admin.database');
    Route::get('database/backup', [DatabaseController::class, 'backup'])->name('admin.database.backup');
    Route::post('database/restore', [DatabaseController::class, 'restore'])->name('admin.database.restore');
    Route::get('database/download/{fileName}', [DatabaseController::class, 'download'])->name('admin.database.download');

    // Email Logs
    Route::get('email-logs', function () {
        $logs = \App\Models\EmailLog::latest()->paginate(20);
        return view('admin.email_logs.index', compact('logs'));
    })->name('admin.email-logs.index');

});
