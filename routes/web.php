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

Route::get('/product', 'ProductController@index')->name('product');
Route::post('/product/upload', 'ProductController@upload');

Route::get('/display', 'DisplayController@index')->name('display');
Route::get('/display/shop/{id}', 'DisplayController@shop');
Route::post('/display/shop/{id}', 'DisplayController@shop');
Route::post('/display/purchase/{id}', 'DisplayController@purchase');
Route::get('/display/emptyCart/{id}', 'DisplayController@emptyCart');