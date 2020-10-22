<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;

class CheckShopUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(ShopifyApp::shop() != null){
            dd(321);
            return $next($request);
        }
        else{
            dd('no');
            return redirect()->route('store.index');
        }
    }
}
