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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/user_info', 'HomeController@userInfo')->name('user_info');
Route::get('/product_info', 'HomeController@productInfo')->name('product_info');

Route::get('/product', 'ProductController@index')->name('product');
Route::post('/product/upload', 'ProductController@upload');

Route::get('/display', 'DisplayController@index')->name('display');
Route::get('/display/detail/{id}', 'DisplayController@getDetail');
Route::get('/display/shop/{id}', 'DisplayController@shop');
Route::post('/display/shop/{id}', 'DisplayController@shop');
Route::post('/display/shoppingcart/{id}', 'DisplayController@addShoppingCart');
Route::post('/display/purchase/{id}', 'DisplayController@purchase');
Route::post('/display/emptyCart/{id}', 'DisplayController@emptyCart');
Route::get('/display/cart', 'DisplayController@getCart')->name('cart');

Route::get('/regist_member', 'DisplayController@getRegistMember')->name('regist_member');

Route::get('/confirm_facebook', 'Auth\LoginController@confirm_facebook');
Route::get('/confirm_twitter', 'Auth\LoginController@confirm_twitter');
Route::get('/confirm_google', 'Auth\LoginController@confirm_google');

Route::get('/admin_login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
Route::post('/admin_login', 'Auth\AdminLoginController@login');
Route::post('/admin_logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
Route::get('/admin_register', 'Auth\AdminRegisterController@showRegistrationForm')->name('admin.register');
Route::post('/admin_register', 'Auth\AdminRegisterController@register');