<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolePermissionController extends Controller
{
   public function check_roles(){
       $user = Auth::user();
       dump('87');
//       if($user->email == 'admin@wefullfill.com'){
//           return redirect('/shop/install?shop=fantasy-supplier.myshopify.com');
//       }

       if($user->email == 'moe@webinopoly.com') {
           dd('43567y');
           return redirect('/');
       }
       if ($user->email == 'super_admin@wefullfill.com'){
           return redirect('/shop/install?shop=wefullfill.myshopify.com');
       }
       else{
           dump('6');
           if($user->hasRole('non-shopify-users') && $user->hasRole('sales-manager')){
               return redirect()->route('system.selection');
           }
           else{
               dump('12');
               if($user->hasRole('non-shopify-users')){
                   dd('45');
                   return redirect()->route('users.dashboard',['ftl' => '1']);
               }
               else if($user->hasRole('sales-manager')){
                   return redirect()->route('managers.dashboard');
               }
               else{
                   dd('99999');
                   return redirect()->route('users.dashboard',['ftl' => '1']);
               }
           }
       }

   }
   public function selection(){
       return view('selection');
   }

   public function store_connect(Request $request){
       return view('non_shopify_users.store_connect');
   }
}
