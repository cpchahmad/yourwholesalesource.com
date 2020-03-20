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
use OhMyBrew\ShopifyApp\Models\Shop;
use Spatie\Permission\Models\Role;

class HelperController extends Controller
{
    public function getShop(){
        $shop = Shop::where('shopify_domain','fantasy-supplier.myshopify.com')->first();
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

                return "Database Reset successfully executed";
            }
        }
        else{
           return 'Please Enter Password for Reset';
        }
    }
}
