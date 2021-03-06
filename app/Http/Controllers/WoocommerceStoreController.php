<?php

namespace App\Http\Controllers;

use App\Category;
use App\Country;
use App\Customer;
use App\DropshipRequest;
use App\Mail\NewShopifyUserMail;
use App\Mail\NewUser;
use App\Mail\NewWallet;
use App\Mail\TopShopifyProuctMail;
use App\Notification;
use App\OrderTransaction;
use App\Product;
use App\Refund;
use App\RetailerImage;
use App\RetailerOrder;
use App\RetailerOrderLineItem;
use App\RetailerProduct;
use App\ShippingMark;
use App\ShippingRate;
use App\Shop;
use App\Ticket;
use App\TicketCategory;
use App\User;
use App\Video;
use App\WalletRequest;
use App\WalletSetting;
use App\WareHouse;
use App\Wishlist;
use App\WishlistStatus;
use App\Zone;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;
use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;
use function foo\func;

class WoocommerceStoreController extends Controller
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


    public function index(Request $request)
    {

        $shop = $this->helper->getCurrentWooShop();

        if ($request->has('date-range')) {
            $date_range = explode('-', $request->input('date-range'));
            $start_date = $date_range[0];
            $end_date = $date_range[1];
            $comparing_start_date = Carbon::parse($start_date)->format('Y-m-d');
            $comparing_end_date = Carbon::parse($end_date)->format('Y-m-d');

            $orders = RetailerOrder::whereIN('paid', [0, 1, 2])->where('shop_id', $shop->id)->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->count();
            $unpaid_orders_count = RetailerOrder::where('paid', 0)->where('shop_id', $shop->id)->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->count();
            $unfullfilled_orders_count = RetailerOrder::where('paid', 0)->where('status', 'unfulfilled')->where('shop_id', $shop->id)->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->count();
            $canceled_order_count = RetailerOrder::where('status', 'cancelled')->where('shop_id', $shop->id)->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->count();
            $sales = RetailerOrder::whereIN('paid', [1, 2])->where('shop_id', $shop->id)->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->sum('cost_to_pay');
//            $products = RetailerProduct::where('shop_id', $shop->id)->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->count();
            $profit = RetailerOrder::whereIN('paid', [1])->where('shop_id', $shop->id)->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->sum('cost_to_pay');
            $cost = RetailerOrder::whereIN('paid', [1])->where('shop_id', $shop->id)->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->sum('cost_to_pay');


            $ordersQ = DB::table('retailer_orders')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(cost_to_pay) as total_sum'))
                ->where('shop_id', $shop->id)
                ->whereIn('paid', [1, 2])
                ->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])
                ->groupBy('date')
                ->get();


//            $ordersQP = DB::table('retailer_orders')
//                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(cost_to_pay) as total_sum'))
//                ->where('shop_id', $shop->id)
//                ->whereIn('paid', [1])
//                ->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])
//                ->groupBy('date')
//                ->get();
//
//            $productQ = DB::table('retailer_products')
//                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
//                ->where('shop_id', $shop->id)
//                ->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])
//                ->groupBy('date')
//                ->get();


        } else {

            $orders = RetailerOrder::whereIN('paid', [1, 2])->where('shop_id', $shop->id)->count();
            $unpaid_orders_count = RetailerOrder::where('paid', 0)->where('shop_id', $shop->id)->count();
            $unfullfilled_orders_count = RetailerOrder::where('paid', 0)->where('status', 'unfulfilled')->where('shop_id', $shop->id)->count();
            $canceled_order_count = RetailerOrder::where('status', 'cancelled')->where('shop_id', $shop->id)->count();
            $sales = RetailerOrder::whereIN('paid', [1, 2])->where('shop_id', $shop->id)->sum('cost_to_pay');
//            $products = RetailerProduct::where('shop_id', $shop->id)->count();
            $profit = RetailerOrder::whereIN('paid', [1])->where('shop_id', $shop->id)->sum('cost_to_pay');
            $cost = RetailerOrder::whereIN('paid', [1])->where('shop_id', $shop->id)->sum('cost_to_pay');

            $ordersQ = DB::table('retailer_orders')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(cost_to_pay) as total_sum'))
                ->where('shop_id', $shop->id)
                ->whereIn('paid', [1, 2])
                ->groupBy('date')
                ->get();


//            $ordersQP = DB::table('retailer_orders')
//                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(cost_to_pay) as total_sum'))
//                ->where('shop_id', $shop->id)
//                ->whereIn('paid', [1])
//                ->groupBy('date')
//                ->get();
//
//            $productQ = DB::table('retailer_products')
//                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
//                ->where('shop_id', $shop->id)
//                ->groupBy('date')
//                ->get();

        }

        $unread_rejected_wishlist = Notification::where('sub_type', 'Wishlist Rejected')
            ->whereHas('to_shops',function ($q) use ($shop){
                $q->where('shopify_domain',$shop->shopify_domain);
            })->where('read',0)->count();


        $unread_completed_wishlist = Notification::where('sub_type', 'Wishlist Completed')
            ->whereHas('to_shops',function ($q) use ($shop){
                $q->where('shopify_domain',$shop->shopify_domain);
            })->where('read',0)->count();


        $graph_one_order_dates = $ordersQ->pluck('date')->toArray();
        $graph_one_order_values = $ordersQ->pluck('total')->toArray();
        $graph_two_order_values = $ordersQ->pluck('total_sum')->toArray();

//        $graph_three_order_dates = $ordersQP->pluck('date')->toArray();
//        $graph_three_order_values = $ordersQP->pluck('total_sum')->toArray();
//
//        $graph_four_order_dates = $productQ->pluck('date')->toArray();
//        $graph_four_order_values = $productQ->pluck('total')->toArray();


        $top_products = Product::join('retailer_products', function ($join) use ($shop) {
            $join->on('products.id', '=', 'retailer_products.linked_product_id')
                ->where('retailer_products.shop_id', '=', $shop->id)
                ->join('retailer_order_line_items', function ($j) {
                    $j->on('retailer_order_line_items.shopify_product_id', '=', 'retailer_products.shopify_id')
                        ->join('retailer_orders', function ($o) {
                            $o->on('retailer_order_line_items.retailer_order_id', '=', 'retailer_orders.id')
                                ->whereIn('paid', [1, 2]);
                        });
                });
        })->select('products.*', DB::raw('sum(retailer_order_line_items.quantity) as sold'), DB::raw('sum(retailer_order_line_items.cost) as selling_cost'))
            ->groupBy('products.id')
            ->orderBy('sold', 'DESC')
            ->get()
            ->take(10);


        return view('woocommerce-store.dashboard')->with([
            'shop' => $shop,
            'date_range' => $request->input('date-range'),
            'orders' => $orders,
            'profit' => $profit,
            'sales' => $sales,
            'cost' => $cost,
//            'products' => $products,
            'graph_one_labels' => $graph_one_order_dates,
            'graph_one_values' => $graph_one_order_values,
            'graph_two_values' => $graph_two_order_values,
//            'graph_three_labels' => $graph_three_order_dates,
//            'graph_three_values' => $graph_three_order_values,
//            'graph_four_values' => $graph_four_order_values,
//            'graph_four_labels' => $graph_four_order_dates,
            'top_products' => $top_products,
            'unpaid_orders_count' => $unpaid_orders_count,
            'unfullfilled_orders_count' => $unfullfilled_orders_count,
            'canceled_order_count' => $canceled_order_count,
            'unread_rejected_wishlist' => $unread_rejected_wishlist,
            'unread_completed_wishlist' => $unread_completed_wishlist,
        ]);

    }

    public function wefullfill_products(Request $request)
    {
        $country = $this->ip_info($this->getRealIpAddr(), 'Country');
        $categories = Category::all();
        $productQuery = Product::with('has_images', 'hasVariants','has_platforms','has_categories','has_subcategories')->where('status', 1)->newQuery();

        $productQuery->where('global', 0)->whereHas('has_preferences', function ($q) {
            return $q->where('woocommerce_domain', '=', $this->helper->getCurrentWooShop()->woocommerce_domain);
        });

        $productQuery->orWhere('global', 1)->where('status', 1);

        if ($request->has('category')) {
            $productQuery->whereHas('has_categories', function ($q) use ($request) {
                return $q->where('title', 'LIKE', '%' . $request->input('category') . '%');
            });
        }
        if ($request->has('search')) {
            $productQuery->where('title', 'LIKE', '%' . $request->input('search') . '%')->orWhere('tags', 'LIKE', '%' . $request->input('search') . '%');
        }
        if ($request->has('tag')) {
            if ($request->input('tag') == 'best-seller') {
                $products = $productQuery->where('sortBy', 'Best Seller')->paginate(12);
            } else if ($request->input('tag') == 'winning-products') {
//                $products = $productQuery->where('tags','LIKE','%'.$request->input('tag').'%')->paginate(12);
                $products = $productQuery->where('sortBy', 'Winning Product')->paginate(12);
            } else {
                $products = $productQuery->where('processing_time', '24 Hours')->paginate(12);
            }
        }
        if ($request->has('filter')) {
            if ($request->input('filter') == 'most-order') {

                $productQuery = Product::join('retailer_products', function ($join) {
                    $join->on('retailer_products.linked_product_id', '=', 'products.id')
                        ->join('retailer_order_line_items', function ($join) {
                            $join->on('retailer_order_line_items.shopify_product_id', '=', 'retailer_products.shopify_id')
                                ->join('retailer_orders', function ($o) {
                                    $o->on('retailer_order_line_items.retailer_order_id', '=', 'retailer_orders.id')
                                        ->whereIn('paid', [1, 2]);
                                });
                        });
                })->select('products.*', DB::raw('sum(retailer_order_line_items.quantity) as sold'), DB::raw('sum(retailer_order_line_items.cost) as selling_cost'))
                    ->groupBy('products.id')
                    ->orderBy('sold', 'DESC');

                $products = $productQuery->paginate(12);

            } elseif ($request->input('filter') == 'most-imported') {
                $products = $productQuery->withCount(['has_imported'])->orderBy('has_imported_count', 'DESC')->paginate(12);
            } elseif ($request->input('filter') == 'new-arrival') {
                $products = $productQuery->orderBy('created_at', 'DESC')->paginate(12);
            }
        } else {
            $products = $productQuery->latest()->paginate(12);
        }


        foreach ($products as $product) {
            $total_weight = $product->weight;
            $zoneQuery = Zone::query();
            $zoneQuery->whereHas('has_countries', function ($q) use ($country) {
                $q->where('name', 'LIKE', '%' . $country . '%');
            });
            $zoneQuery = $zoneQuery->pluck('id')->toArray();

            $shipping_rates = ShippingRate::whereIn('zone_id', $zoneQuery)->newQuery();
            $shipping_rates = $shipping_rates->first();
            if ($shipping_rates != null) {
                if ($shipping_rates->shipping_price > 0) {
                    if ($shipping_rates->type == 'flat') {
                        $product->new_shipping_price = '$' . number_format($shipping_rates->shipping_price, 2);
                    } else {
                        if ($shipping_rates->min > 0) {
                            $ratio = $total_weight / $shipping_rates->min;
                            $product->new_shipping_price = '$' . number_format($shipping_rates->shipping_price * $ratio, 2);
                        } else {
                            $product->new_shipping_price = 'Free Shipping';
                        }
                    }
                } else {
                    $product->new_shipping_price = 'Free Shipping';
                }
            } else {
                $product->new_shipping_price = 'Free Shipping';

            }

        }

        $shop = $this->helper->getCurrentWooShop();
        return view('woocommerce-store.products.wefullfill_products')->with([
            'categories' => $categories,
            'products' => $products,
            'shop' => $shop,
            'search' => $request->input('search'),
            'filter' => $request->input('filter')

        ]);
    }

    public function view_fantasy_product($id)
    {
        $product = Product::find($id);
        $shop = $this->helper->getCurrentWooShop();
        return view('woocommerce-store.products.view_product')->with([
            'product' => $product,
            'shop' => $shop
        ]);
    }

    public function view_my_product($id)
    {
        $product = RetailerProduct::find($id);
        $shop = $this->helper->getCurrentWooShop();
        return view('woocommerce-store.products.view_product')->with([
            'product' => $product,
            'shop' => $shop
        ]);
    }

    public function setting()
    {
        $shop = $this->helper->getCurrentWooShop();
        $user = $shop->has_owner->first();

        if (count($shop->has_owner) > 0) {
            $associated_user = $shop->has_owner[0];
        } else {
            $associated_user = null;
        }
        return view('woocommerce-store.index')->with([
            'shop' => $shop,
            'user' => $user,
            'associated_user' => $associated_user,
            'countries' => Country::all()
        ]);
    }

    public function save_personal_info(Request $request)
    {
        $user = User::find($request->input('user_id'));
        if ($user != null) {
            $user->name = $request->input('name');
            $user->save();
            if ($request->hasFile('profile')) {
                $file = $request->file('profile');
                $name = Str::slug($file->getClientOriginalName());
                $profile = date("mmYhisa_") . $name;
                $file->move(public_path() . '/managers-profiles/', $profile);
                $user->profile = $profile;
                $user->save();
            }
            return redirect()->back()->with('success', 'Personal Information Updated Successfully!');
        } else {
            return redirect()->back()->with('error', 'User Not Found!');
        }
    }

    public function save_address(Request $request)
    {
        $user = User::find($request->input('user_id'));
        if ($user != null) {
            $user->address = $request->input('address');
            $user->address2 = $request->input('address2');
            $user->city = $request->input('city');
            $user->state = $request->input('state');
            $user->zip = $request->input('zip');
            $user->country = $request->input('country');
            $user->save();
            return redirect()->back()->with('success', 'Address Updated Successfully!');

        } else {
            return redirect()->back()->with('error', 'Manager Not Found!');
        }
    }


    public function authenticate(Request $request)
    {
        if (Auth::validate($request->except('_token'))) {
            $authenticate = true;
        } else {
            $authenticate = false;
        }
        return response()->json([
            'authenticate' => $authenticate
        ]);
    }

    public function associate(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();
        $shop = Shop::where('shopify_domain', $request->input('store'))->first();
        if ($user != null && $shop != null) {
            if (!in_array($shop->id, $user->has_shops->pluck('id')->toArray())) {
                $user->has_shops()->attach([$shop->id]);

                // Sending Welcome Email
                try{
                    Mail::to($user->email)->send(new NewShopifyUserMail($user));
                }
                catch (\Exception $e){
                }

                // Sending Top Product Recommendation Email
                try{
                    Mail::to($user->email)->send(new TopShopifyProuctMail($user));
                }
                catch (\Exception $e){
                }

                // Sync To SendGrid WefullFill Members Contact List
                $contacts = [];
                array_push($contacts, [
                    'email' => $user->email,
                    'first_name' => $user->name,
                ]);
                $contacts_payload = [
                    'list_ids' => ["33d743f3-a906-4512-83cd-001f7ba5ab33"],
                    'contacts' => $contacts
                ];

                $payload = json_encode($contacts_payload);

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/contacts",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "PUT",
                    CURLOPT_POSTFIELDS => $payload,
                    CURLOPT_HTTPHEADER => array(
                        "authorization: Bearer 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",
                        "content-type: application/json"
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);



                return response()->json([
                    'status' => 'assigned'
                ]);
            } else {
                return response()->json([
                    'status' => 'already-assigned'
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }

    public function de_associate(Request $request)
    {
        $shop = Shop::find($request->id);
        $user = Auth::user();
        $user->has_shops()->detach([$shop->id]);
        return redirect()->back()->with('success', 'Store Removed Successfully!');
    }


    public function customers(Request $request)
    {
        $customersQ = Customer::where('woocommerce_shop_id', $this->helper->getCurrentWooShop()->id)->newQuery();
        $customers = $customersQ->paginate(30);
        return view('woocommerce-store.customers.index')->with([
            'shop' => $this->helper->getCurrentWooShop(),
            'customers' => $customers,
        ]);
    }

    public function customer_view($id)
    {
        $customer = Customer::find($id);
        return view('woocommerce-store.customers.view')->with([
            'shop' => $this->helper->getCurrentWooShop(),
            'customer' => $customer,
        ]);
    }

    public function getCustomers()
    {
        $woocommerce = $this->helper->getWooShop();
        $shop = $this->helper->getCurrentWooShop();

        $page = 1;
        $customers = [];
        $all_customers = [];
        do{
            try {
                $customers = $woocommerce->get('customers',['per_page' => 100, 'page' => $page]);
            }catch(HttpClientException $e){

            }
            $all_customers = array_merge($all_customers,$customers);
            $page++;
        } while (count($customers) > 0);


        foreach ($customers as $index => $customer) {
            if (Customer::where('customer_woocommerce_id', $customer->id)->where('woocommerce_shop_id', $shop->id)->exists()) {
                $new_customer = Customer::where('customer_woocommerce_id', $customer->id)->where('woocommerce_shop_id', $shop->id)->first();
            } else {
                $new_customer = new Customer();
            }
            $new_customer->customer_woocommerce_id = $customer->id;
            $new_customer->first_name = $customer->first_name;
            $new_customer->last_name = $customer->last_name;
            $new_customer->phone = isset($customer->billing->phone) ? $customer->billing->phone : null;
            $new_customer->email = $customer->email;
            //$new_customer->total_spent = $customer->total_spent;
            $new_customer->woocommerce_shop_id = $shop->id;
            $local_shop = $shop;
            if (count($local_shop->has_owner) > 0) {
                $new_customer->user_id = $local_shop->has_owner[0]->id;
            }
            $new_customer->save();
        }

        return redirect()->back()->with('success', 'Customers Synced Successfully!');
    }

    public function payment_history(Request $request)
    {
        $shop = $this->helper->getLocalShop();
        $payments = OrderTransaction::where('shop_id', $shop->id)->newQuery();
        return view('single-store.orders.payment_history')->with([
            'payments' => $payments->orderBy('created_at')->paginate(20),
        ]);
    }

    public function tracking_info(Request $request)
    {
        $shop = $this->helper->getLocalShop();
        $orders = RetailerOrder::where('shop_id', $shop->id)->newQuery();
        if ($request->has('search')) {
            $orders->where('name', 'LIKE', '%' . $request->input('search') . '%');
        }
        return view('single-store.orders.tracking_info')->with([
            'orders' => $orders->orderBy('created_at')->paginate(20),
            'search' => $request->input('search')

        ]);
    }

    public function helpcenter(Request $request)
    {
        $shop = $this->helper->getCurrentWooShop();
        $user = $shop->has_owner()->first();
        $tickets = Ticket::where('user_id', $user->id)->where('source', 'woocommerce-store')->newQuery();
        $orders = $shop->has_woocommerce_orders()->get();
        $tickets = $tickets->paginate(30);

        return view('woocommerce-store.help-center.index')->with([
            'shop' => $shop,
            'tickets' => $tickets,
            'orders' => $orders,
            'categories' => TicketCategory::all(),
        ]);
    }

    public function wishlist(Request $request)
    {
        $shop = $this->helper->getLocalShop();
        $user = $shop->has_user()->first();
        $wishlist = Wishlist::where('user_id', $user->id)->newQuery();

        if($request->read == 1) {
            $notifications = Notification::where('sub_type', 'Wishlist Rejected')
                ->whereHas('to_shops',function ($q) use ($shop){
                    $q->where('shopify_domain',$shop->shopify_domain);
                })->where('read',0)->get();

            foreach($notifications as $notification) {
                $notification->read = 1;
                $notification->save();
            }
        }

        if($request->read == 2) {
            $notifications = Notification::where('sub_type', 'Wishlist Completed')
                ->whereHas('to_shops',function ($q) use ($shop){
                    $q->where('shopify_domain',$shop->shopify_domain);
                })->where('read',0)->get();

            foreach($notifications as $notification) {
                $notification->read = 1;
                $notification->save();
            }
        }

        if($request->has('search')){
            $wishlist->where('product_name','LIKE','%'.$request->input('search').'%')
                ->orwhere('description','LIKE','%'.$request->input('search').'%');
        }

        if($request->has('status')){
            if($request->input('status') != null){
                $wishlist->where('status_id','=',$request->input('status'));
            }
        }

        if($request->has('imported')) {
            $wishlist->where('imported_to_store',0);
        }

        $wishlist = $wishlist->where('user_id', $user->id)->orderBy('created_at','DESC')
            ->paginate(30);

        return view('single-store.wishlist.index')->with([
            'shop' => $shop,
            'wishlist' => $wishlist,
            'statuses' => WishlistStatus::all(),
            'selected_status' =>$request->input('status'),
            'countries' => Country::all(),
        ]);
    }

    public function view_wishlist(Request $request)
    {
        $shop = $this->helper->getLocalShop();
        $wishlist = Wishlist::find($request->id);
        return view('single-store.wishlist.view')->with([
            'shop' => $shop,
            'wishlist' => $wishlist,
        ]);
    }

    public function view_ticket(Request $request)
    {
        $shop = $this->helper->getCurrentWooShop();
        $ticket = Ticket::find($request->id);
        return view('woocommerce-store.help-center.view')->with([
            'shop' => $shop,
            'ticket' => $ticket,
        ]);
    }

    public function calculate_shipping_old(Request $request)
    {

        if ($request->has('country')) {
            $country = $request->input('country');
        } else {
            $country = "United States";
        }

        $product = Product::find($request->input('product'));
        if ($product != null) {
            $total_weight = $product->weight;
        } else {
            $total_weight = 0;
        }

        $zoneQuery = Zone::query();
        $zoneQuery->whereHas('has_countries', function ($q) use ($country) {
            $q->where('name', 'LIKE', '%' . $country . '%');
        });
        $zoneQuery = $zoneQuery->pluck('id')->toArray();

        $shipping_rates = ShippingRate::whereIn('zone_id', $zoneQuery)->newQuery();

        $shipping_rates = $shipping_rates->get();

        foreach ($shipping_rates as $shipping_rate) {
            if ($shipping_rate->min > 0) {
                if ($shipping_rate->type == 'flat') {

                } else {
                    $ratio = $total_weight / $shipping_rate->min;
                    $shipping_rate->shipping_price = $shipping_rate->shipping_price * $ratio;
                }

            } else {
                $ratio = 0;
                $shipping_rate->shipping_price = $shipping_rate->shipping_price * $ratio;
            }

        }

        $html = view('inc.calculate_shipping')->with([
            'countries' => Country::all(),
            'selected' => $country,
            'rates' => $shipping_rates,
            'product' => $request->input('product'),
            'retailer_product_id' => $request->input('retailer_product'),
        ])->render();

        return response()->json([
            'html' => $html
        ]);


    }

    public function calculate_shipping(Request $request)
    {

        if ($request->has('country')) {
            $country = $request->input('country');
        }
        else {
            $country = 'United States';
        }

        if($request->has('warehouse_id')) {
            $warehouse_id = $request->input('warehouse_id');
        }


        $product = Product::find($request->input('product'));

        if ($product != null) {
            $total_weight = $product->weight;
        } else {
            $total_weight = 0;
        }


        $zoneQuery = Zone::where('warehouse_id', $warehouse_id)->newQuery();

        $zoneQuery->whereHas('has_countries',function ($q) use ($country){
            $q->where('name','LIKE','%'.$country.'%');
        });
        $zoneQuery = $zoneQuery->pluck('id')->toArray();


        $shipping_rates = ShippingRate::whereIn('zone_id', $zoneQuery)->newQuery();

        $shipping_rates = $shipping_rates->get();

        foreach ($shipping_rates as $shipping_rate) {
            if ($shipping_rate->min > 0) {
                if ($shipping_rate->type == 'flat') {
                    $shipping_rate->shipping_price = $shipping_rate->shipping_price;
                } else {
                    $ratio = $total_weight / $shipping_rate->min;
                    $shipping_rate->shipping_price = $shipping_rate->shipping_price * $ratio;
                }

            } else {
                $ratio = 0;
                $shipping_rate->shipping_price = $shipping_rate->shipping_price * $ratio;
            }

        }

        $html = view('inc.calculate_shipping')->with([
            'countries' => Country::all(),
            'selected' => $country,
            'rates' => $shipping_rates,
            'product' => $request->input('product'),
            'retailer_product_id' => $request->input('retailer_product'),
        ])->render();

        return response()->json([
            'html' => $html
        ]);


    }

    public function calculate_warehouse_shipping_old(Request $request)
    {

        $order = RetailerOrder::find($request->input('order'));
        $shipping_address = json_decode($order->shipping_address);
        $country = $shipping_address->country;
        $warehouse = WareHouse::find($request->input('id'));


        if($warehouse->zones) {
            $countries = $warehouse->zones->map(function($zone) {
                return $zone->has_countries->pluck('name');
            });
            $countries = $countries->collapse()->toArray();
        }
        else {
            return view('inc.warehouse')->with([
                'shipping' => 'This product is not shipped to this country',
                'order' => $order,
                'total' => $order->subtotal_price,
                'status' => 'failure'
            ])->render();
        }


        if(!in_array($country, $countries))
            return view('inc.warehouse')->with([
                'shipping' => 'This product is not shipped to this country',
                'order' => $order,
                'total' => $order->subtotal_price,
                'status' => 'failure'
            ])->render();

        $total_shipping = 0;

        $selected_line_item = RetailerOrderLineItem::find($request->input('line_item'));
        $selected_line_item->selected_warehouse = $request->input('id');
        $selected_line_item->save();


        foreach ($order->line_items as $index => $v){
            $weight = $v->linked_product->linked_product->weight *  $v->quantity;
            if($v->linked_product != null){
                if($v->linked_product->linked_product != null && $v->linked_product->linked_product->id != $request->input('product')) {
                    $zoneQuery = Zone::where('warehouse_id', 3)->newQuery();
                    $zoneQuery->whereHas('has_countries',function ($q) use ($country){
                        $q->where('name','LIKE','%'.$country.'%');
                    });
                    $zoneQuery = $zoneQuery->pluck('id')->toArray();

                    $shipping_rates = ShippingRate::whereIn('zone_id',$zoneQuery)->newQuery();
                    $shipping_rates =  $shipping_rates->first();
                    if($shipping_rates != null){

                        if($shipping_rates->type == 'flat'){
                            $total_shipping += $shipping_rates->shipping_price;
                        }
                        else{
                            if($shipping_rates->min > 0){
                                $ratio = $weight/$shipping_rates->min;
                                $total_shipping +=  $shipping_rates->shipping_price*$ratio;
                            }
                            else{
                                $total_shipping += 0;
                            }
                        }

                    }
                    else{
                        $total_shipping += 0;
                    }
                }
                elseif($v->linked_product->linked_product != null && $v->linked_product->linked_product->id == $request->input('product')) {
                    $zoneQuery = Zone::where('warehouse_id', $warehouse->id)->newQuery();
                    $zoneQuery->whereHas('has_countries',function ($q) use ($country){
                        $q->where('name','LIKE','%'.$country.'%');
                    });
                    $zoneQuery = $zoneQuery->pluck('id')->toArray();
                    $shipping_rate = ShippingRate::whereIn('zone_id',$zoneQuery)->newQuery();
                    $shipping_rate =  $shipping_rate->first();

//                    $zoneQuery = $warehouse->zones->pluck('id')->toArray();
//                    $shipping_rate = ShippingRate::whereIn('zone_id', $zoneQuery)->first();

                    if ($shipping_rate->min > 0) {
                        if ($shipping_rate->type == 'flat') {

                        } else {
                            $ratio = $weight / $shipping_rate->min;
                            $total_shipping += $shipping_rate->shipping_price * $ratio;
                        }

                    } else {
                        $ratio = 0;
                        $total_shipping += $shipping_rate->shipping_price * $ratio;
                    }

                }
            }
        }

        return view('inc.warehouse')->with([
            'shipping' => number_format($total_shipping, 2) . ' USD',
            'order' => $order,
            'total' => number_format($order->subtotal_price + $total_shipping, 2),
            'status' => 'success'
        ])->render();

    }

    public function calculate_warehouse_shipping(Request $request)
    {

        $order = RetailerOrder::find($request->input('order'));
        $shipping_address = json_decode($order->shipping_address);
        $country = $shipping_address->country;
        $warehouse = WareHouse::find($request->input('id'));

        if($order->custom == 1)
            $view = 'inc.non-shopify-warehouse';
        else
            $view = 'inc.warehouse';


        if($warehouse->zones) {
            $countries = $warehouse->zones->map(function($zone) {
                return $zone->has_countries->pluck('name');
            });
            $countries = $countries->collapse()->toArray();
        }
        else {

            return view($view)->with([
                'shipping' => 'This product is not shipped to this country',
                'order' => $order,
                'total' => $order->subtotal_price,
                'status' => 'failure'
            ])->render();
        }


        if(!in_array($country, $countries))
            return view($view)->with([
                'shipping' => 'This product is not shipped to this country',
                'order' => $order,
                'total' => $order->subtotal_price,
                'status' => 'failure'
            ])->render();

        $total_shipping = 0;

        $selected_line_item = RetailerOrderLineItem::find($request->input('line_item'));
        $selected_line_item->selected_warehouse = $request->input('id');
        $selected_line_item->save();


        foreach ($order->line_items as $index => $v){
            if($v->linked_product)
                $weight = $v->linked_product->linked_product->weight *  $v->quantity;
            else
                $weight = $v->linked_woocommerce_product->weight *  $v->quantity;


            $zoneQuery = Zone::where('warehouse_id', $v->selected_warehouse)->newQuery();
            $zoneQuery->whereHas('has_countries',function ($q) use ($country){
                $q->where('name','LIKE','%'.$country.'%');
            });
            $zoneQuery = $zoneQuery->pluck('id')->toArray();
            $shipping_rate = ShippingRate::whereIn('zone_id',$zoneQuery)->newQuery();
            $shipping_rate =  $shipping_rate->first();


            if($shipping_rate)
            {
                if ($shipping_rate->min > 0) {
                    if ($shipping_rate->type == 'flat') {
                        $total_shipping += $shipping_rate->shipping_price;
                    } else {
                        $ratio = $weight / $shipping_rate->min;
                        $total_shipping += $shipping_rate->shipping_price * $ratio;
                    }
                }
                else {
                    $ratio = 0;
                    $total_shipping += $shipping_rate->shipping_price * $ratio;
                }
            }

        }

        $shipping=number_format($total_shipping, 2) . ' USD';
        $total=number_format($order->subtotal_price + $total_shipping, 2);
        $status='success';

        return response()->view($view, compact('shipping','order','total','status'))
            ->withHeaders([
                'Content-Type'=>'text/html; charset=UTF-8'
            ]);

    }

    public function set_line_item_warehouse(Request $request) {
        $line_item = RetailerOrderLineItem::find($request->input('line_item'));
        $warehouse_id = $request->input('id');

        $line_item->selected_warehouse = $warehouse_id;
        $line_item->save();

        return response()->json([
            'status' => 'ok'
        ]);
    }

    public function refunds(Request $request)
    {
        $shop = $this->helper->getCurrentWooShop();
        $refunds = Refund::where('woocommerce_shop_id', $shop->id)->newQuery();
        if ($request->has('search')) {
            $refunds->where('order_name', 'LIKE', '%' . $request->input('search') . '%');
        }
        $refunds->whereHas('has_order', function () {

        });
        $orders = RetailerOrder::where('woocommerce_shop_id', $shop->id)->where('paid', 1)->get();
        return view('woocommerce-store.orders.refunds')->with([
            'refunds' => $refunds->orderBy('created_at')->paginate(20),
            'search' => $request->input('search'),
            'shop' => $shop,
            'orders' => $orders
        ]);
    }

    public function refund(Request $request)
    {
        $shop = $this->helper->getLocalShop();
        $refund = Refund::find($request->id);
        if ($refund->has_order != null) {
            return view('single-store.orders.view-refund')->with([
                'shop' => $shop,
                'ticket' => $refund,
            ]);
        } else {
            return redirect()->route('store.refunds')->with('No Refund Found!');

        }
    }

    public function show_notification($id)
    {
        $notification = Notification::find($id);
        if ($notification != null) {
            $notification->read = 1;
            $notification->save();
            if ($notification->type == 'Product') {
                return redirect()->route('store.product.wefulfill.show', $notification->type_id);
            } elseif ($notification->type == 'Order') {
                return redirect()->route('store.order.view', $notification->type_id);

            } elseif ($notification->type == 'Refund') {
                return redirect()->route('store.refund', $notification->type_id);

            } elseif ($notification->type == 'Wish-list') {
                return redirect()->route('store.wishlist.view', $notification->type_id);

            } elseif ($notification->type == 'Ticket') {
                return redirect()->route('help-center.store.ticket.view', $notification->type_id);

            } elseif ($notification->type == 'Wallet') {
                return redirect()->route('store.user.wallet.show');

            }

        }
    }

    public function notifications()
    {
        $query = Notification::query();
        $shop = $this->helper->getCurrentWooShop();

        if ($shop != null) {

            $query->whereHas('to_woocommerce_shops', function ($q) use ($shop) {
                $q->where('woocommerce_shop_id', $shop->id);
            });

            if (count($shop->has_owner) > 0) {
                $user = $shop->has_owner[0];
                $query->orwhereHas('to_users', function ($q) use ($user) {
                    $q->where('email', $user->email);
                });

            }

        }
        $notifications = $query->orderBy('created_at', 'DESC')->paginate(30);
        return view('woocommerce-store.notifications.index')->with([
            'shop' => $shop,
            'notifications' => $notifications
        ]);

    }

    function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE)
    {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        $purpose = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
        $support = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city" => @$ipdat->geoplugin_city,
                            "state" => @$ipdat->geoplugin_regionName,
                            "country" => @$ipdat->geoplugin_countryName,
                            "country_code" => @$ipdat->geoplugin_countryCode,
                            "continent" => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;
    }

    function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            // Check IP from internet.
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Check IP is passed from proxy.
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            // Get IP address from remote address.
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

//    public function reports(Request $request) {
//
//        $shop = $this->helper->getLocalShop();
//        $shop_user = Shop::find($shop->id);
//        $shop_user = $shop_user->has_user()->first();
//        $shop_user = $shop_user->name;
//
//        if ($request->has('date-range')) {
//            $date_range = explode('-', $request->input('date-range'));
//            $start_date = $date_range[0];
//            $end_date = $date_range[1];
//            $comparing_start_date = Carbon::parse($start_date)->format('Y-m-d');
//            $comparing_end_date = Carbon::parse($end_date)->format('Y-m-d');
//
//            $orders = RetailerOrder::whereIN('paid', [1, 2])->where('shop_id', $shop->id)->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->count();
//            $sales = RetailerOrder::whereIN('paid', [1, 2])->where('shop_id', $shop->id)->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->sum('cost_to_pay');
//            $products = RetailerProduct::where('shop_id', $shop->id)->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->count();
//            $profit = RetailerOrder::whereIN('paid', [1])->where('shop_id', $shop->id)->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->sum('cost_to_pay');
//            $cost = RetailerOrder::whereIN('paid', [1])->where('shop_id', $shop->id)->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->sum('cost_to_pay');
//
//
//            $ordersQ = DB::table('retailer_orders')
//                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(cost_to_pay) as total_sum'))
//                ->where('shop_id', $shop->id)
//                ->whereIn('paid', [1, 2])
//                ->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])
//                ->groupBy('date')
//                ->get();
//
//
//            $ordersQP = DB::table('retailer_orders')
//                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(cost_to_pay) as total_sum'))
//                ->where('shop_id', $shop->id)
//                ->whereIn('paid', [1])
//                ->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])
//                ->groupBy('date')
//                ->get();
//
//            $productQ = DB::table('retailer_products')
//                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
//                ->where('shop_id', $shop->id)
//                ->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])
//                ->groupBy('date')
//                ->get();
//
//
//        } else {
//
//            $orders = RetailerOrder::whereIN('paid', [1, 2])->where('shop_id', $shop->id)->count();
//            $sales = RetailerOrder::whereIN('paid', [1, 2])->where('shop_id', $shop->id)->sum('cost_to_pay');
//            $products = RetailerProduct::where('shop_id', $shop->id)->count();
//            $profit = RetailerOrder::whereIN('paid', [1])->where('shop_id', $shop->id)->sum('cost_to_pay');
//            $cost = RetailerOrder::whereIN('paid', [1])->where('shop_id', $shop->id)->sum('cost_to_pay');
//
//            $ordersQ = DB::table('retailer_orders')
//                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(cost_to_pay) as total_sum'))
//                ->where('shop_id', $shop->id)
//                ->whereIn('paid', [1, 2])
//                ->groupBy('date')
//                ->get();
//
//
//            $ordersQP = DB::table('retailer_orders')
//                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(cost_to_pay) as total_sum'))
//                ->where('shop_id', $shop->id)
//                ->whereIn('paid', [1])
//                ->groupBy('date')
//                ->get();
//
//            $productQ = DB::table('retailer_products')
//                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
//                ->where('shop_id', $shop->id)
//                ->groupBy('date')
//                ->get();
//
//        }
//
//
//        $graph_one_order_dates = $ordersQ->pluck('date')->toArray();
//        $graph_one_order_values = $ordersQ->pluck('total')->toArray();
//        $graph_two_order_values = $ordersQ->pluck('total_sum')->toArray();
//
//        $graph_three_order_dates = $ordersQP->pluck('date')->toArray();
//        $graph_three_order_values = $ordersQP->pluck('total_sum')->toArray();
//
//        $graph_four_order_dates = $productQ->pluck('date')->toArray();
//        $graph_four_order_values = $productQ->pluck('total')->toArray();
//
//
//        $top_products = Product::join('retailer_products', function ($join) use ($shop) {
//            $join->on('products.id', '=', 'retailer_products.linked_product_id')
//                ->where('retailer_products.shop_id', '=', $shop->id)
//                ->join('retailer_order_line_items', function ($j) {
//                    $j->on('retailer_order_line_items.shopify_product_id', '=', 'retailer_products.shopify_id')
//                        ->join('retailer_orders', function ($o) {
//                            $o->on('retailer_order_line_items.retailer_order_id', '=', 'retailer_orders.id')
//                                ->whereIn('paid', [1, 2]);
//                        });
//                });
//        })->select('products.*', DB::raw('sum(retailer_order_line_items.quantity) as sold'), DB::raw('sum(retailer_order_line_items.cost) as selling_cost'))
//            ->groupBy('products.id')
//            ->orderBy('sold', 'DESC')
//            ->get()
//            ->take(10);
//
//        $range = $request->input('date-range') ? $request->input('date-range') : 'beginning till now';
//
//        return view('single-store.reports')->with([
//            'date_range' => $range,
//            'orders' => $orders,
//            'profit' => $profit,
//            'sales' => $sales,
//            'cost' => $cost,
//            'products' => $products,
//            'graph_one_labels' => $graph_one_order_dates,
//            'graph_one_values' => $graph_one_order_values,
//            'graph_two_values' => $graph_two_order_values,
//            'graph_three_labels' => $graph_three_order_dates,
//            'graph_three_values' => $graph_three_order_values,
//            'graph_four_values' => $graph_four_order_values,
//            'graph_four_labels' => $graph_four_order_dates,
//            'top_products' => $top_products,
//            'shop' => $shop_user,
//        ]);
//
//    }

    public function showVideosSection() {
        $videos = Video::get()->groupBy(function($data){
            return $data->category;
        });

        return view('woocommerce-store.videos.index')->with([
            'videos' => $videos,
            'shop' => $this->helper->getCurrentWooShop()
        ]);

    }

    public function saveWalletSettings(Request $request, $id) {
        WalletSetting::updateOrCreate(
            [ 'user_id' => $id ],
            [ 'enable' => $request->status ]
        );
    }

    public function showInvoice() {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->has_wallet == null) {
                $wallet = $this->wallet_create(Auth::id());
                try{
                    Mail::to($user->email)->send(new NewWallet($user));

                }catch (\Exception $e){

                }

            } else {
                $wallet = $user->has_wallet;
            }

            if($shop = $this->helper->getCurrentWooShop())
                return view('woocommerce-store.invoices.index')->with([
                    'shop' => $shop,
                    'user' => $user,
                    'wallet' => $wallet
                ]);
            else
                return view('non_shopify_users.invoices.index')->with([
                    'user' => $user,
                    'wallet' => $wallet
                ]);
        }

        return redirect()->back();

    }

    public function downloadInvoicePDF($id) {

        $wallet_request = WalletRequest::find($id);
        $user = User::find($wallet_request->user_id);
        $manager= $user->has_manager;

        return view('single-store.invoices.show')->with([
            'wallet' => $wallet_request,
            'user' => $user,
            'manager' => $manager
        ]);
    }

    public function dropship_requests(Request $request) {

        $shop = $this->helper->getLocalShop();

        $requests = DropshipRequest::where('shop_id', $shop->id)->newQuery();


        if($request->has('status')){
            if($request->input('status') != null){
                $requests->where('status_id','=',$request->input('status'));
            }
        }

        if($request->has('imported')) {
            $requests->where('imported_to_store',0);
        }

        $requests = $requests->orderBy('created_at', 'DESC')->paginate(30);

        return view('single-store.dropship-request.index')->with([
            'shop' => $shop,
            'requests' => $requests,
            'countries' => Country::all(),
        ]);
    }


    public function view_dropship_request(Request $request) {
        $shop = $this->helper->getLocalShop();

        $item = DropshipRequest::with('shipping_marks')->find($request->id);
        return view('single-store.dropship-request.view')->with([
            'shop' => $shop,
            'item' => $item
        ]);
    }


    public function view_shipping_mark($id, $mark_id) {

        return view('single-store.dropship-request.view-shipping-mark')->with([
            'drop_request' => DropshipRequest::find($id),
            'mark' => ShippingMark::find($mark_id)
        ]);
    }

    public function create_shipping_mark($id) {
        $drop_request = DropshipRequest::find($id);

        return view('single-store.dropship-request.create-shipping-mark')->with([
            'drop_request' => $drop_request
        ]);
    }


    public function woocommerce_store_connect() {
        return view('non_shopify_users.woocommerce_store_connect');
    }

    public function authenticate_woocommerce(Request $request)
    {
        $this->validate($request, [
            'woocommerce_domain' => 'required|unique:shops',
            'consumer_key' => 'required',
            'consumer_secret' => 'required'
        ]);

        $woocommerce = new Client($request->woocommerce_domain, $request->consumer_key, $request->consumer_secret, ['wp_api' => true, 'version' => 'wc/v3',]);

        try {
            $products = $woocommerce->get('products');
        }
        catch(HttpClientException $e) {
            $error_msg = $e->getMessage(); // Error message.
            $e->getRequest(); // Last request data.
            $e->getResponse(); // Last response data
            return redirect()->back()->with('error', 'Your Credentials are incorrect. Please Try again!, Error:'.$error_msg);
        }


        $woo_shop = new Shop();
        $woo_shop->woocommerce_domain = $request->woocommerce_domain;
        $woo_shop->consumer_key = $request->consumer_key;
        $woo_shop->consumer_secret = $request->consumer_secret;
        $woo_shop->save();


        Auth::user()->has_woocommerce_shops()->attach([$woo_shop->id]);


        return redirect()->back()->with('success', 'Store Connected Successfully!');
    }

    public function woocommerce_stores() {
        $shops = auth()->user()->has_woocommerce_shops;
        return view('non_shopify_users.woocommerce_stores')->with([
            'shops' => $shops
        ]);
    }

    public function switch_to_store(Request $request) {
        Session::put('woocommerce_domain', $request->input('woocommerce_domain'));

        return redirect(route('woocommerce.store.dashboard'));
    }

    public function getWooShop() {
        $shop = Auth::user()->has_woocommerce_shops[0];

        $woocommerce = new Client($shop->shop_url, $shop->consumer_key, $shop->consumer_secret, ['wp_api' => true, 'version' => 'wc/v3',]);

        return $woocommerce;
    }




}
