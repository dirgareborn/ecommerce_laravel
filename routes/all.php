<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\CategoryController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\PageController;
use App\Http\Controllers\Front\UserController;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\Front\ProductRatingController;
use App\Models\Category;
use App\Models\CmsPage;
use Carbon\Carbon;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Admin\OrderController as OrderAdminController;
use App\Http\Controllers\Admin\EmployeeController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Front\RoomFrontController;
use App\Http\Controllers\Front\InformationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\SystemInfoController;
use App\Http\Controllers\Admin\DatabaseController;
use App\Http\Controllers\Admin\AdminRoleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/')->with('success', 'Email berhasil diverifikasi!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi baru telah dikirim ke email Anda!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Front Route Customer
Route::middleware(['auth','verified'])->namespace('App\Http\Controllers\Front')->group(function () {


    Route::post('product/{product}/rate', [ProductRatingController::class, 'rate'])->name('product.rate');
    Route::get('/account','UserController@account');
    Route::get('/profil','UserController@profil');
    Route::match(['get','post'],'update-password','UserController@updatePassword');
    Route::post('update-detail','UserController@updateDetail');
    Route::post('check-current-password','UserController@checkCurrentPassword');
    Route::match(['get','post'],'testimonial','UserController@testimonial');
    Route::post('/upload-payment-proof', [OrderController::class, 'uploadProof'])->name('upload.payment.proof');
    // Route::get('/view-proof/{order}', [OrderController::class, 'viewProof'])->name('view.payment.proof');
    Route::get('/payment-proof/{order}', function (\App\Models\Order $order) {
        if (!auth()->check() || auth()->id() !== $order->user_id) {
            abort(403, 'Unauthorized');
        }

        $path = storage_path('app/private/payment_proofs/' . $order->payment_proof);

        if (!file_exists($path)) {
            abort(404, 'File not found');
        }

        return response()->file($path);
    })->name('payment.proof.show');

    Route::post('informasi/{information}/comment', [InformationController::class, 'comment'])->name('informasi.comment');
    Route::post('informasi/{information}/reply', [InformationController::class, 'reply'])->name('informasi.reply');

});


// Frontend routes
Route::get('/employees', [App\Http\Controllers\Frontend\EmployeeController::class, 'index'])->name('employees.list');

// Front Public
Route::namespace('App\Http\Controllers\Front')->group(function(){
    Route::get('/', [PageController::class,'index'])->name('beranda');

    // Listing Categories Route
	//Category all
    Route::get('kategori', 'CategoryController@category');

   // Dynamic category & search
    Route::get('/kategori/{slug?}', [ProductController::class, 'listing'])
    ->name('categories.listing');


    //Product Detail ini masih mau di ubah
    Route::get('kategori/{category}/{url}','ProductController@show');

    // Product Search
    Route::get('search-products','ProductController@listing');
    
   //Cart
    Route::post('/addCart', 'CartController@addToCart');
    Route::get('/cart','CartController@index');
    Route::get('/cart/delete-cart-item/{id?}','CartController@delete')->name('cart.delete');
    Route::post('/apply-coupon','CartController@applyCoupon')->name('cart.applyCoupon');
    Route::match(['get','post'],'/checkout','CartController@checkout')->name('checkout');


    Route::get('/order-success','OrderController@orderSuccess');
    Route::get('/invoice/{id}',[OrderController::class, 'generatePDF'])->name('order.invoice');
    Route::get('/download-invoice/{id}','OrderController@downloadPDF');
    Route::get('/daftar-pesanan',[OrderController::class,'orders'])->name('order.list');
    Route::get('/pay', [PaymentController::class, 'createTransaction']);
    Route::post('/payment/notification', [PaymentController::class, 'notification']);

	// Page
    Route::get('/visi-misi', [PageController::class,'visiMisi'])->name('visi-misi');

    Route::get('/kontak-kami', [PageController::class,'kontak'])->name('kontak-kami');

    Route::post('/kontak-kami/kirim', [PageController::class, 'kirimKontak'])->name('kontak.kirim');

    Route::get('/struktur-organisasi', [PageController::class,'strukturOrganisasi'])->name('struktur-organisasi');
    Route::get('/faq', [PageController::class,'faq'])->name('faq');
    Route::get('/kebijakan-cookies', [PageController::class,'cookies'])->name('cookies');

     // Listing Page Route
    $catUrls = CmsPage::select('url')->where('status','ready')->get()->pluck('url');
    foreach($catUrls as $key => $url){
        Route::get($url,'PageController@show');
    } 
});

// Frontend Routes

Route::get('/informasi', [InformationController::class, 'index'])->name('informasi.index');
Route::get('/informasi/{slug}', [InformationController::class, 'show'])->name('informasi.show');

Route::get('rooms', [RoomFrontController::class,'index'])->name('rooms.index');
Route::get('rooms/{room}', [RoomFrontController::class,'show'])->name('rooms.show');

Auth::routes(['verify' => true]);
// User Route
Route::get('/akun', [UserController::class, 'index'])->name('akun');
Route::post('/newsletter/store', [NewsletterController::class, 'store'])->name('newsletter.store');

// Admin Route
Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->group(function(){

    Route::resource('roles', AdminRoleController::class);
    Route::get('rooms', [RoomController::class,'index'])->name('rooms.index');
    Route::get('rooms/create', [RoomController::class,'create'])->name('rooms.create');
    Route::post('rooms', [RoomController::class,'store'])->name('rooms.store');
    Route::get('rooms/{room}/edit', [RoomController::class,'edit'])->name('rooms.edit');
    Route::put('rooms/{room}', [RoomController::class,'update'])->name('rooms.update');
    Route::delete('rooms/{room}', [RoomController::class,'destroy'])->name('rooms.destroy');



    Route::match(['get','post'],'login','AdminController@login')->name('admin.login');
    Route::group(['middleware'=>['admin']], function(){
        Route::get('dashboard','DashboardController@dashboard')->name('admin.dashboard');
        Route::match(['get','post'],'update-password','AdminController@updatePassword');
        Route::match(['get','post'],'update-detail','AdminController@updateDetail');
        Route::post('check-current-password','AdminController@checkCurrentPassword');
        Route::get('logout','AdminController@logout');


        Route::get('employees', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('employees/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('employees', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::put('employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
        
        // Rout CMS
        Route::get('cms-pages','CmsController@index');
        Route::post('update-cms-page-status','CmsController@update');
        Route::match(['get','post'],'add-edit-cms-page/{id?}','CmsController@edit');
        Route::get('delete-cms-page/{id?}','CmsController@destroy');
        
        // Subadmins
        Route::get('subadmins','AdminController@subadmins');
        Route::post('update-subadmin-status','AdminController@updateSubadminStatus');
        Route::match(['get','post'],'add-subadmin/{id?}','AdminController@edit');
        Route::get('delete-subadmin/{id?}','AdminController@deleteSubAdmin');

        // Roles Permissions
        Route::match(['get','post'],'update-role/{id?}','AdminController@updateRole');

        // Categories
        Route::get('categories', 'CategoryController@categories');
        Route::post('update-category-status','CategoryController@updateStatus');
        Route::get('delete-category/{id?}','CategoryController@deleteCategory');
        Route::get('delete-category-image/{id?}','CategoryController@deleteCategoryImage');
        Route::match(['get','post'],'add-edit-category/{id?}','CategoryController@edit');

        // Products
        Route::get('products', 'ProductController@products');
        Route::post('update-product-status','ProductController@updateStatus');
        Route::get('delete-product/{id?}','ProductController@deleteProduct');
        Route::get('delete-product-image/{id?}','ProductController@deleteProductImage');

        Route::get('delete-product-image-slide/{id?}','ProductController@deleteProductImageSlide');
        Route::match(['get','post'],'add-edit-product/{id?}','ProductController@edit');

        // Banners
        Route::get('banners', 'BannerController@banners');
        Route::post('update-banner-status','BannerController@updateStatus');
        Route::get('delete-banner/{id?}','BannerController@deleteBanner');
        Route::get('delete-banner-image/{id?}','BannerController@deleteBannerImage');
        Route::match(['get','post'],'add-edit-banner/{id?}','BannerController@edit');

		// Account Bank
        Route::get('account-banks', 'AccountBankController@accountbanks');
        Route::post('update-account-bank-status','AccountBankController@updateStatus');
        Route::get('delete-account-bank/{id?}','AccountBankController@deleteAccountBank');
        Route::get('delete-account-bank-icon/{id?}','AccountBankController@deleteAccountBankImage');
        Route::match(['get','post'],'add-edit-account-bank/{id?}','AccountBankController@edit');

		// Coupons
        Route::get('coupons', 'CouponController@coupons');
        Route::post('update-coupon-status','CouponController@updateStatus');
        Route::get('delete-coupon/{id?}','CouponController@deletCoupon');
        Route::match(['get','post'],'add-edit-coupon/{id?}','CouponController@edit');

        // Customer
        Route::get('customers', 'UserController@users');

		// Orders 
        Route::get('/orders','OrderController@index')->name('admin.orders.index');
        Route::post('/check-availability', 'OrderController@checkAvailability');
        Route::post('get-price', 'OrderController@getPrice');

        Route::post('/add-order','OrderController@store');
        Route::get('/order-details/{id}','OrderController@show')->name('admin.orders.show');
        Route::get('/edit-order/{id}','OrderController@edit')->name('admin.orders.edit');
        Route::put('/order/{order}', 'OrderController@update')->name('admin.orders.update');
        // 🔒 Route untuk melihat bukti pembayaran dari storage privat
        Route::get('/orders/{id}/payment-proof', 'OrderController@showPaymentProof')
        ->name('admin.orders.paymentProof');
        Route::get('admin/orders/export', 'OrderController@export')->name('admin.orders.export');
        Route::get('admin/orders/export-pdf', 'OrderController@exportPdf')
     ->name('admin.orders.exportPdf');


        Route::get('/calendar', [CalendarController::class, 'index'])->name('admin.calendar.index');
        Route::get('/calendar/events', [CalendarController::class, 'events'])->name('admin.calendar.events');
        Route::get('/orders/{id}/detail', [CalendarController::class, 'detailAjax'])->name('orders.detailAjax');

         Route::get('files', [FileController::class, 'index'])->name('admin.files.index');
        Route::post('files/upload', [FileController::class, 'upload'])->name('admin.files.upload');
        Route::post('files/folder', [FileController::class, 'createFolder'])->name('admin.files.folder');
        Route::post('files/rename', [FileController::class, 'rename'])->name('admin.files.rename');
        Route::post('files/move', [FileController::class, 'move'])->name('admin.files.move');
        Route::get('files/download/{folder?}/{filename}', [FileController::class, 'download'])->name('admin.files.download');
        Route::delete('files/delete', [FileController::class, 'delete'])->name('admin.files.delete');
        Route::get('files/search', [FileController::class, 'search'])->name('admin.files.search');
        Route::get('system', [SystemInfoController::class, 'index'])
        ->name('admin.system');
        Route::get('system/download', [SystemInfoController::class, 'download'])->name('admin.system.download');

        Route::get('database', [DatabaseController::class, 'index'])->name('admin.database');
        Route::get('database/backup', [DatabaseController::class, 'backup'])->name('admin.database.backup');
        Route::post('database/restore', [DatabaseController::class, 'restore'])->name('admin.database.restore');
        Route::get('database/download/{fileName}', [DatabaseController::class, 'download'])
    ->name('admin.database.download');

        // routes/web.php
        Route::get('/email-logs', function () {
            $logs = \App\Models\EmailLog::latest()->paginate(20);
            return view('admin.email_logs.index', compact('logs'));
        });
    });

});



Route::get('/sitemap', function () {
    $sitemap = Sitemap::create()
    ->add(Url::create('/')
        ->setLastModificationDate(Carbon::yesterday())
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
        ->setPriority(0.1))
    ->add(Url::create('/visi-misi'))
    ->add(Url::create('/struktur-organisasi'))
    ->add(Url::create('/kontak-kami'))
    ->add(Url::create('/register'))
    ->add(Url::create('/login'))
    ->add(Url::create('/tentang-kami'))
    ->add(Url::create('/kebijakan-cookies'));

    $cats = Category::all()->each(function(Category $category) use ($sitemap){
        $url = $category->url;
        $sitemap->add(Url::create('/kategori/'.$url)
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            ->setPriority(0.1));
    });
    $sitemap->writeToFile(public_path('sitemap.xml'));
    return 'susses';
});