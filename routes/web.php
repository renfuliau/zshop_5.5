<?php

use App\Http\Controllers\UserController;
use \Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'FrontendController@index');

Route::get('/test', function () {
    // App::setLocale('en');
    return __('auth.failed');
});

// ZShop

Route::group(['prefix' => 'zshop', 'middleware' => ['language']], function () {
    Route::get('/', 'FrontendController@index')->name('index');
    Route::get('/locale-change', 'FrontendController@locale')->name('locale-change');
    // 會員註冊 登入 忘記密碼 聯絡客服
    Route::get('/login', 'FrontendController@login')->name('z-login');
    Route::post('/login', 'FrontendController@loginSubmit')->name('login-submit');
    Route::get('/register', 'FrontendController@register')->name('z-register');
    Route::post('/register', 'FrontendController@registerSubmit')->name('register-submit');
    Route::get('/logout', 'FrontendController@logout')->name('z-logout');
    Route::get('/forget-password', 'FrontendController@forgetPassword')->name('forget-password');
    Route::get('/contact', 'FrontendController@contact')->name('contact');
    Route::post('/contact-store', 'MessageController@messageStore')->name('contact-store');

    // 商品搜尋 商品分類 商品介紹
    Route::get('/product', 'ProductController@productlistByCategory')->name('productlist');
    Route::get('/productlist-category/{slug}', 'ProductController@productlistByCategory')->name('productlist-category');
    Route::get('/productlist-category/{slug}/{sub_slug}', 'ProductController@productSubcategory')->name('productlist-subcategory');
    Route::get('/product-detail/{slug}', 'ProductController@productDetail')->name('product-detail');
    Route::post('/product/search', 'ProductController@productSearch')->name('product-search');
    Route::get('/product-brand/{slug}', 'ProductController@productBrand')->name('product-brand');

    //購物車
    Route::post('/add-to-cart', 'CartController@addToCart')->name('add-to-cart')->middleware('user');
    Route::get('/cart', 'CartController@cart')->name('cart')->middleware('user'); 
    Route::post('/change-product-qty', 'CartController@changeProductQty')->name('change-qty')->middleware('user'); 
    Route::post('/remove_item', 'CartController@removeItem')->name('remove-cart-item')->middleware('user');
    Route::post('/change-reward-money', 'CartController@changeRewardMoney')->middleware('user');
    Route::post('/change-coupon', 'CartController@changeCoupon')->middleware('user');
    Route::post('/checkout', 'CartController@checkout')->name('checkout')->middleware('user');
    Route::post('/checkout/store', 'CartController@checkoutStore')->name('checkout-store')->middleware('user');
    Route::get('/order-confirm', 'CartController@orderConfirm')->name('order-confirm')->middleware('user');
});

Route::group(['prefix' => 'zshop/user', 'middleware' => ['user', 'language']], function () {
    // 個人中心 購物金 訂單查詢 退貨查詢 收藏清單 問答中心
    Route::get('/profile', 'UserController@profile')->name('user-profile');
    Route::post('/profile/{id}', 'UserController@profileUpdate')->name('user-profile-update');
    Route::get('/change-password', 'UserController@changePassword')->name('user-change-password');
    Route::post('/change-password', 'UserController@changPasswordStore')->name('user-change-password-update');

    Route::get('/reward-money', 'UserController@rewardMoney')->name('user-reward-money');

    Route::get('/orders', 'UserController@orders')->name('user-orders');
    Route::get('/order-detail/{order_number}', 'UserController@orderDetail')->name('user-order-detail');
    Route::post('/order-message-store', 'UserController@orderMessageStore')->name('order-message-store');
    Route::post('/order-received', 'UserController@orderReceived');
    Route::post('/order-cancel', 'UserController@orderCancel');
    Route::get('/order-return/{order_id}/{order_number}', 'UserController@orderReturn')->name('order-return');
    Route::post('/order-return-store', 'UserController@orderReturnStore')->name('order-return-store');

    Route::get('/returned', 'UserController@returned')->name('user-returned');

    Route::get('/wishlist', 'UserController@wishlist')->name('user-wishlist');
    Route::post('/add-to-wishlist', 'UserController@addToWishlist')->name('add-to-wishlist');
    Route::post('/remove-wishlist', 'UserController@removeWishlist')->name('remove-wishlist');

    Route::get('/qa-center', 'UserController@qaCenter')->name('user-qa-center');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
