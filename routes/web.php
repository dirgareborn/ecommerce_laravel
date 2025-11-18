<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\CategoryController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\UserController;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\Front\ProductRatingController;
use App\Models\Category;
use App\Models\CmsPage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Front\CartController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Front\InformationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

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
Auth::routes(['verify' => true]);
require __DIR__.'/admin.php';
require __DIR__.'/guest.php';

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

       //Cart
    Route::post('/addCart', 'CartController@addToCart');
    Route::get('/cart','CartController@index');
    Route::get('/cart/delete-cart-item/{id?}','CartController@delete')->name('cart.delete');
    Route::post('/apply-coupon','CartController@applyCoupon')->name('cart.applyCoupon');
    Route::match(['get','post'],'/checkout','CartController@checkout')->name('checkout');


    Route::get('/order-success','OrderController@orderSuccess');
    Route::get('/invoice/{id}',[OrderController::class, 'generatePDF'])->name('order.invoice');
    Route::get('/download-invoice/{id}','OrderController@downloadPDF');
    Route::get('/pay', [PaymentController::class, 'createTransaction']);
    Route::post('/payment/notification', [PaymentController::class, 'notification']);
    
    Route::post('product/{product}/rate', [ProductRatingController::class, 'rate'])->name('product.rate');
   // Untuk Member Area
    Route::get('/member/akun','UserController@account')->name('member.akun');
    Route::get('/member/profil','UserController@profil')->name('member.profil');
    Route::get('/member/pesanan',[OrderController::class,'orders'])->name('member.pesanan');
    Route::match(['get','post'],'/member/testimoni','UserController@testimonial')->name('member.testimoni');
    Route::match(['get','post'],'update-password','UserController@updatePassword');
    Route::post('update-detail','UserController@updateDetail')->name('update.profil');
    Route::post('check-current-password','UserController@checkCurrentPassword');
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
    Route::get('/akun', [UserController::class, 'index'])->name('akun');

   // End Member Area

    Route::post('informasi/{information}/comment', [InformationController::class, 'comment'])->name('informasi.comment');
    Route::post('informasi/{information}/reply', [InformationController::class, 'reply'])->name('informasi.reply');

});



