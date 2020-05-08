<?php

namespace App\Http\Controllers;

use App\Category;
use App\Customer;
use App\OrderTransaction;
use App\Product;
use App\RetailerImage;
use App\RetailerOrder;
use App\RetailerProduct;
use App\Shop;
use App\User;
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
}
