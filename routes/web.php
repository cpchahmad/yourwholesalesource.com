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

use Illuminate\Support\Facades\Redirect;

Route::get('/', function () {
    return view('welcome');
})->name('admin.dashboard');
Route::get('/categories','CategoryController@index')->name('category.create');
Route::post('/categories/save','CategoryController@save')->name('category.save');
Route::any('/categories/{id}/update','CategoryController@update')->name('category.update');
Route::get('/categories/{id}/delete','CategoryController@delete')->name('category.delete');

Route::post('/subcategory/save','CategoryController@subsave')->name('sub.save');
Route::any('/subcategory/{id}/update','CategoryController@subupdate')->name('sub.update');
Route::get('/subcategory/{id}/delete','CategoryController@subdelete')->name('sub.delete');

Route::get('/products','ProductController@index')->name('product.create');
Route::get('/products/all','ProductController@all')->name('product.all');
Route::any('/products/{id}/view','ProductController@view')->name('product.view');
Route::any('/products/{id}/edit','ProductController@edit')->name('product.edit');
Route::any('/products/{id}/update','ProductController@update')->name('product.update');
Route::post('/products/save','ProductController@save')->name('product.save');
Route::post('/products/variant/save','ProductController@variant')->name('product.variant');
Route::get('/products/{id}/delete','ProductController@delete')->name('product.delete');
Route::get('/products/{id}/varaints/new','ProductController@add_existing_product_new_variants')->name('product.existing_product_new_variants');
Route::get('/products-tabs/{id}/delete','ProductController@delete_tab')->name('product.tab.delete');

Route::get('/default/settings','DefaultSettingsController@index')->name('default_info');
Route::post('/default/settings/save','DefaultSettingsController@save')->name('default_info.save');
Route::any('/default/settings/{id}/update','DefaultSettingsController@update')->name('default_info.update');

Route::post('/create/platform','DefaultSettingsController@create_platform')->name('create_platform');
Route::post('/update/platform/{id}','DefaultSettingsController@update_platform')->name('update_platform');
Route::get('/delete/platform/{id}','DefaultSettingsController@delete_platform')->name('delete_platform');

Route::get('/zones','ZoneController@index')->name('zone.index');
Route::post('/zones','ZoneController@create')->name('zone.create');
Route::post('/zone/{id}/update','ZoneController@update')->name('zone.update');
Route::any('/zone/{id}/delete','ZoneController@delete')->name('zone.delete');



Route::post('/zone/rate/{id}','ZoneController@rate_create')->name('zone.rate.create');
Route::post('/zone/rate/{id}/update','ZoneController@rate_update')->name('zone.rate.update');
Route::any('/zone/rate/{id}/delete','ZoneController@rate_delete')->name('zone.rate.delete');

Route::get('/sales-managers','DefaultSettingsController@show_sales_managers')->name('sales-managers.index');
Route::post('/sales-managers','DefaultSettingsController@create_manager')->name('sales-managers.create');
Route::any('/sales-managers/{id}/delete','DefaultSettingsController@delete_manager')->name('sales-managers.delete');
Route::any('/sales-managers/{id}/set','DefaultSettingsController@set_manager_as_user')->name('sales-managers.set_manager_as_user');

Route::get('/push/{id}/to-store','ProductController@import_to_shopify')->name('import_to_shopify');



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/logout', function(){
    Auth::logout();
    return Redirect::to('login');
})->name('logout');

Route::group(['middleware' => ['auth']], function () {
    /*Checking User Role*/
    Route::get('/check/roles','RolePermissionController@check_roles')->name('system.check-roles');
    Route::get('/choose/platform','RolePermissionController@selection')->name('system.selection');

    Route::group(['middleware' => ['role:non-shopify-users']], function () {
        Route::prefix('users')->group(function () {
           Route::get('/home',function (){
              return view('non_shopify_users.index');
           })->name('users.dashboard');
        });
    });

    Route::group(['middleware' => ['role:sales-manager']], function () {
        Route::prefix('managers')->group(function () {
            Route::get('/home',function (){
                return view('sales_managers.index');
            })->name('managers.dashboard');;
        });
    });

});


Route::get('/getShippingRates', 'ZoneController@getShippingRates');
Route::get('/getExportFile', 'ProductController@getExportFile');
