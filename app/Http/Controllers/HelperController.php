<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OhMyBrew\ShopifyApp\Models\Shop;

class HelperController extends Controller
{
    public function getShop(){
        $shop = Shop::where('shopify_domain','fantasy-supplier.myshopify.com')->first();
        return $shop;

    }
}
