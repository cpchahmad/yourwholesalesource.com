<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;


class AuthShopOrUser
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
        if(Auth::check()){
            dd(2);
            return $next($request);
        }
        else if( ShopifyApp::shop() != null){
            dd(5);

            return $next($request);
        }
        else{
            dd(56);

            return redirect()->route('login');
        }

    }
}
