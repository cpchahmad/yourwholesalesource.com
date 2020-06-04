<?php

namespace App\Http\Controllers;

use App\Category;
use App\Country;
use App\Customer;
use App\OrderTransaction;
use App\Product;
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
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function index(){
        return view('single-store.dashboard');
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
        if($request->has('category')){
            $productQuery->whereHas('has_categories',function($q) use ($request){
                return $q->where('title','LIKE','%'.$request->input('category').'%');
            });
        }
        if($request->has('search')){
            $productQuery->where('title','LIKE','%'.$request->input('search').'%')->orWhere('tags','LIKE','%'.$request->input('search').'%');
        }
        $products = $productQuery->paginate(12);

        foreach ($products as $product){
            $total_weight = $product->weight;
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
                $product->new_shipping_price = '$'.number_format($shipping_rates->shipping_price,2);
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
            'search' =>$request->input('search')
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
        $shop = $this->helper->getLocalShop();
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
        $shipping_rates->whereRaw('min <='.$total_weight);
        $shipping_rates->whereRaw('max >='.$total_weight);

        $shipping_rates =  $shipping_rates->get();

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


}
