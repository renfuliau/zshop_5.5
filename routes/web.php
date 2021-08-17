<?php

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

Route::get('/', function () {
    return view('welcome');
});

// ZShop

Route::group(['prefix' => 'zshop'], function () {
    Route::get('/', 'FrontendController@index')->name('index');

    // 會員註冊 登入 忘記密碼 聯絡客服
    // Route::get('/login-register', 'FrontendController@loginRegister')->name('login-register');
    Route::get('/login', 'FrontendController@login')->name('login');
    Route::post('/login', 'FrontendController@loginSubmit')->name('login-submit');
    Route::get('/register', 'FrontendController@register')->name('register');
    Route::post('/register', 'FrontendController@registerSubmit')->name('register-submit');
    Route::get('/forget-password', 'FrontendController@forgetPassword')->name('forget-password');
    Route::get('/contact', 'FrontendController@contact')->name('contact');
    // Route::post('/contact/message', 'MessageController@store')->name('contact.store');

    // 商品搜尋 商品分類 商品介紹
    Route::get('/productlist', 'FrontendController@productlist')->name('productlist');
    Route::get('/productlist-category/{slug}', 'FrontendController@productlistByCategory')->name('productlist-category');
    Route::get('/productlist-category/{slug}/{sub_slug}', 'FrontendController@productSubcategory')->name('productlist-subcategory');
    Route::get('/product-detail/{slug}', 'FrontendController@productDetail')->name('product-detail');
    Route::post('/product/search', 'FrontendController@productSearch')->name('product.search');
    Route::get('/product-brand/{slug}', 'FrontendController@productBrand')->name('product-brand');

    // 購物車
    Route::get('/cart', 'FrontendController@cart')->name('cart');
    Route::get('/checkout', 'FrontendController@checkout')->name('checkout')->middleware('user');
    Route::post('/checkout/store', 'FrontendController@checkoutStore')->name('checkoutStore');
    Route::get('/add-to-cart/{slug}', 'FrontendController@addToCart')->name('add-to-cart')->middleware('user');
    Route::post('/add-to-cart', 'FrontendController@singleAddToCart')->name('single-add-to-cart')->middleware('user');
    Route::get('cart-delete/{id}', 'FrontendController@cartDelete')->name('cart-delete');
    Route::post('cart-update', 'FrontendController@cartUpdate')->name('cart.update');
});

Route::group(['prefix' => 'zshop/user'], function () {
    // 個人中心 購物金 訂單查詢 退貨查詢 收藏清單 問答中心
    Route::get('/home', 'FrontendController@home')->name('user-home');
    Route::post('/profile/{id}', 'FrontendController@profileUpdate')->name('user-profile-update');
    Route::get('/change-password', 'FrontendController@changePassword')->name('user-change-password');
    Route::post('/change-password', 'FrontendController@changPasswordStore')->name('user-change-password-update');

    Route::get('/reward-money', 'FrontendController@rewardMoney')->name('user-reward-money');

    Route::get('/orders', 'FrontendController@orders')->name('user-orders');

    Route::get('/returned', 'FrontendController@returned')->name('user-returned');

    Route::get('/wishlist', 'FrontendController@wishlist')->name('user-wishlist');
    Route::get('/wishlist/{slug}', 'FrontendController@addToWishlist')->name('add-to-wishlist')->middleware('user');
    Route::get('/wishlist-delete/{id}', 'FrontendController@wishlistDelete')->name('wishlist-delete');

    Route::get('/qa-center', 'FrontendController@qaCenter')->name('user-qa-center');
});
