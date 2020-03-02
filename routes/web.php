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
Route::get('/categories','CategoryController@index')->name('category.create');
Route::post('/categories/save','CategoryController@save')->name('category.save');
Route::any('/categories/{id}/update','CategoryController@update')->name('category.update');
Route::get('/categories/{id}/delete','CategoryController@delete')->name('category.delete');

Route::post('/subcategory/save','CategoryController@subsave')->name('sub.save');
Route::any('/subcategory/{id}/update','CategoryController@subupdate')->name('sub.update');
Route::get('/subcategory/{id}/delete','CategoryController@subdelete')->name('sub.delete');

Route::get('/products','ProductController@index')->name('product.create');
Route::get('/products/all','ProductController@all')->name('product.all');
Route::any('/products/{id}/edit','ProductController@edit')->name('product.edit');
Route::any('/products/{id}/update','ProductController@update')->name('product.update');
Route::post('/products/save','ProductController@save')->name('product.save');
Route::post('/products/variant/save','ProductController@variant')->name('product.variant');
Route::get('/products/{id}/delete','ProductController@delete')->name('product.delete');

Route::get('/default/settings','DefaultSettingsController@index')->name('default_info');
Route::post('/default/settings/save','DefaultSettingsController@save')->name('default_info.save');
Route::any('/default/settings/{id}/update','DefaultSettingsController@update')->name('default_info.update');

Route::get('/zones','ZoneController@index')->name('zone.index');
Route::post('/zones','ZoneController@create')->name('zone.create');
Route::post('/zone/{id}/update','ZoneController@update')->name('zone.update');
Route::any('/zone/{id}/delete','ZoneController@delete')->name('zone.delete');



Route::post('/zone/rate/{id}','ZoneController@rate_create')->name('zone.rate.create');
Route::post('/zone/rate/{id}/update','ZoneController@rate_update')->name('zone.rate.update');
Route::any('/zone/rate/{id}/delete','ZoneController@rate_delete')->name('zone.rate.delete');

