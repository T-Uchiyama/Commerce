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
Route::get('/product_info/edit/{id}', 'HomeController@showProductEditForm')->name('master.edit');
Route::post('/product_info/edit/{id}', 'HomeController@ProductMasterEdit');
Route::post('/csv', 'HomeController@downloadCSV')->name('csv');
Route::get('/import', 'HomeController@showInportForm')->name('import');
Route::post('/import', 'HomeController@import');

Route::get('/product', 'ProductController@index')->name('product');
Route::post('/product/upload', 'ProductController@upload');
Route::get('/product/edit/{id}', 'ProductController@getEdit')->name('edit');
Route::post('/product/edit/{id}', 'ProductController@edit');

Route::get('/display', 'DisplayController@index')->name('display');
Route::get('/display/detail/{id}', 'DisplayController@getDetail');
Route::post('/display/shoppingcart/{id}', 'DisplayController@addShoppingCart');
Route::post('/display/purchase/{id}', 'DisplayController@purchase');
Route::post('/display/emptyCart/{id}', 'DisplayController@emptyCart');
Route::get('/display/cart', 'DisplayController@getCart')->name('cart');
Route::get('/display/checkout', 'DisplayController@getCheckout')->name('checkout');
Route::post('/display/checkout', 'DisplayController@purchase');
Route::post('/display/changeCartNum', 'DisplayController@changeCartNum');
Route::get('/display/search', 'DisplayController@getQuerySearch')->name('search');

Route::get('/regist_member', 'DisplayController@getRegistMember')->name('regist_member');

Route::get('/confirm_facebook', 'Auth\LoginController@confirm_facebook');
Route::get('/confirm_twitter', 'Auth\LoginController@confirm_twitter');
Route::get('/confirm_google', 'Auth\LoginController@confirm_google');

Route::get('/admin_login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
Route::post('/admin_login', 'Auth\AdminLoginController@login');
Route::post('/admin_logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
Route::get('/admin_register', 'Auth\AdminRegisterController@showRegistrationForm')->name('admin.register');
Route::post('/admin_register', 'Auth\AdminRegisterController@register');