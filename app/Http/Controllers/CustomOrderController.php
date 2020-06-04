<?php

namespace App\Http\Controllers;

use App\AdminSetting;
use App\Category;
use App\Country;
use App\Customer;
use App\Imports\UsersImport;
use App\OrderLog;
use App\OrderTransaction;
use App\Product;
use App\ProductVariant;
use App\RetailerOrder;
use App\RetailerOrderLineItem;
use App\RetailerProduct;
use App\RetailerProductVariant;
use App\ShippingRate;
use App\Ticket;
use App\TicketCategory;
use App\User;
use App\UserFile;
use App\UserFileTemp;
use App\Wishlist;
use App\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Srmklive\PayPal\Services\ExpressCheckout;

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
        $products->whereHas('hasVariants',function (){

        });

        $customers = Customer::where('user_id',Auth::id())->get();
        return view('non_shopify_users.orders.create')->with([
            'products' => $products->get(),
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
                $q->orwhere('sku','LIKE','%'.$request->input('search').'%');
            });
        }
        $html = view('non_shopify_users.orders.product-browse-section')->with([
            'products' => $products->get(),
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
                $new_line->shopify_variant_id = $variant->shopify_id;
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
        $settings = AdminSetting::all()->first();
        if($order != null){
            return view('non_shopify_users.orders.view')->with([
                'order' => $order,
                'settings' =>$settings
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

    public function wefullfill_products(Request $request){
        $categories = Category::all();
        $productQuery = Product::where('status',1)->newQuery();
        if($request->has('category')){
            $productQuery->whereHas('has_categories',function($q) use ($request){
                return $q->where('title','LIKE','%'.$request->input('category').'%');
            });
        }
        if($request->has('search')){
            $productQuery->where('title','LIKE','%'.$request->input('search').'%')->orWhere('tags','LIKE','%'.$request->input('search').'%');
        }
        $products = $productQuery->paginate(12);

        return view('non_shopify_users.product.wefullfill_products')->with([
            'categories' => $categories,
            'products' => $products,
            'search' =>$request->input('search')
        ]);
    }
    public function view_fantasy_product($id){
        $product = Product::find($id);
        return view('non_shopify_users.product.view_product')->with([
            'product' => $product,
        ]);
    }

    public function process_file(Request $request){
        if($request->hasFile('import_order_file')){

            $image =  $request->file('import_order_file');
            $destinationPath = 'import-orders/';
            $filename = now()->format('YmdHi') . str_replace([' ','(',')'], '-', $image->getClientOriginalName());
            $image->move($destinationPath, $filename);

            $new_file = new UserFile();
            $new_file->file = $filename;
            $new_file->user_id = Auth::id();
            $new_file->save();

            Excel::import(new UsersImport($new_file->id,Auth::id()),'import-orders/'.$filename);
            $process_data = UserFileTemp::where('user_id',$new_file->user_id)->where('file_id',$new_file->id)->get()->groupBy('order_number');
            foreach ($process_data as $index => $data){
                $order_name = $index;
                $atleast_one_varaint = false;
                foreach ($data as $item){
                    if(ProductVariant::where('sku',$item->sku)->exists()){
                        $atleast_one_varaint = true;
                        break;
                    }
                }
                if($atleast_one_varaint){
                    $new = new RetailerOrder();
                    $new->name = '#WFI-'.$order_name;
                    $new->taxes_included = '0';
                    $new->total_tax = '0';
                    $new->currency = 'USD';
                    $new->total_discounts = '0';

                    $new_user = false;
                    foreach ($data as $item){
                        if (Customer::where('email',$item->email)->exists()){
                            $customer = Customer::where('email',$item->email)->first();
                            $new->customer_id = $customer->id;
                            $new_user = false;
                            break;
                        }
                        else{
                            $new_user = true;
                        }
                    }
                    $name = explode(' ',$data[0]->name);
                    $first_name = $name[0];
                    if(array_key_exists(1,$name)){
                        $last_name = $name[1];
                    }
                    $address1 = $data[0]->address1;
                    $address2 = $data[0]->address2;
                    $city = $data[0]->city;
                    $postcode = $data[0]->postcode;
                    $country = $data[0]->country;
                    $phone = $data[0]->phone;
                    $email = $data[0]->email;

                    if($new_user){
                        $customer = new Customer();
                        $customer->first_name = $first_name;
                        $customer->last_name = $last_name;
                        $customer->email = $email;
                        $customer->phone = $phone;
                        $customer->user_id = Auth::id();
                        $customer->save();
                        $new->customer_id = $customer->id;

                    }
                    $shipping_address = [
                        "first_name"=>$first_name,
                        "last_name"=>$last_name,
                        "address1"=>$address1,
                        "address2"=>$first_name,
                        "city"=>$city,
                        "province" => "",
                        "zip"=>$postcode,
                        "country"=>$country,
                    ];


                    $new->shipping_address = json_encode($shipping_address,true);

                    $new->email = $email;
                    $new->status = 'new';
                    $new->user_id = Auth::id();
                    $new->fulfilled_by = 'fantasy';
                    $new->sync_status = 1;
                    $new->save();
                    $cost_to_pay = 0;
                    $total_weight = 0;

                    foreach ($data as $item){
                        $variant = ProductVariant::where('sku',$item->sku)->first();
                        if($variant != null){

                            $item->status = 1;
                            $item->order_id = $new->id;
                            $item->save();

                            $new_line = new RetailerOrderLineItem();
                            $new_line->retailer_order_id = $new->id;
                            $new_line->shopify_product_id = $variant->linked_product->shopify_id;
                            $new_line->shopify_variant_id = $variant->shopify_id;
                            $new_line->title = $variant->linked_product->title;
                            $new_line->quantity = $item->quantity;
                            $new_line->sku = $variant->sku;
                            $new_line->variant_title = $variant->variant_title;
                            $new_line->title = $variant->title;
                            $new_line->vendor = $variant->linked_product->title;
                            $new_line->price = $variant->price;
                            $new_line->requires_shipping = 'true';
                            $new_line->name = $variant->linked_product->title.' - '. $variant->title;
                            $new_line->fulfillable_quantity =$item->quantity;
                            $new_line->fulfilled_by = 'Fantasy';
                            $new_line->cost = $variant->price;
                            $cost_to_pay = $cost_to_pay + $variant->price * $item->quantity;;
                            $total_weight = $total_weight + $variant->linked_product->weight;
                            $new_line->save();
                        }

                    }

                    $new->subtotal_price = $cost_to_pay;
                    $new->shipping_price = 0;
                    $total_cost = $cost_to_pay;
                    $new->total_weight = $total_weight;
                    $new->total_price = $total_cost;
                    $new->cost_to_pay = $total_cost;
                    $new->custom = 1;
                    $new->save();


                    $zoneQuery = Zone::query();
                    $zoneQuery->whereHas('has_countries',function ($q) use ($country){
                        $q->where('name',$country);
                    });
                    $zoneQuery = $zoneQuery->pluck('id')->toArray();

                    $shipping_rates = ShippingRate::where('type','weight')->whereIn('zone_id',$zoneQuery)->newQuery();
                    $shipping_rates->whereRaw('min <='.$total_weight);
                    $shipping_rates->whereRaw('max >='.$total_weight);
                    $shipping_rates =  $shipping_rates->first();
                    if($shipping_rates != null){
                        $new->shipping_price = $shipping_rates->shipping_price;
                        $new->total_price =  $new->total_price + $shipping_rates->shipping_price;
                        $new->save;
                    }
                    else{
                        $new->shipping_price = 0;
                        $new->save;
                    }

                    /*Maintaining Log*/
                    $order_log =  new OrderLog();
                    $order_log->message = "Custom Order Created to WeFullFill through file import on ".date_create($new->created_at)->format('d M, Y h:i a');
                    $order_log->status = "Newly Synced";
                    $order_log->retailer_order_id = $new->id;
                    $order_log->save();
                }
            }

            $custom_orders = RetailerOrder::where('user_id',Auth::id())->newQuery();
            $custom_orders->whereHas('imported',function ($q) use ($new_file){
                $q->where('file_id','=',$new_file->id);
            });

            $temp_data = UserFileTemp::where('user_id',$new_file->user_id)->where('file_id',$new_file->id)->where('status',0)->get();

            return view('non_shopify_users.orders.processed')->with([
                'orders' => $custom_orders->get(),
                'data' => $temp_data,
                'file' => $new_file
            ]);
        }
        else{
            return redirect()->back();
        }
    }

    public function files(Request $request){
        $files = UserFile::where('user_id',Auth::id())->orderBy('created_at')->get();
        return view('non_shopify_users.orders.import_files')->with([
            'files' => $files,
        ]);
    }

    public function file(Request $request){
        $new_file = UserFile::find($request->id);
        $custom_orders = RetailerOrder::where('user_id',Auth::id())->newQuery();
        $custom_orders->whereHas('imported',function ($q) use ($new_file){
            $q->where('file_id','=',$new_file->id);
        });

        $temp_data = UserFileTemp::where('user_id',$new_file->user_id)->where('file_id',$new_file->id)->where('status',0)->get();

        return view('non_shopify_users.orders.processed')->with([
            'orders' => $custom_orders->get(),
            'data' => $temp_data,
            'file' => $new_file
        ]);
    }


    public function bulk_import_order_paypal(Request $request){
        $new_file = UserFile::find($request->id);
        $custom_orders = RetailerOrder::where('user_id',Auth::id())->where('custom',1)->where('paid',0)->newQuery();
        $custom_orders->whereHas('imported',function ($q) use ($new_file){
            $q->where('file_id','=',$new_file->id);
        });
        $custom_orders = $custom_orders->get();
        if(count($custom_orders) > 0){
            $items = [];
            $order_total = 0;
            foreach ($custom_orders as $retailer_order){
                $order_total = $order_total + $retailer_order->cost_to_pay;

                /*adding order-lime-items for paying through paypal*/
                foreach ($retailer_order->line_items as $item){
                    array_push($items,[
                        'name' => $item->title .' - '.$item->variant_title,
                        'price' => $item->cost,
                        'qty' =>$item->quantity
                    ]);
                }
                if($retailer_order->shipping_price != null){
                    array_push($items,[
                        'name' => $retailer_order->name .' Shipping Price',
                        'price' => $retailer_order->shipping_price,
                        'qty' =>1
                    ]);
                }
            }


            $data = [];
            $data['items'] = $items;
            $data['invoice_id'] = 'WeFullFill-Import-Bulk-Pay'.rand(1,1000);
            $data['invoice_description'] = "WeFullFill-Import-Bulk-Pay-Invoice-".rand(1,1000);;
            $data['return_url'] = route('users.orders.bulk.paypal.success',$new_file->id);
            $data['cancel_url'] = route('users.orders.bulk.paypal.cancel',$new_file->id);
            $data['total'] = $order_total;

            $provider = new ExpressCheckout;
            $response = $provider->setExpressCheckout($data);
            foreach ($custom_orders as $retailer_order){
                $retailer_order->paypal_token  = $response['TOKEN'];
                $retailer_order->save();
            }

            return redirect($response['paypal_link']);
        }
    }

    public function bulk_import_order_paypal_cancel(Request $request){
        return redirect()->route('users.files.view',$request->id)->with('error','Paypal Transaction Process cancelled successfully');
    }
    public function bulk_import_order_paypal_success(Request $request){
        $file = UserFile::find($request->id);
        $provider = new ExpressCheckout;
        $response = $provider->getExpressCheckoutDetails($request->token);

        $custom_orders = RetailerOrder::where('user_id',Auth::id())->where('custom',1)
            ->where('paid',0)
            ->where('paypal_token',$request->token)
            ->newQuery();
        $custom_orders->whereHas('imported',function ($q) use ($file){
            $q->where('file_id','=',$file->id);
        });
        $custom_orders = $custom_orders->get();

        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING']) && $file  != null && count($custom_orders) > 0)
        {
            foreach ($custom_orders as $retailer_order){
                $retailer_order->paypal_payer_id =$request->PayerID;
                $new_transaction = new OrderTransaction();
                $new_transaction->amount =  $retailer_order->cost_to_pay;
                $new_transaction->name = $response['FIRSTNAME'].' '.$response['LASTNAME'];
                $new_transaction->retailer_order_id = $retailer_order->id;
                $new_transaction->paypal_payment_id = $request->PayerID;
                $new_transaction->user_id = $retailer_order->user_id;
                $new_transaction->shop_id = $retailer_order->shop_id;
                $new_transaction->save();

                $retailer_order->paid = 1;
                $retailer_order->status = 'Paid';
                $retailer_order->pay_by = 'Paypal';
                $retailer_order->save();

                /*Maintaining Log*/
                $order_log =  new OrderLog();
                $order_log->message = "An amount of ".$new_transaction->amount." USD used to WeFullFill through BULK PAYPAL PAYMENT of ". $response['AMT']." USD on ".date_create($new_transaction->created_at)->format('d M, Y h:i a')." for further process";
                $order_log->status = "paid";
                $order_log->retailer_order_id = $retailer_order->id;
                $order_log->save();

            }
             return redirect()->route('users.files.view',$request->id)->with('success','Bulk Payment Processed Successfully!');

        }
        else{
            return redirect()->route('users.files.view',$request->id)->with('error','Bulk Orders Not Found!');
        }
    }

    public function helpcenter(Request $request){
        $user = User::find(Auth::id());
        $tickets = Ticket::where('user_id',$user->id)->where('source','non-shopify-user')->newQuery();
        $tickets = $tickets->paginate(30);

        return view('non_shopify_users.help-center.index')->with([
            'user' => $user,
            'tickets' => $tickets,
            'categories' => TicketCategory::all(),
            ]);
    }

    public function view_ticket(Request $request){
        $user = User::find(Auth::id());
        $ticket = Ticket::find($request->id);
        return view('non_shopify_users.help-center.view')->with([
            'user' => $user,
            'ticket' => $ticket,
        ]);
    }

    public function wishlist(Request $request){
        $user = User::find(Auth::id());
        $wishlists = Wishlist::where('user_id',$user->id)->newQuery();
        $wishlists = $wishlists->orderBy('created_at','DESC')->paginate(30);

        return view('non_shopify_users.wishlist.index')->with([
            'user' => $user,
            'wishlist' => $wishlists,
            'countries' => Country::all(),
        ]);
    }

    public function view_wishlist(Request $request){
        $user = User::find(Auth::id());
        $wishlists = Wishlist::find($request->id);
        return view('non_shopify_users.wishlist.view')->with([
            'user' => $user,
            'wishlist' => $wishlists,
        ]);
    }

}
