<?php

namespace App\Http\Middleware;

use Closure;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;

class SuperAdminCheck
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
        $shop = ShopifyApp::shop();
        if($shop->shopify_domain == 'fantasy-supplier.myshopify.com'){
            return $next($request);
        }
        else{
            return redirect('login');
        }
    }
}
