<?php

namespace App\Http\Middleware;

use Closure;

class CheckWoocommerceShop
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
        dd($request->session()->all());
        if(session()->has('current_shop_domain')){
            return $next($request);
        }

        return redirect()->route('users.dashboard');

    }
}
