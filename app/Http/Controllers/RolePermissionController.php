<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

class RolePermissionController extends Controller
{
   public function check_roles(){
       $user = Auth::user();
       if($user->hasRole('non-shopify-users') && $user->hasRole('sales-manager')){
          return redirect()->route('system.selection');
       }
       else{
           if($user->hasRole('non-shopify-users')){
               return redirect()->route('users.dashboard');
           }
           else if($user->hasRole('sales-manager')){
               return redirect()->route('managers.dashboard');
           }
           else{
               return redirect()->route('admin.dashboard');
           }
       }
   }
   public function selection(){
       return view('selection');
   }
}
