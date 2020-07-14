<?php

namespace App\Http\Controllers;

use App\Category;
use App\Country;
use App\Customer;
use App\Notification;
use App\OrderTransaction;
use App\Product;
use App\Refund;
use App\RetailerImage;
use App\RetailerOrder;
use App\RetailerProduct;
use App\ShippingRate;
use App\Shop;
use App\Ticket;
use App\TicketCategory;
use App\User;
use App\Wishlist;
use App\Zone;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;
use function foo\func;

class SingleStoreController extends Controller
{
    private $helper;

    /**
     * SingleStoreController constructor.
     * @param $helper
     */
    public function __construct()
    {
        $this->helper = new HelperController();
    }

    public function index(Request $request){

        $shop = $this->helper->getLocalShop();

        if ($request->has('date-range')) {
            $date_range = explode('-',$request->input('date-range'));
            $start_date = $date_range[0];
            $end_date = $date_range[1];
            $comparing_start_date = Carbon::parse($start_date)->format('Y-m-d');
            $comparing_end_date = Carbon::parse($end_date)->format('Y-m-d');

            $orders = RetailerOrder::whereIN('paid',[1,2])->where('shop_id',$shop->id)->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->count();
            $sales = RetailerOrder::whereIN('paid',[1,2])->where('shop_id',$shop->id)->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->sum('cost_to_pay');
            $products = RetailerProduct::where('shop_id',$shop->id)->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->count();
            $profit = RetailerOrder::whereIN('paid',[1])->where('shop_id',$shop->id)->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->sum('cost_to_pay');


            $ordersQ = DB::table('retailer_orders')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(cost_to_pay) as total_sum'))
                ->where('shop_id',$shop->id)
                ->whereIn('paid',[1,2])
                ->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])
                ->groupBy('date')
                ->get();


            $ordersQP = DB::table('retailer_orders')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(cost_to_pay) as total_sum'))
                ->where('shop_id',$shop->id)
                ->whereIn('paid',[1])
                ->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])
                ->groupBy('date')
                ->get();

            $productQ = DB::table('retailer_products')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                ->where('shop_id',$shop->id)
                ->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])
                ->groupBy('date')
                ->get();





        } else {

            $orders = RetailerOrder::whereIN('paid',[1,2])->where('shop_id',$shop->id)->count();
            $sales = RetailerOrder::whereIN('paid',[1,2])->where('shop_id',$shop->id)->sum('cost_to_pay');
            $products = RetailerProduct::where('shop_id',$shop->id)->count();
            $profit = RetailerOrder::whereIN('paid',[1])->where('shop_id',$shop->id)->sum('cost_to_pay');

            $ordersQ = DB::table('retailer_orders')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(cost_to_pay) as total_sum'))
                ->where('shop_id',$shop->id)
                ->whereIn('paid',[1,2])
                ->groupBy('date')
                ->get();


            $ordersQP = DB::table('retailer_orders')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(cost_to_pay) as total_sum'))
                ->where('shop_id',$shop->id)
                ->whereIn('paid',[1])

                ->groupBy('date')
                ->get();

            $productQ = DB::table('retailer_products')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                ->where('shop_id',$shop->id)
                ->groupBy('date')
                ->get();

        }


        $graph_one_order_dates = $ordersQ->pluck('date')->toArray();
        $graph_one_order_values = $ordersQ->pluck('total')->toArray();
        $graph_two_order_values = $ordersQ->pluck('total_sum')->toArray();

        $graph_three_order_dates = $ordersQP->pluck('date')->toArray();
        $graph_three_order_values = $ordersQP->pluck('total_sum')->toArray();

        $graph_four_order_dates = $productQ->pluck('date')->toArray();
        $graph_four_order_values = $productQ->pluck('total')->toArray();


        $top_products =  Product::join('retailer_products',function($join) use ($shop){
            $join->on('products.id','=','retailer_products.linked_product_id')
                ->where('retailer_products.shop_id' ,'=',$shop->id)
                ->join('retailer_order_line_items',function ($j){
                    $j->on('retailer_order_line_items.shopify_product_id','=','retailer_products.shopify_id')
                        ->join('retailer_orders',function($o){
                            $o->on('retailer_order_line_items.retailer_order_id','=','retailer_orders.id')
                                ->whereIn('paid',[1,2]);
                        });
                });
        })->select('products.*',DB::raw('sum(retailer_order_line_items.quantity) as sold'),DB::raw('sum(retailer_order_line_items.cost) as selling_cost'))
            ->groupBy('products.id')
            ->orderBy('sold','DESC')
            ->get()
            ->take(10);



        return view('single-store.dashboard')->with([
            'date_range' => $request->input('date-range'),
            'orders' => $orders,
            'profit' => $profit,
            'sales' =>$sales,
            'products' => $products,
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
    public function wefullfill_products(Request $request){


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.ipdata.co/country_name?api-key=878bb41d66f819dc08ffdec4fc14d763252af1c959f305c712443925');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $country = 'United States';
        }
        curl_close($ch);
        $country = $result;

        $categories = Category::all();
        $productQuery = Product::where('status',1)->newQuery();

        $productQuery->where('global',0)->whereHas('has_preferences',function ($q){
            return $q->where('shopify_domain','=',$this->helper->getLocalShop()->shopify_domain);
        });

        $productQuery->orwhere('global',1);

        if($request->has('category')){
            $productQuery->whereHas('has_categories',function($q) use ($request){
                return $q->where('title','LIKE','%'.$request->input('category').'%');
            });
        }
        if($request->has('search')){
            $productQuery->where('title','LIKE','%'.$request->input('search').'%')->orWhere('tags','LIKE','%'.$request->input('search').'%');
        }
        if($request->has('tag')){
            $productQuery->orWhere('tags','LIKE','%'.$request->input('tag').'%');
        }
        if($request->has('filter')){
            if($request->input('filter') == 'most-order'){
//                $productQuery->withCount(['has_retailer_products' => function(Builder  $q){
//                    $q->whereHas('hasVariants',function ($va){
//                        dd($va);
//                    });
//                }]);
                $products = $productQuery->paginate(12);

            }
            elseif($request->input('filter') == 'most-imported'){
                $products =   $productQuery->withCount(['has_imported'])->orderBy('has_imported_count', 'DESC')->paginate(12);
            }
            elseif($request->input('filter') == 'new-arrival'){
                $products = $productQuery->orderBy('created_at', 'DESC')->paginate(12);

            }
        }
        else{
            $products = $productQuery->paginate(12);
        }



        foreach ($products as $product){
            $total_weight = $product->weight;
            $zoneQuery = Zone::query();
            $zoneQuery->whereHas('has_countries',function ($q) use ($country){
                $q->where('name',$country);
            });
            $zoneQuery = $zoneQuery->pluck('id')->toArray();

            $shipping_rates = ShippingRate::where('type','weight')->whereIn('zone_id',$zoneQuery)->newQuery();
//            $shipping_rates->whereRaw('min <='.$total_weight);
//            $shipping_rates->whereRaw('max >='.$total_weight);
            $shipping_rates =  $shipping_rates->first();
            if($shipping_rates != null){
                if($shipping_rates->shipping_price > 0){
                    if($shipping_rates->min > 0){
                        $ratio = $total_weight/$shipping_rates->min;
                        $product->new_shipping_price = '$'.number_format($shipping_rates->shipping_price*$ratio,2);;

                    }
                    else{
                        $product->new_shipping_price = 'Free Shipping';
                    }
//                    $product->new_shipping_price = '$'.number_format($shipping_rates->shipping_price,2);

                }
                else{
                    $product->new_shipping_price = 'Free Shipping';
                }
            }
            else{
                $product->new_shipping_price = 'Free Shipping';

            }

        }

        $shop= $this->helper->getLocalShop();
        return view('single-store.products.wefullfill_products')->with([
            'categories' => $categories,
            'products' => $products,
            'shop' => $shop,
            'search' =>$request->input('search'),
            'filter' => $request->input('filter')

        ]);
    }
    public function view_fantasy_product($id){
        $product = Product::find($id);
        $shop= $this->helper->getLocalShop();
        return view('single-store.products.view_product')->with([
            'product' => $product,
            'shop' => $shop
        ]);
    }
    public function view_my_product($id){
        $product = RetailerProduct::find($id);
        $shop= $this->helper->getLocalShop();
        return view('single-store.products.view_product')->with([
            'product' => $product,
            'shop' => $shop
        ]);
    }
    public function setting(){
        /*Ossiset Shop Model*/
        $shop =  ShopifyApp::shop();
        /*Local Shop Model!*/
        $shop= Shop::find($shop->id);
        if(count($shop->has_user) > 0){
            $associated_user =   $shop->has_user[0];
        }
        else{
            $associated_user = null;
        }
        return view('single-store.index')->with([
            'shop'=>$shop,
            'associated_user' =>$associated_user
        ]);
    }
    public function authenticate(Request $request){
        if(Auth::validate($request->except('_token'))){
            $authenticate = true;
        }
        else{
            $authenticate = false;
        }
        return response()->json([
            'authenticate' => $authenticate
        ]);
    }
    public function associate(Request $request){
        $user =  User::where('email',$request->input('email'))->first();
        $shop = Shop::where('shopify_domain',$request->input('store'))->first();
        if($user != null && $shop !=null){
            if(!in_array($shop->id,$user->has_shops->pluck('id')->toArray())){
                $user->has_shops()->attach([$shop->id]);
                return response()->json([
                    'status' => 'assigned'
                ]);
            }
            else{
                return response()->json([
                    'status' => 'already-assigned'
                ]);
            }
        }
        else{
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
    public function de_associate(Request $request){
        $shop = Shop::find($request->id);
        $user =  Auth::user();
        $user->has_shops()->detach([$shop->id]);
        return redirect()->back()->with('success','Store Removed Successfully!');
    }


    public function customers(Request $request){
        $customersQ  =Customer::where('shop_id',$this->helper->getShop()->id)->newQuery();
        $customers = $customersQ->paginate(30);
        return view('single-store.customers.index')->with([
            'customers' => $customers,
        ]);
    }

    public function customer_view($id){
        $customer = Customer::find($id);
        return view('single-store.customers.view')->with([
            'customer' => $customer,
        ]);
    }

    public function getCustomers(){
        $shop =  $this->helper->getShop();
        $response = $shop->api()->rest('GET', '/admin/api/2019-10/customers.json');
        if($response->errors){
            return redirect()->back();
        }
        else{
            $customers = $response->body->customers;
            foreach ($customers as $index => $customer){
                if (Customer::where('customer_shopify_id',$customer->id)->exists()){
                    $new_customer = Customer::where('customer_shopify_id',$customer->id)->first();
                }
                else{
                    $new_customer = new Customer();
                }
                $new_customer->customer_shopify_id = $customer->id;
                $new_customer->first_name = $customer->first_name;
                $new_customer->last_name = $customer->last_name;
                $new_customer->phone = $customer->phone;
                $new_customer->email = $customer->email;
                $new_customer->total_spent = $customer->total_spent;
                $new_customer->shop_id = $shop->id;
                $local_shop = $this->helper->getLocalShop();
                if(count($local_shop->has_user) > 0){
                    $new_customer->user_id = $local_shop->has_user[0]->id;
                }
                $new_customer->save();
            }
            return redirect()->back()->with('success','Customers Synced Successfully!');
        }

    }
    public function payment_history(Request $request){
        $shop = $this->helper->getLocalShop();
        $payments = OrderTransaction::where('shop_id',$shop->id)->newQuery();
        return view('single-store.orders.payment_history')->with([
            'payments' =>  $payments->orderBy('created_at')->paginate(20),
        ]);
    }

    public function tracking_info(Request $request){
        $shop = $this->helper->getLocalShop();
        $orders = RetailerOrder::where('shop_id',$shop->id)->newQuery();
        if($request->has('search')){
            $orders->where('name','LIKE','%'.$request->input('search').'%');
        }
        return view('single-store.orders.tracking_info')->with([
            'orders' =>  $orders->orderBy('created_at')->paginate(20),
            'search' =>$request->input('search')

        ]);
    }

    public function helpcenter(Request $request){
        $shop = $this->helper->getLocalShop();
        $tickets = Ticket::where('shop_id',$shop->id)->where('source','store')->newQuery();
        $tickets = $tickets->paginate(30);

        return view('single-store.help-center.index')->with([
            'shop' => $shop,
            'tickets' => $tickets,
            'categories' => TicketCategory::all(),
        ]);
    }

    public function wishlist(Request $request){
        $shop = $this->helper->getLocalShop();
        $wishlist = Wishlist::where('shop_id',$shop->id)->newQuery();
        $wishlist = $wishlist->orderBy('created_at','DESC')->paginate(30);

        return view('single-store.wishlist.index')->with([
            'shop' => $shop,
            'wishlist' => $wishlist,
            'countries' => Country::all(),
        ]);
    }
    public function view_wishlist(Request $request){
        $shops = $this->helper->getLocalShop();
        $wishlist = Wishlist::find($request->id);
        return view('single-store.wishlist.view')->with([
            'shop' => $shop,
            'wishlist' => $wishlist,
        ]);
    }

    public function view_ticket(Request $request){
        $shop = $this->helper->getLocalShop();
        $ticket = Ticket::find($request->id);
        return view('single-store.help-center.view')->with([
            'shop' => $shop,
            'ticket' => $ticket,
        ]);
    }

    public function calculate_shipping(Request $request){

        $total_weight = 0;
        if($request->has('country')){
            $country = $request->input('country');
        }
        else{
            $country = "United States";
//            $country = "Afghanistan";
        }

        $product = Product::find($request->input('product'));
        if($product != null){
            $total_weight = $product->weight;
        }
        else{
            $total_weight = 0;
        }


        $zoneQuery = Zone::query();
        $zoneQuery->whereHas('has_countries',function ($q) use ($country){
            $q->where('name',$country);
        });
        $zoneQuery = $zoneQuery->pluck('id')->toArray();

        $shipping_rates = ShippingRate::where('type','weight')->whereIn('zone_id',$zoneQuery)->newQuery();
//        $shipping_rates->whereRaw('min <='.$total_weight);
//        $shipping_rates->whereRaw('max >='.$total_weight);

        $shipping_rates =  $shipping_rates->get();

        foreach ($shipping_rates as $shipping_rate){
            if($shipping_rate->min > 0){
                $ratio = $total_weight/$shipping_rate->min;
                $shipping_rate->shipping_price =  $shipping_rate->shipping_price*$ratio;
            }
            else{
                $ratio = 0;
                $shipping_rate->shipping_price =  $shipping_rate->shipping_price*$ratio;
            }

        }

        $html = view('inc.calculate_shipping')->with([
            'countries' => Country::all(),
            'selected' => $country,
            'rates' =>$shipping_rates,
            'product'=>$request->input('product')
        ])->render();

        return response()->json([
            'html' => $html
        ]);


    }

    public function refunds(Request $request)
    {
        $shop = $this->helper->getLocalShop();
        $refunds = Refund::where('shop_id',$shop->id)->newQuery();
        if($request->has('search')){
            $refunds->where('order_name','LIKE','%'.$request->input('search').'%');
        }
        $orders = RetailerOrder::where('shop_id',$shop->id)->where('paid',1)->get();
        return view('single-store.orders.refunds')->with([
            'refunds' =>  $refunds->orderBy('created_at')->paginate(20),
            'search' =>$request->input('search'),
            'shop' => $shop,
            'orders' =>$orders
        ]);
    }

    public function refund(Request $request)
    {
        $shop = $this->helper->getLocalShop();
        $refund = Refund::find($request->id);
        return view('single-store.orders.view-refund')->with([
            'shop' => $shop,
            'ticket' => $refund,
        ]);
    }
    public function show_notification($id){
        $notification = Notification::find($id);
        if($notification != null){
            $notification->read = 1;
            $notification->save();
            if($notification->type == 'Product'){
                return redirect()->route('store.product.wefulfill.show',$notification->type_id);
            }
            elseif ($notification->type == 'Order'){
                return redirect()->route('store.order.view',$notification->type_id);

            }
            elseif ($notification->type == 'Refund'){
                return redirect()->route('store.refund',$notification->type_id);

            }
            elseif ($notification->type == 'Wish-list'){
                return redirect()->route('store.wishlist.view',$notification->type_id);

            }
            elseif ($notification->type == 'Ticket'){
                return redirect()->route('help-center.store.ticket.view',$notification->type_id);

            }
            elseif ($notification->type == 'Wallet'){
                return redirect()->route('store.user.wallet.show');

            }

        }
    }


}
