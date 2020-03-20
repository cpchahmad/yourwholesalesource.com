<?php

namespace App\Http\Controllers;

use App\Shop;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;

class SingleStoreController extends Controller
{
    public function index(){
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
}
