<?php

use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Backend\AboutController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\AdminListController;
use App\Http\Controllers\Backend\AdminReviewController;
use App\Http\Controllers\Backend\AdminVendorProfileController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CodSettingController;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\CustomerListController;
use App\Http\Controllers\Backend\FooterColumnThreeController;
use App\Http\Controllers\Backend\FooterColumnTwoController;
use App\Http\Controllers\Backend\FooterInfoController;
use App\Http\Controllers\Backend\FQAController;
use App\Http\Controllers\Backend\ManageUserController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\PaymentSettingController;
use App\Http\Controllers\Backend\PaypalSettingController;
use App\Http\Controllers\Backend\PrivacyPolicyController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\ShippingRuleController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Backend\SubscribeController;
use App\Http\Controllers\Backend\TermsAndConditionController;
use App\Http\Controllers\Backend\TransactionController;
use App\Http\Controllers\Backend\VendorController;
use App\Http\Controllers\Backend\VendorListController;
use Illuminate\Support\Facades\Route;

//use App\Http\Controllers\ProfileController;

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

//Route::get('/', function () {
//    return view('welcome');
//});
//
//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

//Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});

require __DIR__.'/auth.php';

Route::get('admin/login',[AdminController::class, 'login'])->name('admin.login');


Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    //Profile Route
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');

    Route::post('profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');

    //AdminVendorProfile Route
    Route::get('vendor-profile', [AdminVendorProfileController::class, 'index'])->name('vendor-profile.index');
    Route::post('vendor-profile', [AdminVendorProfileController::class, 'store'])->name('vendor-profile.store');

    //Slider Route
    Route::resource('slider', SliderController::class);

    //Category Route
    Route::resource('category', CategoryController::class);

    //SubCategory Route
    Route::resource('sub-category', SubCategoryController::class);

    //Product Route
    Route::get('products/get-sub-category/{categoryId}', [ProductController::class,'getSubCategory'])->name('get-sub-category');
    Route::resource('products', ProductController::class);

    /* Coupon Route*/
    Route::resource('coupon',CouponController::class);

    /* Shipping Route*/
    Route::resource('shipping-rule',ShippingRuleController::class);

    /* All Payments Route*/
    Route::get('payment-settings',[PaymentSettingController::class,'index'])->name('payment-settings.index');
    Route::put('paypal-setting/{id}',[PaypalSettingController::class,'update'])->name('paypal-setting.update');
    Route::put('cod/{id}',[CodSettingController::class,'update'])->name('cod-setting.update');

    /* Get Status Order Route*/
    Route::get('order/get-order-status/{id}/{status}',[OrderController::class,'getOrderStatus'])->name('get-order-status');

    /* Get Status Payment Route*/
    Route::get('order/payment-status/{id}/{status}',[OrderController::class,'changePaymentStatus'])->name('payment-status');

    /* Custom Order Route*/
    Route::get('pending-orders',[OrderController::class,'pendingOrders'])->name('pending-orders');
    Route::get('processed-orders',[OrderController::class,'processedOrders'])->name('processed-orders');
    Route::get('drop-off-orders',[OrderController::class,'dropOffOrders'])->name('drop-off-orders');
    Route::get('shipped-orders',[OrderController::class,'shippedOrders'])->name('shipped-orders');
    Route::get('out-of-delivery-orders',[OrderController::class,'outOfDeliveryOrders'])->name('out-of-delivery-orders');
    Route::get('delivered-orders',[OrderController::class,'deliveredOrders'])->name('delivered-orders');
    Route::get('canceled-orders',[OrderController::class,'canceledOrders'])->name('canceled-orders');

    /* Order Route*/
    Route::get('order',[OrderController::class, 'index'])->name('order.index');
    Route::get('order/{order}',[OrderController::class, 'show'])->name('order.show');
    Route::delete('order/{order}',[OrderController::class, 'destroy'])->name('order.destroy');

    /* Transaction Route*/
    Route::get('transaction',[TransactionController::class, 'index'])->name('transaction.index');
    Route::get('orders/show/{order}', [TransactionController::class, 'show'])->name('orders.show');

    /* Get Status Transaction Route*/
    Route::get('orders/get-order-status/{id}/{status}',[TransactionController::class,'getOrderStatus'])->name('get-order-status');

    /* Setting Route*/
    Route::get('settings',[SettingController::class,'index'])->name('setting.index');
    Route::post('email-setting-update',[SettingController::class,'emailConfigSettingUpdate'])->name('email-setting-update');
    Route::post('general-setting',[SettingController::class,'generalSetting'])->name('general-setting');

    /* FAQ Route*/
    Route::resource('faqs',FQAController::class);

    /* About us Route*/
    Route::get('about',[AboutController::class,'index'])->name('about.index');
    Route::put('about',[AboutController::class,'update'])->name('about.update');

    /* Terms and Condition us Route*/
    Route::get('terms-and-condition',[TermsAndConditionController::class,'index'])->name('terms-and-condition.index');
    Route::put('terms-and-condition',[TermsAndConditionController::class,'update'])->name('terms-and-condition.update');

    /* Policy us Route*/
    Route::get('policy',[PrivacyPolicyController::class,'index'])->name('policy.index');
    Route::put('policy',[PrivacyPolicyController::class,'update'])->name('policy.update');

    /* Review Route*/
    Route::get('review',[AdminReviewController::class,'index'])->name('review.index');
    Route::get('review/update-approved/{productId}/{approved}', [AdminReviewController::class,'updateApprove'])->name('review.updateApprove');
    Route::delete('review/{productReview}', [AdminReviewController::class,'destroy'])->name('review.destroy');

    /* Subscriber Route*/
    Route::get('subscriber',[SubscribeController::class,'index'])->name('subscriber.index');
    Route::delete('subscriber/destroy/{id}',[SubscribeController::class,'destroy'])->name('subscriber.destroy');
    Route::post('subscriber-send-mail',[SubscribeController::class,'sendMail'])->name('subscriber-send-mail');


    /* Footer Route*/
    Route::resource('footer-info',FooterInfoController::class);

    Route::put('footer-column-2/change-title',[FooterColumnTwoController::class,'changeTitle'])->name('footer-column-2.change-title');
    Route::resource('footer-column-2',FooterColumnTwoController::class);

    Route::put('footer-column-3/change-title',[FooterColumnThreeController::class,'changeTitle'])->name('footer-column-3.change-title');
    Route::resource('footer-column-3',FooterColumnThreeController::class);

//    /*Route Newsletter*/
//    Route::get('newsletter-verify/{token}',[NewsletterController::class, 'newsLetterEmailVerify'])->name('newsletter-verify');

    /* Customer list Route*/
    Route::get('customers',[CustomerListController::class,'index'])->name('customers.index');
    Route::put('customers/{user}',[CustomerListController::class,'updateStatus'])->name('customer.update-status');

    /* Admin list Route*/
    Route::get('admin-list',[AdminListController::class,'index'])->name('admin-list.index');
    Route::put('admin-list/{user}',[AdminListController::class,'updateStatus'])->name('admin-list.update-status');
    Route::delete('admin-list/{user}',[AdminListController::class,'destroy'])->name('admin-list.destroy');

    /* Vendor list Route*/
    Route::get('vendors',[VendorListController::class,'index'])->name('vendors.index');
    Route::put('vendors/{vendor}',[VendorListController::class,'updateStatus'])->name('vendors.update-status');

    /* Manage User Route*/
    Route::get('manage-user',[ManageUserController::class,'index'])->name('manage-user.index');
    Route::post('manage-user',[ManageUserController::class,'create'])->name('manage-user.create');


});




//Route::prefix('vendor')->name('vendor.')->middleware(['auth', 'role:vendor'])->group(function () {
//    Route::get('dashboard', [VendorController::class, 'dashboard'])->name('dashboard');
//});
