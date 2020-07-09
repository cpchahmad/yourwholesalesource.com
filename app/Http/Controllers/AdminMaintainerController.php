<?php

namespace App\Http\Controllers;

use App\RetailerOrder;
use Illuminate\Http\Request;

class AdminMaintainerController extends Controller
{
    public function sync_order_to_admin_store(RetailerOrder $order){
        dd($order);
    }
}
