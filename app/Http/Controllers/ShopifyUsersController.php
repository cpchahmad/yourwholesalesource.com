<?php

namespace App\Http\Controllers;

use App\Product;
use App\RetailerOrder;
use App\RetailerProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShopifyUsersController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();

        if ($request->has('date-range')) {
            $date_range = explode('-',$request->input('date-range'));
            $start_date = $date_range[0];
            $end_date = $date_range[1];
            $comparing_start_date = Carbon::parse($start_date)->format('Y-m-d');
            $comparing_end_date = Carbon::parse($end_date)->format('Y-m-d');

            $orders = RetailerOrder::whereIN('paid',[1,2])->where('user_id',$user->id)->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->count();
            $sales = RetailerOrder::whereIN('paid',[1,2])->where('user_id',$user->id)->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->sum('cost_to_pay');
            $refunds =  RetailerOrder::whereIN('paid',[2])->where('user_id',$user->id)->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->count();
            $profit = RetailerOrder::whereIN('paid',[1])->where('user_id',$user->id)->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->sum('cost_to_pay');


            $ordersQ = DB::table('retailer_orders')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(cost_to_pay) as total_sum'))
                ->where('user_id',$user->id)
                ->whereIn('paid',[1,2])
                ->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])
                ->groupBy('date')
                ->get();


            $ordersQP = DB::table('retailer_orders')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(cost_to_pay) as total_sum'))
                ->where('user_id',$user->id)
                ->whereIn('paid',[1])
                ->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])
                ->groupBy('date')
                ->get();

            $ordersQR = DB::table('retailer_orders')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(cost_to_pay) as total_sum'))
                ->where('user_id',$user->id)
                ->whereIn('paid',[2])
                ->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])
                ->groupBy('date')
                ->get();





        } else {

            $orders = RetailerOrder::whereIN('paid',[1,2])->where('user_id',$user->id)->count();
            $sales = RetailerOrder::whereIN('paid',[1,2])->where('user_id',$user->id)->sum('cost_to_pay');
            $refunds = RetailerOrder::whereIN('paid',[2])->where('user_id',$user->id)->sum('cost_to_pay');
            $profit = RetailerOrder::whereIN('paid',[1])->where('user_id',$user->id)->sum('cost_to_pay');

            $ordersQ = DB::table('retailer_orders')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(cost_to_pay) as total_sum'))
                ->where('user_id',$user->id)
                ->whereIn('paid',[1,2])
                ->groupBy('date')
                ->get();


            $ordersQP = DB::table('retailer_orders')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(cost_to_pay) as total_sum'))
                ->where('user_id',$user->id)
                ->whereIn('paid',[1])
                ->groupBy('date')
                ->get();

            $ordersQR = DB::table('retailer_orders')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(cost_to_pay) as total_sum'))
                ->where('user_id',$user->id)
                ->whereIn('paid',[2])
                ->groupBy('date')
                ->get();

        }


        $graph_one_order_dates = $ordersQ->pluck('date')->toArray();
        $graph_one_order_values = $ordersQ->pluck('total')->toArray();
        $graph_two_order_values = $ordersQ->pluck('total_sum')->toArray();

        $graph_four_order_dates = $ordersQP->pluck('date')->toArray();
        $graph_four_order_values = $ordersQP->pluck('total_sum')->toArray();

        $graph_three_order_dates = $ordersQR->pluck('date')->toArray();
        $graph_three_order_values = $ordersQR->pluck('total_sum')->toArray();


        $top_products =  Product::join('retailer_order_line_items',function($join) use ($user){
            $join->on('retailer_order_line_items.shopify_product_id','=','products.shopify_id')
                ->join('retailer_orders',function($o) use ($user){
                    $o->on('retailer_order_line_items.retailer_order_id','=','retailer_orders.id')
                        ->whereIn('paid',[1,2])
                    ->where('user_id',$user->id);
                });
        })->select('products.*',DB::raw('sum(retailer_order_line_items.quantity) as sold'),DB::raw('sum(retailer_order_line_items.cost) as selling_cost'))
            ->groupBy('products.id')
            ->orderBy('sold','DESC')
            ->get()
            ->take(10);



        return view('non_shopify_users.index')->with([
            'date_range' => $request->input('date-range'),
            'orders' => $orders,
            'profit' => $profit,
            'sales' =>$sales,
            'refunds' => $refunds,
            'graph_one_labels' => $graph_one_order_dates,
            'graph_one_values' => $graph_one_order_values,
            'graph_two_values' => $graph_two_order_values,
            'graph_three_labels' => $graph_three_order_dates,
            'graph_three_values' => $graph_three_order_values,
            'graph_four_values' => $graph_four_order_values,
            'graph_four_labels' => $graph_four_order_dates,
            'top_products' => $top_products,
        ]);

    }
    public function stores(){
        $shops = auth()->user()->has_shops;
        return view('non_shopify_users.stores')->with([
            'shops' => $shops
        ]);
    }
}
