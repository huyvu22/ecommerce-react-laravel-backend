<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthVendorController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductReviewController;
use App\Http\Controllers\Api\ShippingFeeController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\SocialiteLoginController;
use App\Http\Controllers\Api\UserAddressController;
use App\Http\Controllers\Api\VendorAddressController;
use App\Http\Controllers\Api\VendorOrderedProductController;
use App\Http\Controllers\Api\VendorProductController;
use App\Http\Controllers\Api\WishListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Public Route
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('forgot', [AuthController::class, 'forgot']);

//Login with Google
Route::get('login/google', [SocialiteLoginController::class, 'redirectToGoogle']);
Route::get('login/google/callback', [SocialiteLoginController::class, 'handleGoogleCallback']);

Route::post('seller/register', [AuthVendorController::class, 'register']);

Route::get('province', [UserAddressController::class, 'getProvince']);
Route::get('district/{provinceId}', [UserAddressController::class, 'getDistrict']);
Route::get('ward/{districtId}', [UserAddressController::class, 'getWard']);


//Protected Route
Route::group(['middleware' => ['auth:sanctum']], function (){

    /*CheckToken Route*/
    Route::post('/check-token', [AuthController::class, 'checkTokenLogin']);
    Route::post('/check-remember', [AuthController::class, 'checkRememberLogin']);

    /*Authenticate Route*/
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post( 'change-password',[AuthController::class, 'changePassword']);

   /*Address Route*/
    Route::resource( 'address',UserAddressController::class);
    Route::resource( 'seller/address',VendorAddressController::class);

    Route::post( 'shipping-fee',[ShippingFeeController::class, 'shippingFee']);

    /*Payment Route*/
    Route::get('payment',[PaymentController::class,'index']);
    Route::get('payment-success',[PaymentController::class,'paymentSuccess']);

    /*PayPal Route*/
    Route::get('paypal/payment',[PaymentController::class,'connectPaypalPayment']);
    Route::get('paypal/success',[PaymentController::class,'paypalPaymentSuccess']);
    Route::get('paypal/cancel',[PaymentController::class,'paypalPaymentCancel']);
    Route::post('cart-list',[PaymentController::class,'getCartList']);

    /*VnPay Route*/
    Route::post('vnpay/payment',[PaymentController::class,'connectVnPayPayment']);
    Route::get('vnpay/checkout',[PaymentController::class,'vnPayCheck']);

    /*COD Route*/
    Route::get('cod/payment',[PaymentController::class,'payWithCod']);

    /*Order Route*/
    Route::get('orders',[OrderController::class, 'orders']);
    Route::get('orders/detail/{orderId}',[OrderController::class, 'orderDetail']);

//    Route::prefix('seller/orders')->group(function () {
//        Route::get('/detail/{orderId}',[OrderController::class, 'vendorOrderDetail']);
//        Route::get('/{orderId}/status',[OrderController::class, 'vendorOrderShortBy']);
//    });


    /*Vendor Product Route*/
    Route::prefix('seller')->group(function () {
        Route::resource('/products',VendorProductController::class);
        Route::get('/ordered-products',[VendorOrderedProductController::class, 'index']);
        Route::get('orders/detail/{orderId}',[VendorOrderedProductController::class, 'orderDetail']);
        Route::get('orders/{status}',[VendorOrderedProductController::class, 'orderShortBy']);
        Route::post('set-order-status/{orderId}/{status}',[VendorOrderedProductController::class, 'setOrderStatus']);
    });

    /*Product Review Route*/
    Route::get('product-review/{productId}',[ProductReviewController::class, 'showReview']);
    Route::post('product-review',[ProductReviewController::class, 'store']);

    /*WishList Route*/
    Route::resource( 'wishlist',WishListController::class);
});


Route::get('sliders',[SliderController::class,'sliders']);
Route::get('category',[CategoryController::class,'category']);
Route::get('/coupon/{code}', [CouponController::class,'applyCoupon']);


Route::prefix('products')->group(function () {
//    Route::get('/', [ProductController::class, 'products']);
    Route::get('/vendor-products/{id}', [ProductController::class,'vendorProducts']);
    Route::get('/search/{keyword}', [ProductController::class, 'searchProduct']);
    Route::get('/product-type/{type}', [ProductController::class, 'productType']);
    Route::get('/category/{slug}', [ProductController::class, 'productsByCategory']);
    Route::get('/sub-category/{slug}', [ProductController::class, 'productsBySubCategory']);
    Route::get('/{id}/{slug}', [ProductController::class, 'showProduct']);
    Route::get('price-range/{min}/{max}', [ProductController::class,'filterPrice']);
});

/*Route Newsletter*/
Route::post('newsletter',[NewsletterController::class, 'newsLetter']);
Route::get('newsletter-verify/{token}',[NewsletterController::class, 'newsLetterEmailVerify'])->name('newsletter-verify');

/*Route specific Page*/
Route::get('about-us',[PageController::class, 'about']);
Route::get('terms-and-condition',[PageController::class, 'termsAndCondition']);
Route::get('privacy-policy',[PageController::class, 'privacyPolicy']);

Route::get('contact',[PageController::class, 'contact']);
Route::post('send-contact',[PageController::class, 'postContactForm']);
Route::get('faqs',[PageController::class, 'faqs']);

