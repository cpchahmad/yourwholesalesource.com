<?php
/*
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

Route::get('/reset', 'HelperController@reset_all');
Route::get('/reset-retailers', 'HelperController@deleteRetailer');

Route::get('/generate-super-admin', 'HelperController@SuperAdminCreate');
/*Shopify Store Routes*/
Route::get('/getShippingRates', 'ZoneController@getShippingRates');
Route::get('/getExportFile', 'ProductController@getExportFile')->name('app.download.product');
/*Auth Routes*/
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/logout', function(){
    Auth::logout();
    return Redirect::to('login');
})->name('logout');
/*Super Admin Routes*/
Route::group(['middleware' => ['auth.shop','super-admin-store']], function () {
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
    Route::get('/products/{id}/images-position-update','ProductController@update_image_position')->name('product.update_image_position');

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
    Route::get('/sales-manager/create','DefaultSettingsController@show_sales_manager_create')->name('sales-managers.create.form');
    Route::get('/sales-manager/edit/{id}','DefaultSettingsController@show_sales_manager_edit')->name('sales-managers.edit.form');
    Route::get('/sales-manager/view/{id}','DefaultSettingsController@show_sales_manager')->name('sales-managers.view');
    Route::get('/sales-manager/create/search','DefaultSettingsController@search_create_content_sale_manager')->name('sales-managers.create.search');
    Route::get('/sales-manager/edit-search','DefaultSettingsController@search_edit_content_sale_manager')->name('sales-managers.edit.search');



    Route::post('/sales-managers','DefaultSettingsController@create_manager')->name('sales-managers.create');
    Route::post('/sales-manager/update/{id}','DefaultSettingsController@update_manager')->name('sales-managers.update');
    Route::any('/sales-managers/{id}/delete','DefaultSettingsController@delete_manager')->name('sales-managers.delete');
    Route::any('/sales-managers/{id}/set','DefaultSettingsController@set_manager_as_user')->name('sales-managers.set_manager_as_user');
    Route::get('/push/{id}/to-store','ProductController@import_to_shopify')->name('import_to_shopify');

    Route::get('/ticket-category','DefaultSettingsController@view_ticket_categories')->name('ticket.category.index');
    Route::post('/ticket-category','DefaultSettingsController@create_ticket_categories')->name('ticket.category.create');
    Route::post('/ticket-category/{id}/update','DefaultSettingsController@update_ticket_categories')->name('ticket.category.update');
    Route::any('/ticket-category/{id}/delete','DefaultSettingsController@delete_ticket_categories')->name('ticket.category.delete');


    Route::get('/orders','AdminOrderController@index')->name('admin.orders');
    Route::get('/orders/view/{id}','AdminOrderController@view_order')->name('admin.order.view');
    Route::get('/orders/view/{id}/fulfillment','AdminOrderController@fulfill_order')->name('admin.order.fulfillment');
    Route::post('/orders/view/{id}/fulfillment/process','AdminOrderController@fulfillment_order')->name('admin.order.fulfillment.process');
    Route::get('/orders/{id}/fulfillment/cancel/{fulfillment_id}','AdminOrderController@fulfillment_cancel_order')->name('admin.order.fulfillment.cancel');
    Route::post('/orders/{id}/fulfillment/tracking','AdminOrderController@fulfillment_add_tracking')->name('admin.order.fulfillment.tracking');
    Route::get('/orders/{id}/mark-as-delivered','AdminOrderController@mark_as_delivered')->name('admin.order.mark_as_delivered');

    /*Admin Wallet Routes*/
    Route::get('/wallets', 'WalletController@index')->name('admin.wallets');
    Route::get('/wallets/{id}', 'WalletController@wallet_details')->name('admin.wallets.detail');
    Route::get('/wallet/request/approve/{id}', 'WalletController@approved_bank_statement')->name('admin.wallets.approve.request');
    Route::post('/wallet/top-up', 'WalletController@topup_wallet_by_admin')->name('admin.user.wallet.topup');




});
/*Single Store Routes*/
Route::group(['middleware' => ['auth.shop']], function () {
    Route::get('/import/{id}/to-store','RetailerProductController@import_to_shopify')->name('retailer.import_to_shopify');

    Route::prefix('store')->group(function () {
        Route::post('/user/authenticate','SingleStoreController@authenticate')->name('store.user.authenticate');
        Route::post('/user/store/association','SingleStoreController@associate')->name('store.user.associate');

        Route::get('/dashboard','SingleStoreController@index')->name('store.dashboard');
        Route::get('/setting','SingleStoreController@setting')->name('store.index');
        Route::get('/products/wefullfill','SingleStoreController@wefullfill_products')->name('store.product.wefulfill');
        Route::get('/products/wefullfill/{id}','SingleStoreController@view_fantasy_product')->name('store.product.wefulfill.show');
        Route::get('/my_products/wefullfill/{id}','SingleStoreController@view_my_product')->name('store.my_product.wefulfill.show');

        /*Import List Route*/
        Route::get('/wefullfill/{id}/add-to-import-list','RetailerProductController@add_to_import_list')->name('store.product.wefulfill.add-to-import-list');
        Route::get('/import-list','RetailerProductController@import_list')->name('store.import_list');
        Route::get('/my_products','RetailerProductController@my_products')->name('store.my_products');
        Route::get('/my_products/{id}','RetailerProductController@edit_my_product')->name('store.my_product.edit');


        Route::get('/products/delete/{id}','RetailerProductController@delete')->name('store.product.delete');
        Route::post('/import-list/{id}/update','RetailerProductController@update')->name('store.import_list.product.update');

        Route::get('/getOrders', 'OrderController@getOrders')->name('store.sync.orders');
        Route::get('/orders', 'OrderController@index')->name('store.orders');
        Route::get('/order/delete/{id}', 'OrderController@delete')->name('store.order.delete');
        Route::get('/order/view/{id}', 'OrderController@view_order')->name('store.order.view');
        Route::post('/order/payment', 'OrderController@proceed_payment')->name('store.order.proceed.payment');
        Route::get('/orders/{id}/mark-as-complete','AdminOrderController@mark_as_completed')->name('admin.order.complete');

        Route::get('/customers', 'SingleStoreController@customers')->name('store.customers');
        Route::get('/customers/{id}', 'SingleStoreController@customer_view')->name('store.customer.view');
        Route::get('/getCustomers', 'SingleStoreController@getCustomers')->name('store.sync.customers');

        Route::get('/payments', 'SingleStoreController@payment_history')->name('store.payments');
        Route::get('/tracking-info', 'SingleStoreController@tracking_info')->name('store.tracking');

        Route::get('/help-center','SingleStoreController@helpcenter')->name('store.help-center');
        Route::get('/help-center/ticket/{id}', 'SingleStoreController@view_ticket')->name('help-center.store.ticket.view');

    });
});
/*Main Routes*/
Route::group(['middleware' => ['auth']], function () {
    /*Checking User Role*/
    Route::get('/check/roles','RolePermissionController@check_roles')->name('system.check-roles');
    Route::get('/choose/platform','RolePermissionController@selection')->name('system.selection');

    /*Store Connection*/
    Route::get('/connect/store','RolePermissionController@store_connect')->name('system.store.connect');
    /*Non-Shopify and Shopify User Routes */

    Route::group(['middleware' => ['role:non-shopify-users']], function () {
        Route::prefix('users')->group(function () {
            Route::get('/user/store/de-association/{id}','SingleStoreController@de_associate')->name('store.user.de-associate');
            Route::get('/home','ShopifyUsersController@index')->name('users.dashboard');
            Route::get('/stores','ShopifyUsersController@stores')->name('users.stores');
            Route::get('/custom-orders','CustomOrderController@index')->name('users.custom.orders');
            Route::get('/custom-orders/create','CustomOrderController@show_create_form')->name('users.custom.orders.create');
            Route::post('/get/shipping', 'CustomOrderController@getShippingRate')->name('users.order.shipping.rate');
            Route::post('/custom-orders/create','CustomOrderController@save_draft_order')->name('users.custom.orders.create.post');
            Route::get('/custom-order/view/{id}', 'CustomOrderController@view_order')->name('users.order.view');
            Route::get('/custom-order/delete/{id}', 'CustomOrderController@delete')->name('users.order.delete');
            Route::post('/order_file_processing', 'CustomOrderController@process_file')->name('order_file_processing');
            Route::get('/files', 'CustomOrderController@files')->name('users.files');
            Route::get('/files/{id}', 'CustomOrderController@file')->name('users.files.view');

            Route::get('/bulk-pay-through-paypal/{id}', 'CustomOrderController@bulk_import_order_paypal')->name('users.orders.bulk.paypal');
            Route::get('/bulk-pay-through-paypal/{id}/cancel', 'CustomOrderController@bulk_import_order_paypal_cancel')->name('users.orders.bulk.paypal.cancel');
            Route::get('/bulk-pay-through-paypal/{id}/success', 'CustomOrderController@bulk_import_order_paypal_success')->name('users.orders.bulk.paypal.success');

            Route::get('/products/wefullfill','CustomOrderController@wefullfill_products')->name('users.product.wefulfill');
            Route::get('/products/wefullfill/{id}','CustomOrderController@view_fantasy_product')->name('users.product.wefulfill.show');

            Route::get('/help-center','CustomOrderController@helpcenter')->name('users.help-center');
            Route::get('/help-center/ticket/{id}', 'CustomOrderController@view_ticket')->name('help-center.users.ticket.view');

            Route::group(['middleware' => ['check_user_shop']], function () {

            });
        });
    });
    /*Sales Manager Routes*/
    Route::group(['middleware' => ['role:sales-manager']], function () {
        Route::prefix('managers')->group(function () {
            Route::get('/tickets','ManagerController@tickets')->name('sales_managers.tickets');
            Route::get('/tickets/{id}', 'ManagerController@view_ticket')->name('sales_managers.ticket.view');

            Route::get('/orders','ManagerController@index')->name('sales_managers.orders');
            Route::get('/orders/view/{id}','ManagerController@view_order')->name('sales_managers.order.view');
            Route::get('/orders/view/{id}/fulfillment','ManagerController@fulfill_order')->name('sales_managers.order.fulfillment');
            Route::post('/orders/view/{id}/fulfillment/process','ManagerController@fulfillment_order')->name('sales_managers.order.fulfillment.process');
            Route::get('/orders/{id}/fulfillment/cancel/{fulfillment_id}','ManagerController@fulfillment_cancel_order')->name('sales_managers.order.fulfillment.cancel');
            Route::post('/orders/{id}/fulfillment/tracking','ManagerController@fulfillment_add_tracking')->name('sales_managers.order.fulfillment.tracking');
            Route::get('/orders/{id}/mark-as-delivered','ManagerController@mark_as_delivered')->name('sales_managers.order.mark_as_delivered');

            Route::get('/stores','ManagerController@stores')->name('sales_managers.stores');
            Route::get('/stores/{id}','ManagerController@store')->name('sales_managers.stores.view');
            Route::get('/products/{id}','ManagerController@product')->name('sales_managers.products.view');
            Route::get('/customers/{id}', 'ManagerController@customer_view')->name('sales_managers.customer.view');


            Route::get('/users','ManagerController@users')->name('sales_managers.users');
            Route::get('/users/{id}','ManagerController@user')->name('sales_managers.users.view');

            Route::get('/settings','ManagerController@view_setting')->name('sales_managers.settings');
            Route::post('/settings/personal','ManagerController@save_personal_info')->name('sales_managers.save_personal_info');
            Route::post('/settings/personal/address','ManagerController@save_address')->name('sales_managers.save_address');
            Route::post('/change/password','ManagerController@change_password')->name('sales_managers.change_password');


            Route::get('/wallets', 'ManagerController@wallet_index')->name('sales_managers.wallets');
            Route::get('/wallets/{id}', 'ManagerController@wallet_details')->name('sales_managers.wallets.detail');
            Route::get('/wallet/request/approve/{id}', 'ManagerController@approved_bank_statement')->name('sales_managers.wallets.approve.request');
            Route::post('/wallet/top-up', 'ManagerController@topup_wallet_by_admin')->name('sales_managers.user.wallet.topup');



            Route::get('/home',function (){
                return view('sales_managers.index');
            })->name('managers.dashboard');
        });
    });
});

/*Common Routes*/
Route::group(['middleware' => ['check_user_or_shop']], function () {
    Route::prefix('store')->group(function () {
        Route::get('/wallet', 'WalletController@user_wallet_view')->name('store.user.wallet.show');
        Route::post('/wallet/top-up/bank-transfer', 'WalletController@request_wallet_topup_bank')->name('store.user.wallet.request.topup');
        Route::post('/wallet/top-up/bank-transfer', 'WalletController@request_wallet_topup_bank')->name('store.user.wallet.request.topup');
        Route::get('/pay-through-wallet/{id}', 'WalletController@order_payment_by_wallet')->name('store.order.wallet.pay');


        Route::get('/pay-through-paypal/{id}', 'PaypalController@paypal_order_payment')->name('store.order.paypal.pay');
        Route::get('/pay-through-paypal/{id}/cancel', 'PaypalController@paypal_payment_cancel')->name('store.order.paypal.pay.cancel');
        Route::get('/pay-through-paypal/{id}/success', 'PaypalController@paypal_payment_success')->name('store.order.paypal.pay.success');

        Route::post('/topup-through-paypal/{id}', 'WalletController@paypal_topup_payment')->name('store.wallet.paypal.topup');
        Route::get('/topup-through-paypal/{id}/cancel', 'WalletController@paypal_topup_payment_cancel')->name('store.wallet.paypal.topup.cancel');
        Route::get('/topup-through-paypal/{id}/success', 'WalletController@paypal_topup_payment_success')->name('store.wallet.paypal.topup.success');

        Route::post('/ticket/create', 'TicketController@create_ticket')->name('help-center.ticket.create');
        Route::post('/ticket/thread/create', 'TicketController@create_ticket_thread')->name('help-center.ticket.thread.create');

    });
});

Route::get('/variant/{id}/change/image/{image_id}', 'ProductController@change_image')->name('change_image');
Route::get('/search/products', 'CustomOrderController@find_products')->name('find_products');
Route::get('/get_selected_variants', 'CustomOrderController@get_selected_variants')->name('get_selected_variants');
