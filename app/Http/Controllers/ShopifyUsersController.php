<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopifyUsersController extends Controller
{
    public function index(){
        return view('non_shopify_users.index');
    }
    public function stores(){
        $shops = auth()->user()->has_shops;
        return view('non_shopify_users.stores')->with([
            'shops' => $shops
        ]);
    }
}
