<?php

namespace App\Http\Controllers;

use App\Country;
use App\Customer;
use App\OrderLog;
use App\Product;
use App\ProductVariant;
use App\RetailerOrder;
use App\RetailerOrderLineItem;
use App\RetailerProduct;
use App\RetailerProductVariant;
use App\ShippingRate;
use App\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomOrderController extends Controller
{
    public function index(Request $request){
        $orders  = RetailerOrder::where('user_id',Auth::id())->where('custom',1)->newQuery();
        $orders = $orders->orderBy('created_at','DESC')->paginate(30);

        return view('non_shopify_users.orders.index')->with([
            'orders' => $orders
        ]);
    }

    public function show_create_form(){
        $products = Product::query();
        $customers = Customer::where('user_id',Auth::id())->get();
        return view('non_shopify_users.orders.create')->with([
            'products' => $products->paginate(50),
            'customers' => $customers,
            'countries' => Country::all(),
        ]);
    }

    public function getShippingRate(Request $request){
        if($request->input('variant_selection') != '0'){
            $total_weight = 0;
            $country = $request->input('country');
            foreach ($request->input('line_items') as $index => $item){
                $v = ProductVariant::find($item);
                if($v->linked_product != null){
                    $total_weight = $total_weight + ( $v->linked_product->weight *  $request->input('quantity')[$index] );
                }
            }
            $zoneQuery = Zone::query();
            $zoneQuery->whereHas('has_countries',function ($q) use ($country){
                $q->where('name',$country);
            });
            $zoneQuery = $zoneQuery->pluck('id')->toArray();

//            dd($total_weight);
            $shipping_rates = ShippingRate::where('type','weight')->whereIn('zone_id',$zoneQuery)->newQuery();
            $shipping_rates->whereRaw('min <='.$total_weight);
            $shipping_rates->whereRaw('max >='.$total_weight);
            $shipping_rates =  $shipping_rates->first();
            if($shipping_rates != null){
                $rate = $shipping_rates->shipping_price;
            }
            else{
                $rate = 0;
            }

            return response()->json([
                'rate' => $rate,
                'message' => 'success'
            ]);

        }
    }

    public function find_products(Request $request){
        $products = Product::query();
        if($request->has('search')){
            $products->where('title','LIKE','%'.$request->input('search').'%');
            $products->orWhereHas('hasVariants',function ($q) use ($request){
                $q->where('title','LIKE','%'.$request->input('search').'%');
            });
        }
        $html = view('non_shopify_users.orders.product-browse-section')->with([
            'products' => $products->paginate(50),
        ])->render();

        return response()->json([
            'html' => $html
        ]);

    }

    public function get_selected_variants(Request $request){

        $selectedVaraints = ProductVariant::whereIn('id',$request->input('variants'))->get();
        $total_cost = 0;
        foreach ($selectedVaraints as $varaint){
            $total_cost = $total_cost + $varaint->price;
        }
        $html = view('non_shopify_users.orders.selected-varaint-section')->with([
            'variants' => $selectedVaraints,
            'total' => $total_cost
        ])->render();

        return response()->json([
            'html' => $html
        ]);

    }

    public function save_draft_order(Request $request){

        $count = RetailerOrder::all()->count();
        $new = new RetailerOrder();
        $new->email = $request->input('email');
        $count = $count+1;
        $new->name = '#WF'.$count;

        $new->taxes_included = '0';
        $new->total_tax = '0';
        $new->currency = 'USD';
        $new->total_discounts = '0';


        if (Customer::where('email',$request->input('email'))->exists()){
            $customer = Customer::where('email',$request->input('email'))->first();
            $new->customer_id = $customer->id;
        }
        else{
            $customer = new Customer();
            $customer->first_name = $request->input('c_first_name');
            $customer->last_name = $request->input('c_last_name');
            $customer->email = $request->input('email');
            $customer->user_id = Auth::id();
            $customer->save();
            $new->customer_id = $customer->id;
        }

        $new->shipping_address = json_encode($request->except(['line_items','quantity','c_first_name','c_last_name','_token','email']),true);


        $new->status = 'new';
        $new->user_id = Auth::id();
        $new->fulfilled_by = 'fantasy';
        $new->sync_status = 1;
        $new->save();
        $cost_to_pay = 0;
        $total_weight = 0;

        foreach ($request->input('line_items') as $index =>  $item){
            $variant = ProductVariant::find($item);
            if($variant != null){
                $new_line = new RetailerOrderLineItem();
                $new_line->retailer_order_id = $new->id;
                $new_line->shopify_product_id = $variant->linked_product->shopify_id;
                $new_line->shopify_variant_id = $variant->shopify;
                $new_line->title = $variant->linked_product->title;
                $new_line->quantity = $request->input('quantity')[$index];
                $new_line->sku = $variant->sku;
                $new_line->variant_title = $variant->variant_title;
                $new_line->title = $variant->title;
                $new_line->vendor = $variant->linked_product->title;
                $new_line->price = $variant->price;
                $new_line->requires_shipping = 'true';
                $new_line->name = $variant->linked_product->title.' - '. $variant->title;
                $new_line->fulfillable_quantity = $request->input('quantity')[$index];
                $new_line->fulfilled_by = 'Fantasy';
                $new_line->cost = $variant->price;
                $cost_to_pay = $cost_to_pay + $variant->price * $request->input('quantity')[$index];
                $total_weight = $total_weight + $variant->linked_product->weight;
                $new_line->save();
            }

        }

        $new->subtotal_price = $cost_to_pay;
        $new->shipping_price = $request->input('shipping_price');
        $total_cost = $cost_to_pay + $request->input('shipping_price');
        $new->total_weight = $total_weight;
        $new->total_price = $total_cost;
        $new->cost_to_pay = $total_cost;
        $new->custom = 1;
        $new->save();


        /*Maintaining Log*/
        $order_log =  new OrderLog();
        $order_log->message = "Custom Order Created to WeFullFill on ".date_create($new->created_at)->format('d M, Y h:i a');
        $order_log->status = "Newly Synced";
        $order_log->retailer_order_id = $new->id;
        $order_log->save();

        if($request->input('payment-option') == 'draft'){
            return redirect()->route('users.order.view',$new->id)->with('success','Custom Order Created Successfully');
        }
        elseif($request->input('payment-option') == 'paypal'){
            return redirect()->route('store.order.paypal.pay',$new->id);
        }
        else{
            return redirect()->route('store.order.wallet.pay',$new->id);
        }


    }

    public function view_order($id){
        $order  = RetailerOrder::find($id);
        if($order != null){
            return view('non_shopify_users.orders.view')->with([
                'order' => $order
            ]);
        }
    }

    public function delete($id){
        $r = RetailerOrder::find($id);
        foreach ($r->line_items as $i){
            $i->delete();
        }
        foreach ($r->fulfillments as $f){
            foreach ($f->line_items as $item){
                $item->delete();
            }
            $f->delete();
        }
        $r->delete();
        return redirect()->back()->with('success','Order Deleted Successfully!');
    }


}
