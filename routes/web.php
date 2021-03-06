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

Route::redirect('/', '/items', 301);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/items', 'ItemController');

Route::get('/cart', 'CartController@index');
Route::post('/cart/add', 'CartController@add');
Route::post('/cart/delete/{id}', 'CartController@delete');
Route::post('/cart/update/{id}', 'CartController@update');
