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

use App\EmailTemplate;
use App\ERPOrderFulfillment;
use App\Http\Controllers\AdminMaintainerController;
use App\Http\Controllers\HelperController;
use App\Mail\NewsEmail;
use App\MonthlyDiscountPreference;
use App\MonthlyDiscountSetting;
use App\Notification;
use App\Product;
use App\RetailerOrder;
use App\RetailerProduct;
use App\ShippingRate;
use App\Shop;
use App\Tag;
use App\User;
use App\WareHouse;
use App\WarehouseInventory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;



Route::get('/order/download/{id}/csv', 'CustomOrderController@download_order')->name('app.order.download');



Route::get('/reset', 'HelperController@reset_all');
Route::get('/reset-retailers', 'HelperController@deleteRetailer');

Route::get('/generate-super-admin', 'HelperController@SuperAdminCreate');
/*Shopify Store Routes*/
Route::get('/getShippingRates', 'ZoneController@getShippingRates');
Route::get('/getExportFile', 'ProductController@getExportFile')->name('app.download.product');
/*Auth Routes*/
Auth::routes(['verify' => true]);
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/shop-login', function (\Illuminate\Http\Request  $request) {
    return \redirect('/authenticate?shop='.$request->shop);
});
Route::get('/logout', function(){
    session()->flush();
    Auth::logout();
    return Redirect::to('login');
})->name('logout');
/*Super Admin Routes*/
//Route::group(['middleware' => ['auth.shop']], function () {
Route::get('/','AdminOrderController@dashboard')->name('admin.dashboard')->middleware('auth');
Route::group(['middleware' => ['auth', 'role:wordpress-admin']], function () {
    Route::get('/categories','CategoryController@index')->name('category.create');
    Route::post('/categories/save','CategoryController@save')->name('category.save');
    Route::any('/categories/{id}/update','CategoryController@update')->name('category.update');
    Route::get('/categories/{id}/delete','CategoryController@delete')->name('category.delete');
    Route::post('/subcategory/save','CategoryController@subsave')->name('sub.save');
    Route::any('/subcategory/{id}/update','CategoryController@subupdate')->name('sub.update');
    Route::get('/subcategory/{id}/delete','CategoryController@subdelete')->name('sub.delete');
    Route::post('/sub_sub_category/save','CategoryController@sub_sub_save')->name('sub.sub.save');
    Route::any('/sub_sub_category/{id}/update','CategoryController@sub_sub_update')->name('sub.sub.update');
    Route::get('/sub_sub_category/{id}/delete','CategoryController@sub_sub_delete')->name('sub.sub.delete');
    Route::get('/products','ProductController@index')->name('product.create');
    Route::get('/products/sync/inventory','ProductController@syncInventoryWithInflow')->name('product.sync.inventory');
    Route::get('/products/all','ProductController@all')->name('product.all');
    Route::get('dropship/products','ProductController@view_dropship_products_listing')->name('dropship.product.all');
    Route::any('/products/{id}/view','ProductController@view')->name('product.view');
    Route::any('/dropship/products/{id}/view','ProductController@view_single_dropship_product')->name('dropship.product.view');
    Route::any('/retailer/products/{id}/view','ProductController@retailer_view')->name('product.retailer.view');
    Route::any('/products/{id}/edit','ProductController@edit')->name('product.edit');
    Route::get('/products/{id}/import/to/woocommerce','ProductController@import_to_woocommerce')->name('product.import.to.woocommerce');
    Route::get('/download/sku/csv/{id}','ProductController@download_sku')->name('product.download.sku.csv');
    Route::any('/products/{id}/update','ProductController@update')->name('product.update');
    Route::post('/dropship/products/{id}/update','ProductController@updateDropshipProduct')->name('dropship.product.update');
    Route::any('/products/{id}/update/tab/details','ProductController@editTabDetails')->name('product.update.tab-details');
    Route::any('/products/{id}/add/new/variants','ProductController@updateExistingProductNewVariants')->name('product.update.add.new.variants');
    Route::any('/products/{id}/update/old/variants','ProductController@updateExistingProductOldVariants')->name('product.update.old.variants');
    Route::any('/products/{id}/change/status','ProductController@updateProductStatus')->name('product.change.status');
    Route::any('/products/{id}/delete/existing/image','ProductController@deleteExistingProductImage')->name('product.delete.existing.image');
    Route::any('/orders/{id}/send/status/email','AdminOrderController@sendOrderStatusEmail')->name('admin.send.order.status.email');
    Route::any('/products/{id}/add/images','ProductController@productAddImages')->name('product.add.images');
    Route::post('/products/{id}/add/tiered/price','ProductController@addTieredPrice')->name('product.add.tiered.price');
    Route::post('/single-product/{id}/add/tiered/price','ProductController@addTieredPriceForProductWithoutVariant')->name('single.product.add.tiered.price');
    Route::get('/products/{id}/remove/tiered/price','ProductController@removeTieredPrice')->name('product.remove.tiered.price');
    Route::post('/products/save','ProductController@save')->name('product.save');
    Route::post('/products/variant/save','ProductController@variant')->name('product.variant');
    Route::get('/products/{id}/delete','ProductController@delete')->name('product.delete');
    Route::get('/products/{id}/delete','ProductController@delete')->name('product.delete');
    Route::get('/products/{id}/images-position-update','ProductController@update_image_position')->name('product.update_image_position');
    Route::get('/products/{id}/categories-position-update','CategoryController@update_category_position')->name('category.update_image_position');
    Route::get('/products/{id}/varaints/new','ProductController@add_existing_product_new_variants')->name('product.existing_product_new_variants');
    Route::get('/products/{id}/varaints/update','ProductController@update_existing_product_new_variants')->name('product.existing_product_update_variants');
    Route::get('/products-tabs/{id}/delete','ProductController@delete_tab')->name('product.tab.delete');
    Route::get('/default/settings','DefaultSettingsController@index')->name('default_info');
    Route::get('/default/payment/settings','DefaultSettingsController@paymentIndex')->name('default_info_payment');
    Route::post('/default/settings/save','DefaultSettingsController@save')->name('default_info.save');
    Route::post('/charge/payment/save','DefaultSettingsController@save_percentage')->name('payment.charge.save');
    Route::post('/api/settings/save','DefaultSettingsController@save_api_settings')->name('api.settings.save');
    Route::post('/save/tiered/pricing/preferences','DefaultSettingsController@save_tiered_pricing_preferences')->name('save.tiered.pricing.preferences');
    Route::post('/save/general/discount/preferences','DefaultSettingsController@save_general_discount_preferences')->name('save.general.discount.preferences');
    Route::post('/save/fixed/discount/preferences','DefaultSettingsController@save_fixed_discount_preferences')->name('save.general.fixed.preferences');
    Route::post('/save/monthly/discount/settings','DefaultSettingsController@save_monthly_discount_settings')->name('save.monthly.discount.settings');
    Route::any('/default/settings/{id}/update','DefaultSettingsController@update')->name('default_info.update');
    Route::post('/create/platform','DefaultSettingsController@create_platform')->name('create_platform');
    Route::post('/update/platform/{id}','DefaultSettingsController@update_platform')->name('update_platform');
    Route::get('/delete/platform/{id}','DefaultSettingsController@delete_platform')->name('delete_platform');
    Route::get('/zones','ZoneController@index')->name('zone.index');
    Route::post('/zones','ZoneController@create')->name('zone.create');
    Route::resource('couriers', 'CourierController');
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
    Route::get('/sales-managers/{id}/report','DefaultSettingsController@get_manager_report')->name('sales-managers.report');
    Route::any('/sales-managers/{id}/set','DefaultSettingsController@set_manager_as_user')->name('sales-managers.set_manager_as_user');
    Route::get('/push/{id}/to-store','ProductController@import_to_shopify')->name('import_to_shopify');
    Route::get('/tickets','DefaultSettingsController@tickets')->name('tickets.index');
    Route::get('/tickets/{id}','DefaultSettingsController@ticket')->name('tickets.view');
    Route::get('/wishlists','DefaultSettingsController@wishlist')->name('wishlist.index');
    Route::get('/wishlists/{id}','DefaultSettingsController@view_wishlist')->name('wishlist.view');
    Route::get('/dropship-requests','DefaultSettingsController@dropship_requests')->name('dropship.requests.index');
    Route::get('/dropship-requests/{id}','DefaultSettingsController@view_dropship_requests')->name('dropship.requests.view');

    Route::get('/stores','DefaultSettingsController@stores')->name('stores.index');
    Route::get('/stores/{id}','DefaultSettingsController@store')->name('stores.view');
    Route::get('/stores/customers/{id}','DefaultSettingsController@customer_view')->name('customers.view');
    Route::get('/non-shopify-users','DefaultSettingsController@users')->name('users.index');
    Route::get('/non-shopify-users/{id}','DefaultSettingsController@user')->name('users.view');
    Route::get('/non-shopify-users/{id}/delete','DefaultSettingsController@Deleteuser')->name('users.delete');
    Route::get('/sync-store-orders/{id}', 'OrderController@syncAllOrders')->name('store.all.sync.orders');
    Route::get('/ticket-category','DefaultSettingsController@view_ticket_categories')->name('ticket.category.index');
    Route::post('/ticket-category','DefaultSettingsController@create_ticket_categories')->name('ticket.category.create');
    Route::post('/ticket-category/{id}/update','DefaultSettingsController@update_ticket_categories')->name('ticket.category.update');
    Route::any('/ticket-category/{id}/delete','DefaultSettingsController@delete_ticket_categories')->name('ticket.category.delete');
    Route::get('/orders','AdminOrderController@index')->name('admin.orders');
    Route::get('/orders/view/{id}','AdminOrderController@view_order')->name('admin.order.view');
    Route::get('/orders/view/{id}','AdminOrderController@view_order')->name('admin.order.view');
    Route::get('/orders/view/{id}/fulfillment','AdminOrderController@fulfill_order')->name('admin.order.fulfillment');
    Route::post('/orders/view/{id}/fulfillment/process','AdminOrderController@fulfillment_order')->name('admin.order.fulfillment.process');
    Route::get('manual/push/order/to/wefulfill/{id}', 'AdminOrderController@manual_push_order')->name('admin.manual_push_to_wefulfill');
    Route::get('/orders/{id}/fulfillment/cancel/{fulfillment_id}','AdminOrderController@fulfillment_cancel_order')->name('admin.order.fulfillment.cancel');
    Route::post('/orders/{id}/fulfillment/tracking','AdminOrderController@fulfillment_add_tracking')->name('admin.order.fulfillment.tracking');
    Route::post('/orders/{id}/fulfillment/edit/tracking/{fulfillment_id}','AdminOrderController@fulfillment_edit_tracking')->name('admin.order.edit.fulfillment.tracking');
    Route::get('/orders/{id}/mark-as-delivered','AdminOrderController@mark_as_delivered')->name('admin.order.mark_as_delivered');
    /*Admin Wallet Routes*/
    Route::get('/wallets', 'WalletController@index')->name('admin.wallets');
    Route::get('/wallets-requests', 'WalletController@walletRequest')->name('admin.wallets.requests');
    Route::get('/wallets/{id}', 'WalletController@wallet_details')->name('admin.wallets.detail');
    Route::get('/wallet/request/approve/{id}', 'WalletController@approved_bank_statement')->name('admin.wallets.approve.request');
    Route::get('/wallet/request/{id}/edit', 'WalletController@EditWalletDetails')->name('admin.wallets.edit');
    Route::post('/wallet/top-up', 'WalletController@topup_wallet_by_admin')->name('admin.user.wallet.topup');
    Route::post('/wallet/edit', 'WalletController@editWalletAmount')->name('admin.user.wallet.amount.edit');

    Route::get('/refunds','DefaultSettingsController@refunds')->name('refunds.index');
    Route::get('/refunds/{id}', 'DefaultSettingsController@view_refund')->name('refunds.view');
    Route::post('/assign_manager/{id}', 'DefaultSettingsController@assign_manager')->name('assign_manager');

    /*Product Notification Update*/
    Route::get('/notification/{id}/product', 'ProductController@product_notification')->name('product.notification.update');

    Route::get('orders/bulk-tracking/', 'AdminOrderController@show_import_data')->name('orders.bulk.tracking');
    Route::get('orders/bulk-tracking/download', 'AdminOrderController@download_orders')->name('orders.bulk.tracking.download');
    Route::post('orders/bulk-tracking/import', 'AdminOrderController@import_bulk_tracking')->name('orders.bulk.tracking.import');

    Route::get('customers/{id}/download', 'DefaultSettingsController@download_customer')->name('customers.download');
    Route::get('/email/templates', 'EmailTemplateController@index')->name('admin.emails.index');
    Route::get('/email/templates/{id}', 'EmailTemplateController@show')->name('admin.emails.show');
    Route::get('/email/templates/edit/{id}', 'EmailTemplateController@edit')->name('admin.emails.edit');
    Route::put('/email/templates/update/{id}', 'EmailTemplateController@update')->name('admin.emails.update');
    Route::post('/email/templates/{id}/status', 'EmailTemplateController@changeStatus')->name('admin.emails.status');
    Route::get('/tiered/pricing/preferences', 'DefaultSettingsController@getTieredPricingPreferences')->name('admin.tiered.pricing.preferences');
    Route::get('/general/discounts/preferences', 'DefaultSettingsController@getGeneralDiscountPreferences')->name('admin.general.discount.preferences');
    Route::get('/monthly/discounts/settings', 'DefaultSettingsController@getMonthlyDiscountSettings')->name('admin.monthly.discount.settings');
    Route::get('/activity/logs', 'ActivityLogController@index')->name('admin.activity.log.index');
    Route::get('/campaigns', 'DefaultSettingsController@campaigns')->name('email.campaigns.index');
    Route::get('/campaigns/{id}', 'DefaultSettingsController@getCampaign')->name('email.campaigns.show');
    Route::get('/campaigns/{id}/delete', 'DefaultSettingsController@deleteCampaign')->name('email.campaigns.delete');
    Route::get('/campaigns/{id}/remove/user/{user_id}', 'DefaultSettingsController@removeUserFromCampaign')->name('campaigns.remove.user');
    Route::get('/campaigns/{id}/submit', 'DefaultSettingsController@submitCampaign')->name('email.campaigns.submit');
    Route::get('/campaigns/{id}/edit', 'DefaultSettingsController@editCampaign')->name('email.campaigns.edit');
    Route::post('/campaigns/{id}/update', 'DefaultSettingsController@updateCampaign')->name('email.campaigns.update');
    Route::get('/sync_manual/{id}', 'AdminOrderController@manualSyncfulfillment')->name('manually.sync.fulfillment');
    Route::get('/category/{title}/get/sub-categories', 'CategoryController@getSubCategories');
    Route::get('/sub-category/{title}/get/sub-sub-categories', 'CategoryController@getSubSubCategories');

    Route::get('warehouses', 'WareHouseController@index')->name('warehouse.index');
    Route::post('warehouses/store', 'WareHouseController@store')->name('warehouse.store');
    Route::post('warehouses/update/{id}', 'WareHouseController@update')->name('warehouse.update');
    Route::post('warehouses/{id}', 'WareHouseController@delete')->name('warehouse.destroy');

    Route::get('/manual-push-to-ship-station/{id}', 'AdminMaintainerController@manualPushToShipStation')->name('manual.push.to.shipstaion');


    // ROUTES ADDED AFTER WOOCOMMERCE INTEGRATION
    Route::resource('tags', 'TagController');

    Route::get('/user-suggestions', 'DefaultSettingsController@showSuggestions')->name('admin.suggestions');
    Route::get('news', 'DefaultSettingsController@showNews')->name('admin.news.index');
    Route::post('news', 'DefaultSettingsController@createNews')->name('admin.news.store');
    Route::post('news/edit/{id}', 'DefaultSettingsController@editNews')->name('admin.news.edit');
    Route::post('news/delete/{id}', 'DefaultSettingsController@deleteNews')->name('admin.news.delete');
    Route::get('admin/yourwholesalesource/university', 'DefaultSettingsController@allVideos')->name('admin.videos.index');
    Route::post('/admin/yourwholesalesource/university', 'DefaultSettingsController@createVideo')->name('admin.videos.create');
    Route::post('/admin/yourwholesalesource/university/ribbon', 'DefaultSettingsController@createRibbon')->name('admin.ribbon.create');
    Route::post('/admin/yourwholesalesource/university/edit/{id}', 'DefaultSettingsController@editVideo')->name('admin.videos.edit');
    Route::post('/admin/yourwholesalesource/university/ribbon/edit/{id}', 'DefaultSettingsController@editRibbon')->name('admin.ribbons.edit');
    Route::post('/admin/yourwholesalesource/university/delete/{id}', 'DefaultSettingsController@deleteVideo')->name('admin.videos.delete');
    Route::post('/admin/yourwholesalesource/university/ribbon//delete/{id}', 'DefaultSettingsController@deleteRibbon')->name('admin.ribbons.delete');



});

/*Single Store Routes*/
Route::group(['middleware' => ['auth.shop']], function () {
    Route::get('/import/{id}/to-store','RetailerProductController@import_to_shopify')->name('retailer.import_to_shopify');
    Route::prefix('store')->group(function () {
        Route::post('/user/authenticate','SingleStoreController@authenticate')->name('store.user.authenticate');
        Route::post('/user/store/association','SingleStoreController@associate')->name('store.user.associate');
        Route::get('/dashboard','SingleStoreController@index')->name('store.dashboard')->middleware(['check_shop_user']);
//        Route::get('/reports','SingleStoreController@reports')->name('store.reports');
        Route::get('/invoice','SingleStoreController@showInvoice')->name('store.invoice');
        Route::get('/invoice/download/{id}','SingleStoreController@downloadInvoicePDF')->name('store.invoice.download');
        Route::get('/settings','SingleStoreController@setting')->name('store.index');
        Route::get('/settings/authentication','SingleStoreController@storeAuthentication')->name('store.authentication');
        Route::post('/settings/personal','SingleStoreController@save_personal_info')->name('store.save_personal_info');
        Route::post('/settings/personal/address','SingleStoreController@save_address')->name('store.save_address');
        Route::post('/wallet/settings/{id}','SingleStoreController@saveWalletSettings')->name('store.save.wallet.settings');
        Route::get('/products/wefullfill','SingleStoreController@wefullfill_products')->name('store.product.wefulfill');
        Route::get('/products/wefullfill/{id}','SingleStoreController@view_fantasy_product')->name('store.product.wefulfill.show');
        Route::get('/my_products/wefullfill/{id}','SingleStoreController@view_my_product')->name('store.my_product.wefulfill.show');
        /*Import List Route*/
        Route::get('/wefullfill/{id}/add-to-import-list','RetailerProductController@add_to_import_list')->name('store.product.wefulfill.add-to-import-list');
        Route::get('/wefullfill/{id}/updated-product','RetailerProductController@show_updated_product')->name('store.product.wefulfill.updated-product');
        Route::post('/wefullfill/{id}/update/variants','RetailerProductController@updateProductVariants')->name('store.product.variant.update');
        Route::get('/import-list','RetailerProductController@import_list')->name('store.import_list');
        Route::get('/my_products','RetailerProductController@my_products')->name('store.my_products');
        Route::get('/my_dropship_products','RetailerProductController@my_dropship_products')->name('store.my_dropship_products');
        Route::get('/my_products/{id}','RetailerProductController@edit_my_product')->name('store.my_product.edit');
        Route::get('/products/delete/{id}','RetailerProductController@delete')->name('store.product.delete');
        Route::get('/products/sync/{id}','RetailerProductController@syncWithAdminProduct')->name('store.product.sync');
        Route::post('/import-list/{id}/update','RetailerProductController@update')->name('store.import_list.product.update');
        Route::get('/getOrders', 'OrderController@getOrders')->name('store.sync.orders');
        Route::get('/orders', 'OrderController@index')->name('store.orders');
        Route::get('/order/delete/{id}', 'OrderController@delete')->name('store.order.delete');
        Route::get('/order/view/{id}', 'OrderController@view_order')->name('store.order.view');
        Route::get('/customers', 'SingleStoreController@customers')->name('store.customers');
        Route::get('/customers/{id}', 'SingleStoreController@customer_view')->name('store.customer.view');
        Route::get('/getCustomers', 'SingleStoreController@getCustomers')->name('store.sync.customers');
        Route::get('/payments', 'SingleStoreController@payment_history')->name('store.payments');
        Route::get('/tracking-info', 'SingleStoreController@tracking_info')->name('store.tracking');
        Route::get('/help-center','SingleStoreController@helpcenter')->name('store.help-center');
        Route::get('/help-center/ticket/{id}', 'SingleStoreController@view_ticket')->name('help-center.store.ticket.view');
        Route::get('/wishlist','SingleStoreController@wishlist')->name('store.wishlist');
        Route::get('/wishlist/{id}','SingleStoreController@view_wishlist')->name('store.wishlist.view');
        Route::get('/refunds', 'SingleStoreController@refunds')->name('store.refunds');
        Route::get('/refunds/{id}', 'SingleStoreController@refund')->name('store.refund');
        Route::get('/notifications/{id}', 'SingleStoreController@show_notification')->name('store.notification');
        Route::get('/notifications', 'SingleStoreController@notifications')->name('store.notifications');


        Route::get('/dropship-requests', 'SingleStoreController@dropship_requests')->name('store.dropship.requests');
        Route::get('/dropship-requests/{id}', 'SingleStoreController@view_dropship_request')->name('store.dropship.request.view');
        Route::get('/dropship-requests/{id}/view-shipping-mark/{mark_id}', 'SingleStoreController@view_shipping_mark')->name('store.dropship.requests.view.shipping.mark');
        Route::get('/dropship-requests/{id}/create-shipping-mark', 'SingleStoreController@create_shipping_mark')->name('store.dropship.requests.create.shipping.mark');



        Route::post('/orders/bulk-payment', 'OrderController@show_bulk_payments')->name('store.orders.bulk.payment');


    });
});


//Woocommerce Store Routes
Route::group(['middleware' => ['check_woocommerce_shop']], function () {
    Route::get('/import/{id}/to-woocommerce-store','RetailerProductController@import_to_woocommerce')->name('woocommerce.retailer.import_to_shopify');
    Route::prefix('woocommerce-store')->group(function () {
        Route::post('/user/authenticate','WoocommerceStoreController@authenticate')->name('woocommerce.user.authenticate');
        Route::post('/user/store/association','WoocommerceStoreController@associate')->name('woocommerce.user.associate');
        Route::get('/dashboard','WoocommerceStoreController@index')->name('woocommerce.store.dashboard');
        Route::get('/reports','WoocommerceStoreController@reports')->name('store.reports');
        Route::get('/invoice','WoocommerceStoreController@showInvoice')->name('woocommerce.invoice');
        Route::get('/invoice/download/{id}','WoocommerceStoreController@downloadInvoicePDF')->name('woocommerce.invoice.download');
        Route::get('/settings','WoocommerceStoreController@setting')->name('woocommerce.index');
        Route::post('/settings/personal','WoocommerceStoreController@save_personal_info')->name('woocommerce.save_personal_info');
        Route::post('/settings/personal/address','WoocommerceStoreController@save_address')->name('woocommerce.save_address');
        Route::post('/wallet/settings/{id}','WoocommerceStoreController@saveWalletSettings')->name('woocommerce.save.wallet.settings');
        Route::get('/products/wefullfill','WoocommerceStoreController@wefullfill_products')->name('woocommerce.product.wefulfill');
        Route::get('/products/wefullfill/{id}','WoocommerceStoreController@view_fantasy_product')->name('woocommerce.product.wefulfill.show');
        Route::get('/my_products/wefullfill/{id}','WoocommerceStoreController@view_my_product')->name('woocommerce.my_product.wefulfill.show');
        /*Import List Route*/
        Route::get('/wefullfill/{id}/add-to-import-list','RetailerProductController@add_to_woocommerce_import_list')->name('woocommerce.product.wefulfill.add-to-import-list');
        Route::get('/wefullfill/{id}/updated-product','RetailerProductController@show_updated_product')->name('woocommerce.product.wefulfill.updated-product');
        Route::post('/wefullfill/{id}/update/variants','RetailerProductController@updateProductVariants')->name('woocommerce.product.variant.update');
        Route::get('/import-list','RetailerProductController@woocommerce_import_list')->name('woocommerce.import_list');
        Route::get('/my_products','RetailerProductController@my_woocommerce_products')->name('woocommerce.my_products');
        Route::get('/my_dropship_products','RetailerProductController@my_dropship_products')->name('woocommerce.my_dropship_products');
        Route::get('/my_products/{id}','RetailerProductController@edit_my_woocommerce_product')->name('woocommerce.my_product.edit');
        Route::get('/products/delete/{id}','RetailerProductController@delete_woocommerce_product')->name('woocommerce.product.delete');
        Route::get('/products/sync/{id}','RetailerProductController@syncWithAdminProduct')->name('woocommerce.product.sync');
        Route::post('/import-list/{id}/update','RetailerProductController@update_woocommerce_product')->name('woocommerce.import_list.product.update');
        Route::get('/getOrders', 'OrderController@getOrders')->name('woocommerce.sync.orders');
        Route::get('/orders', 'OrderController@index')->name('woocommerce.orders');
        Route::get('/order/delete/{id}', 'OrderController@delete')->name('woocommerce.order.delete');
        Route::get('/order/view/{id}', 'OrderController@view_order')->name('woocommerce.order.view');
        Route::get('/customers', 'WoocommerceStoreController@customers')->name('woocommerce.customers');
        Route::get('/customers/{id}', 'WoocommerceStoreController@customer_view')->name('woocommerce.customer.view');
        Route::get('/getCustomers', 'WoocommerceStoreController@getCustomers')->name('woocommerce.sync.customers');
        Route::get('/payments', 'WoocommerceStoreController@payment_history')->name('woocommerce.payments');
        Route::get('/tracking-info', 'WoocommerceStoreController@tracking_info')->name('woocommerce.tracking');
        Route::get('/help-center','WoocommerceStoreController@helpcenter')->name('woocommerce.help-center');
        Route::get('/help-center/ticket/{id}', 'WoocommerceStoreController@view_ticket')->name('help-center.woocommerce.ticket.view');
        Route::get('/wishlist','WoocommerceStoreController@wishlist')->name('woocommerce.wishlist');
        Route::get('/wishlist/{id}','WoocommerceStoreController@view_wishlist')->name('woocommerce.wishlist.view');
        Route::get('/refunds', 'WoocommerceStoreController@refunds')->name('woocommerce.refunds');
        Route::get('/refunds/{id}', 'WoocommerceStoreController@refund')->name('woocommerce.refund');
        Route::get('/notifications/{id}', 'WoocommerceStoreController@show_notification')->name('woocommerce.notification');
        Route::get('/notifications', 'WoocommerceStoreController@notifications')->name('woocommerce.notifications');


        Route::get('/dropship-requests', 'WoocommerceStoreController@dropship_requests')->name('woocommerce.dropship.requests');
        Route::get('/dropship-requests/{id}', 'WoocommerceStoreController@view_dropship_request')->name('woocommerce.dropship.request.view');
        Route::get('/dropship-requests/{id}/view-shipping-mark/{mark_id}', 'WoocommerceStoreController@view_shipping_mark')->name('woocommerce.dropship.requests.view.shipping.mark');
        Route::get('/dropship-requests/{id}/create-shipping-mark', 'WoocommerceStoreController@create_shipping_mark')->name('woocommerce.dropship.requests.create.shipping.mark');


        Route::get('/wefulfill/university','WoocommerceStoreController@showVideosSection')->name('woocommerce.university.index');
        Route::post('/orders/bulk-payment', 'OrderController@show_bulk_payments')->name('woocommerce.orders.bulk.payment');


    });
});

/*Main Routes*/
Route::group(['middleware' => ['auth', 'verified']], function () {
    /*Checking User Role*/
    Route::get('/check/roles','RolePermissionController@check_roles')->name('system.check-roles');
    Route::get('/choose/platform','RolePermissionController@selection')->name('system.selection');

    /*Store Connection*/
    Route::get('/shop/login', 'SingleStoreController@storeAuthenticate');
    Route::get('/connect/store','RolePermissionController@store_connect')->name('system.store.connect');
    Route::get('/connect/woocommerce/store','WoocommerceStoreController@woocommerce_store_connect')->name('system.woocommerce.store.connect');
    Route::post('/user/authenticate/woocommerce','WoocommerceStoreController@authenticate_woocommerce')->name('store.user.authenticate.woocommerce');
    Route::get('/woocommerce/stores','WoocommerceStoreController@woocommerce_stores')->name('users.woocommerce.stores');
    Route::post('/woocommerce/install','WoocommerceStoreController@switch_to_store')->name('switch.woocommerce');
    /*Non-Shopify and Shopify User Routes */

    Route::get('users/home','ShopifyUsersController@index')->name('users.dashboard')->middleware('role:non-shopify-users');

    Route::group(['middleware' => ['role:non-shopify-users']], function () {
        Route::prefix('users')->group(function () {
            Route::get('/user/store/de-association/{id}','SingleStoreController@de_associate')->name('store.user.de-associate');
            Route::get('/reports','ShopifyUsersController@reports')->name('users.reports');
            Route::get('/invoice','ShopifyUsersController@showInvoice')->name('users.invoice');
            Route::get('/invoice/download/{id}','ShopifyUsersController@downloadInvoicePDF')->name('users.invoice.download');
            Route::get('/settings','ShopifyUsersController@setting')->name('users.settings');
            Route::post('/settings/personal','ShopifyUsersController@save_personal_info')->name('users.save_personal_info');
            Route::post('/settings/personal/address','ShopifyUsersController@save_address')->name('users.save_address');
            Route::post('/settings/change/password','ShopifyUsersController@change_password')->name('users.change.password');

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
            Route::get('/files/{id}/download_processed_orders', 'CustomOrderController@download_processed_orders')->name('users.files.download_processed_orders');
            Route::get('/files/{id}/download_unprocessed_orders', 'CustomOrderController@download_unprocessed_orders')->name('users.files.download_unprocessed_orders');

            Route::post('/bulk-pay-through-paypal/{id}', 'CustomOrderController@bulk_import_order_paypal')->name('users.orders.bulk.paypal');
            Route::get('/bulk-pay-through-paypal/{id}/cancel', 'CustomOrderController@bulk_import_order_paypal_cancel')->name('users.orders.bulk.paypal.cancel');
            Route::get('/bulk-pay-through-paypal/{id}/success', 'CustomOrderController@bulk_import_order_paypal_success')->name('users.orders.bulk.paypal.success');
            Route::get('/bulk-pay-through-wallet/{id}', 'CustomOrderController@bulk_import_order_wallet')->name('users.orders.bulk.wallet');
            Route::post('/bulk-pay-through-card/{id}', 'CustomOrderController@bulk_import_order_card')->name('users.orders.bulk.card');


            Route::get('/products/wefullfill','CustomOrderController@wefullfill_products')->name('users.product.wefulfill');
            Route::get('/products/dropship','CustomOrderController@my_dropship_products')->name('users.product.dropship');
            Route::get('/products/wefullfill/{id}','CustomOrderController@view_fantasy_product')->name('users.product.wefulfill.show');
            Route::get('/products/dropship/{id}','CustomOrderController@view_single_dropship_product')->name('users.product.dropship.show');
            Route::get('/help-center','CustomOrderController@helpcenter')->name('users.help-center');
            Route::get('/help-center/ticket/{id}', 'CustomOrderController@view_ticket')->name('help-center.users.ticket.view');
            Route::get('/wishlist','CustomOrderController@wishlist')->name('users.wishlist');
            Route::get('/wishlist/{id}','CustomOrderController@view_wishlist')->name('users.wishlist.view');
            Route::get('/refunds', 'CustomOrderController@refunds')->name('users.refunds');
            Route::get('/refunds/{id}', 'CustomOrderController@refund')->name('users.refund');
            Route::get('/notifications/{id}', 'CustomOrderController@show_notification')->name('users.notification');
            Route::get('/notifications', 'CustomOrderController@notifications')->name('users.notifications');
            Route::get('/get/admin/products','ProductController@getAdminProducts')->name('admin.product.all');
            Route::get('/get/dropship/products','ProductController@getUserDropshipProducts')->name('user.dropship.products.all');

            Route::get('/dropship-requests', 'CustomOrderController@dropship_requests')->name('users.dropship.requests');
            Route::get('/dropship-requests/{id}', 'CustomOrderController@view_dropship_request')->name('users.dropship.request.view');
            Route::get('/dropship-requests/{id}/view-shipping-mark/{mark_id}', 'DropshipRequestController@view_shipping_mark')->name('users.dropship.requests.view.shipping.mark');
            Route::get('/dropship-requests/{id}/create-shipping-mark', 'CustomOrderController@create_shipping_mark')->name('users.dropship.requests.create.shipping.mark');

            Route::get('/yourwholesalesource/university','CustomOrderController@showVideosSection')->name('users.university.index');

            Route::group(['middleware' => ['check_user_shop']], function () {

            });
        });

    });
    /*Sales Manager Routes*/
    Route::group(['middleware' => ['role:sales-manager']], function () {
        Route::prefix('managers')->group(function () {
            Route::get('/tickets','ManagerController@tickets')->name('sales_managers.tickets');
            Route::get('/tickets/{id}', 'ManagerController@view_ticket')->name('sales_managers.ticket.view');
            Route::get('/wishlist','ManagerController@wishlist')->name('sales_managers.wishlist');
            Route::get('/wishlist/{id}', 'ManagerController@view_wishlist')->name('sales_managers.wishlist.view');
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
            Route::get('/wallets-requests', 'ManagerController@wallet_request')->name('sales_managers.wallets.requests');
            Route::get('/wallets/{id}', 'ManagerController@wallet_details')->name('sales_managers.wallets.detail');
            Route::get('/wallet/request/approve/{id}', 'ManagerController@approved_bank_statement')->name('sales_managers.wallets.approve.request');
            Route::post('/wallet/top-up', 'ManagerController@topup_wallet_by_admin')->name('sales_managers.user.wallet.topup');
            Route::get('/home','ManagerController@dashboard')->name('managers.dashboard');
            Route::get('/products','ManagerController@viewAllProducts')->name('managers.products');
            Route::get('/products/{id}','ManagerController@viewSingleProduct')->name('managers.products.view');

            Route::get('/refunds','ManagerController@refunds')->name('sales_managers.refunds');
            Route::get('/refunds/{id}', 'ManagerController@view_refund')->name('sales_managers.refunds.view');

        });
    });
});

/*Common Routes*/
Route::group(['middleware' => ['check_user_or_shop']], function () {
    Route::prefix('app')->group(function () {
        Route::get('/orders/{id}/mark-as-complete','AdminOrderController@mark_as_completed')->name('admin.order.complete');
        Route::post('/order/payment', 'OrderController@proceed_payment')->name('store.order.proceed.payment');
        Route::post('/order/update/address/{id}', 'OrderController@order_addresss_update')->name('store.order.address.update');
        Route::post('/order/bulk/payment', 'OrderController@proceed_bulk_payment')->name('store.order.proceed.bulk.payment');

        Route::get('/wallet', 'WalletController@user_wallet_view')->name('store.user.wallet.show');
        Route::post('/wallet/top-up/bank-transfer', 'WalletController@request_wallet_topup_bank')->name('store.user.wallet.request.topup');
        Route::get('/pay-through-wallet/{id}', 'WalletController@order_payment_by_wallet')->name('store.order.wallet.pay');
        Route::post('/pay-bulk-through-wallet', 'WalletController@order_bulk_payment_by_wallet')->name('store.order.wallet.pay.bulk');
        Route::get('/pay-through-paypal/{id}', 'PaypalController@paypal_order_payment')->name('store.order.paypal.pay');
        Route::post('/pay-bulk-through-paypal', 'PaypalController@paypal_bulk_order_payment')->name('store.order.paypal.bulk.pay');
        Route::get('/pay-through-paypal/{id}/cancel', 'PaypalController@paypal_payment_cancel')->name('store.order.paypal.pay.cancel');
        Route::any('/pay-through-paypal/{id}/success', 'PaypalController@paypal_payment_success')->name('store.order.paypal.pay.success');
        Route::post('/topup-through-paypal/{id}', 'WalletController@paypal_topup_payment')->name('store.wallet.paypal.topup');
        Route::get('/topup-through-paypal/{id}/cancel', 'WalletController@paypal_topup_payment_cancel')->name('store.wallet.paypal.topup.cancel');
        Route::get('/topup-through-paypal/{id}/success', 'WalletController@paypal_topup_payment_success')->name('store.wallet.paypal.topup.success');

        Route::post('/ticket/create', 'TicketController@create_ticket')->name('help-center.ticket.create');
        Route::post('/ticket/thread/create', 'TicketController@create_ticket_thread')->name('help-center.ticket.thread.create');
        Route::get('/ticket/status/{id}/completed', 'TicketController@marked_as_completed')->name('help-center.ticket.marked_as_completed');
        Route::get('/ticket/status/{id}/closed', 'TicketController@marked_as_closed')->name('help-center.ticket.marked_as_closed');

        Route::post('/wishlist/create', 'WishlistController@create_wishlist')->name('wishlist.create');
        Route::post('/wishlist/thread/create', 'WishlistController@create_wishlist_thread')->name('wishlist.thread.create');
        Route::post('/wishlist/accepted', 'WishlistController@accept_wishlist')->name('wishlist.accept');
        Route::post('/wishlist/approved', 'WishlistController@approve_wishlist')->name('wishlist.approve');
        Route::post('/wishlist/completed', 'WishlistController@completed_wishlist')->name('wishlist.completed');
        Route::post('/wishlist/completed/map_product', 'WishlistController@map_product')->name('wishlist.completed.map_product');
        Route::post('/wishlist/rejected', 'WishlistController@reject_wishlist')->name('wishlist.reject');
        Route::get('/wishlist/{id}/delete','WishlistController@delete_wishlist')->name('wishlist.delete');


        Route::post('/dropship-requests/create', 'DropshipRequestController@create_dropship_requests')->name('dropship.request.create');
        Route::get('/dropship-requests/{id}/delete', 'DropshipRequestController@delete_dropship_requests')->name('dropship.requests.delete');
        Route::post('/dropship-requests/approved', 'DropshipRequestController@approve_dropship_request')->name('dropship.requests.approve');
        Route::post('/dropship-requests/rejected', 'DropshipRequestController@reject_dropship_request')->name('dropship.requests.reject');
        Route::post('/dropship-requests/accepted', 'DropshipRequestController@accept_dropship_request')->name('dropship.requests.accept');
        Route::post('/dropship-requests/shipped', 'DropshipRequestController@mark_as_shipped_dropship_request')->name('dropship.requests.shipped');
        Route::post('/dropship-requests/completed', 'DropshipRequestController@complete_dropship_request')->name('dropship.requests.completed');
        Route::post('/dropship-requests/cancel', 'DropshipRequestController@cancel_dropship_request')->name('dropship.requests.cancelled');
        Route::post('/dropship-requests/continue', 'DropshipRequestController@continue_dropship_request')->name('dropship.requests.continue');
        Route::post('/dropship-requests/connection', 'DropshipRequestController@connect_dropship_request')->name('dropship.requests.product.connection');
        Route::post('/dropship-requests/rejected_by_weight', 'DropshipRequestController@mark_as_rejected_by_weight_dropship_request')->name('dropship.reqeusts.rejected.by.weight');
        Route::post('/dropship-requests/rejected_by_inventory', 'DropshipRequestController@mark_as_rejected_by_inventory_dropship_request')->name('dropship.reqeusts.rejected.by.inventory');
        Route::get('/dropship-requests/{id}/create-shipping-mark', 'DropshipRequestController@create_shipping_mark')->name('dropship.requests.create.shipping.mark');
        Route::post('/dropship-requests/{id}/create-shipping-mark/create', 'DropshipRequestController@save_shipping_mark')->name('dropship.requests.save.shipping.mark');
        Route::post('/dropship-requests/{id}/create-shopify-product-shipping-mark/create', 'DropshipRequestController@save_shopify_product_shipping_mark')->name('dropship.requests.save.shipping.mark.shopify');
        Route::get('/dropship-requests/{id}/view-shipping-mark/{mark_id}', 'DefaultSettingsController@view_shipping_mark')->name('dropship.requests.view.shipping.mark');
        Route::get('/delete-shipping-mark/{id}', 'DropshipRequestController@delete_shipping_mark')->name('delete.shipping.mark');

        Route::post('/ticket/review', 'TicketController@post_review')->name('ticket.post_review');
        /*Refund*/
        Route::post('/create/refund', 'RefundController@create_refund')->name('refund.create');
        Route::post('/create/refund/thread', 'RefundController@create_refund_thread')->name('refund.create.thread');
        Route::get('/refund/approve/{id}/order/{order_id}', 'RefundController@approve_refund')->name('refund.approve');
        Route::get('cancel/order/{id}', 'RefundController@cancel_order')->name('app.order.cancel');
        Route::get('cancel/refund/order/{id}', 'RefundController@refund_cancel_order')->name('app.refund_cancel_order');

        Route::post('/orders/bulk-fulfillments', 'AdminOrderController@show_bulk_fulfillments')->name('app.orders.bulk.fulfillment');

        Route::post('post/questionnaire', 'HelperController@SaveQuestionnaire')->name('app.questionaire.post');

        Route::get('/yourwholesalesource/university','DefaultSettingsController@showVideosSection')->name('university.index');





    });
});

Route::get('/variant/{id}/change/image/{image_id}', 'ProductController@change_image')->name('change_image');
Route::get('/search/products', 'CustomOrderController@find_products')->name('find_products');
Route::get('/search/dropship/products', 'CustomOrderController@find_dropship_products')->name('find_dropship_products');
Route::get('/get_selected_variants', 'CustomOrderController@get_selected_variants')->name('get_selected_variants');
Route::get('/calculate_shipping', 'SingleStoreController@calculate_shipping')->name('calculate_shipping');
Route::get('/get-warehouse/shipping-price', 'SingleStoreController@calculate_warehouse_shipping')->name('calculate_warehouse_shipping');

Route::get('test/sync/{id}', 'PaypalController@test');

Route::get('getWebhooks', 'AdminOrderController@GetWebhooks');
Route::get('fetch_stock.json', 'InventoryController@FetchQuantity');
Route::get('/sync-inflow-product', 'InventoryController@syncInflowProducts');
Route::get('/add-inflow-products', 'InventoryController@addInflowIds');





//zain

Route::get('/inventory','InventoryController@getinventory')->name('inventory.create');

Route::post('/export','InventoryController@exportCsv')->name('inventory.export');

Route::post('/import','InventoryController@importCsv')->name('inventory.import');


Route::get('/allproducts', 'ProductController@allproducts')->name('product.allproducts');
Route::post('/exportallproducts','ProductController@exportallproducts')->name('product.exportallproducts');

Route::post('/importallproducts','ProductController@importallproducts')->name('product.importallproducts');


Route::get('check/questionnaire', 'HelperController@QuestionnaireCheck')->name('app.questionaire.check');
Route::get('test/emails', 'HelperController@testEmail');



Route::get('/push-to-ship-station/{id}', 'AdminMaintainerController@pushToShipStation')->name('push.to.shipstation');
//Route::any('/get/shipment', 'AdminOrderController@getFulfillmentFromErp')->name('erp.order.fulfillment');
Route::post('suggestions/create', 'DefaultSettingsController@createSuggestion')->name('suggestion.create');

Route::any('/get/shipment', function() {
   $log = new \App\ErrorLog();
   $log->message = "hi there new shippment test";
   $log->save();
});


//Route::get('fetch-order', 'HelperController@test');
//Route::get('create/service', 'InventoryController@create_service');
//Route::get('/get/inventory/sync', 'InventoryController@inventory_connect');
//Route::get('/test', 'AdminOrderController@changeFulfillmentServiceUrl');
//Route::get('/sendgrid/sync/old/users', 'AdminMaintainerController@sendGrid');
Route::get('/testing-2', function() {
    $helper = new HelperController();
    $shop = $helper->getSpecificShop(197);
    $response = $shop->api()->rest('GET', '/admin/webhooks.json');

    dd($response);
});




//Route::get('/tess', function() {
//   $class = new \App\Http\Controllers\AdminOrderController();
//
//    $order = RetailerOrder::find(2362);
//    $fulfillment = ERPOrderFulfillment::where('retailer_order_id', $order->id)->first();
//
//
//    $class->set_erp_order_fulfillment()
//});

Route::get('/testing', function() {
    $helper = new HelperController();
    $shop = $helper->getSpecificShop(188);


    $response = $shop->api()->rest('POST', '/admin/webhooks.json', [
        'webhook' => [
            'topic' => 'customers/create',
            'address' => 'https://app.yourwholesalesource.com/webhook/customers-create',
            "format"=> "json"
        ]
    ]);

    $response = $shop->api()->rest('POST', '/admin/webhooks.json', [
        'webhook' => [
            "topic" => "products/delete",
            "address" => "https://app.yourwholesalesource.com/webhook/products-delete",
            "format"=> "json"
        ]
    ]);

    $response = $shop->api()->rest('POST', '/admin/webhooks.json', [
        'webhook' => [
            'topic' => 'orders/cancelled',
            'address' => 'https://app.yourwholesalesource.com/webhook/orders-cancelled',
            "format"=> "json"
        ]
    ]);

    $response = $shop->api()->rest('GET', '/admin/webhooks.json');

    dd($response);
});


Route::get('/webhok', function() {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://ssapi.shipstation.com/webhooks",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Host: ssapi.shipstation.com",
            "Authorization: Basic MjRkYWZmNmY4OGMzNGY4OGJhZmE0ZWI3ZmRlM2NhNjA6ODkyYjM0MjYzMjgzNDkxNmFlZjAzNWQ5NTc2MzZkNDc=",
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    dd($response);
});


Route::get('/regsiter-web', function() {
    $url = "https://ssapi.shipstation.com/webhooks/subscribe";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = array(
        "Authorization: Basic ZmI0NWNiZmU5OGFjNDliMDlmMTJhYmY4YzkyY2Y2MDc6MTk5MDQxN2RlMTQxNDhjYjg4Mzk4MDNkNDYwNTBhOWY=",
        "Content-Type: application/json",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $data = [
      "target_url"=> "https://app.yourwholesalesource.com/get/ship-station-fulfillment-details",
      "event"=> "SHIP_NOTIFY",
      "store_id"=> null,
      "friendly_name"=> "Shipstation Shipment Webhook"
    ];

    $data = str_replace("\\", '', json_encode($data));


    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

//for debug only!
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $resp = curl_exec($curl);
    curl_close($curl);
    dd($resp);
});

Route::any('/any-web', function() {
    $inventory = new \App\Http\Controllers\InventoryController();
    $inventory->deductProductInventory(Product::find(3170), 1);
});
Route::post('stripe', 'StripeController@processPayment')->name('stripe.process.payment');


Route::get('/process-variants-csv', 'ProductCsvImportController@storeVariantFile');
Route::get('/process-products-csv', 'ProductCsvImportController@storeProductCsv');
Route::get('/remove-extra', 'ProductCsvImportController@removeExtraProducts');
Route::get('/create-product-csv', 'ProductCsvImportController@processProducts');



Route::get('/sync-test', function() {
   $inv = new \App\Http\Controllers\InventoryController();
   $product = Product::latest()->first();

   $inv->syncProductInventory($product);
});


Route::get('syn-inventory-man', function() {
    $helper = new HelperController();
    $retailer_products = RetailerProduct::whereNotNull('shopify_id')->whereNotNull('shop_id')->get();

    foreach ($retailer_products as $retailer_product){
        $shop = $helper->getSpecificShop($retailer_product->shop_id);
        if($shop != null){
            $resp =  $shop->api()->rest('GET', '/admin/api/2019-10/products/'.$retailer_product->shopify_id.'.json');
            if($resp->errors)
                continue;
            $variants = $resp->body->product->variants;
            foreach ($variants as $variant) {
                $productdata = [
                    "variant" => [
                        "fulfillment_service" => "AwarenessDropshipping",
                        'inventory_management' => 'AwarenessDropshipping',
                    ]
                ];

                $resp =  $shop->api()->rest('PUT', '/admin/api/2019-10/products/'.$retailer_product->shopify_id.'/variants/'.$variant->id.'.json',$productdata);
                dump($resp);

            }
        }

    }

});


//Route::get('delete-variants', function() {
//   $products = Product::all();
//   $count = 0;
//
//   foreach ($products as $product) {
//       if($product->hasVariants()->count() == 2) {
//           $variants = $product->hasVariants;
//           if($variants[0]->option == $variants[1]->title)
//           {
//
//           }
//       }
//   }
//
//   dd($count);
//});
