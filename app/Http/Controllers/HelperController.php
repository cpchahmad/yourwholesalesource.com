<?php

namespace App\Http\Controllers;

use App\AdditionalTab;
use App\Category;
use App\DefaultInfo;
use App\Image;
use App\Product;
use App\ProductVariant;
use App\ShippingRate;
use App\SubCategory;
use App\User;
use App\WarnedPlatform;
use App\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use OhMyBrew\ShopifyApp\Models\Shop;
use OhMyBrew\ShopifyApp\ShopifyApp;
use Spatie\Permission\Models\Role;

class HelperController extends Controller
{
    public function getShop(){
        $current_shop = \OhMyBrew\ShopifyApp\Facades\ShopifyApp::shop();
        return Shop::where('shopify_domain',$current_shop->shopify_domain)->first();
    }
    public function getLocalShop(){
        /*Ossiset Shop Model*/
        $shop =  \OhMyBrew\ShopifyApp\Facades\ShopifyApp::shop();
        /*Local Shop Model!*/
        $shop= \App\Shop::find($shop->id);
        return $shop;
    }
    public function reset_all(Request $request){
        if($request->has('pass')){
            if($request->input('pass')== 'fantasy-reset')
            {
                AdditionalTab::truncate();
                Category::truncate();
                DB::table('category_product')->truncate();
                DB::table('cities')->truncate();
                DB::table('countries')->truncate();
                DB::table('country_zone')->truncate();
                DefaultInfo::truncate();
                Image::truncate();
                Product::truncate();
                DB::table('product_platform')->truncate();
                ProductVariant::truncate();
                ShippingRate::truncate();
                DB::table('states')->truncate();
                DB::table('subcategory_product')->truncate();
                SubCategory::truncate();
                User::truncate();
                WarnedPlatform::truncate();
                Zone::truncate();
                DB::table('user_shop')->truncate();
                DB::table('model_has_roles')->truncate();

                Artisan::call('db:seed');
                $this->SuperAdminCreate();

                return "Database Reset successfully executed";
            }
        }
        else{
           return 'Please Enter Password for Reset';
        }
    }
    public function SuperAdminCreate()
    {
        if (!User::where('email', 'super_admin@wefullfill.com')->exists()) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'super_admin@wefullfill.com',
                'password' => Hash::make('wefullfill@admin'),
            ]);
        }
        if (!User::where('email', 'super_admin@wefullfill.com')->exists()) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'admin@wefullfill.com',
                'password' => Hash::make('wefullfill@admin'),
            ]);
        }
    }
}
