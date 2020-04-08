<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
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
}
