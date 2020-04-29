<?php

namespace App\Providers;

use App\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        view()->composer('*', function ($view)
        {
            if (Auth::check()) {
                $user = Auth::user();
                if ($user->has_wallet == null) {
                    $balance = 0;
                } else {
                    $balance  = $user->has_wallet->available;
                }
            }
            else {
              $auth_shop =  ShopifyApp::shop();
              $shop = Shop::find($auth_shop->id);
              if(count($shop->has_user) > 0){
                  if($shop->has_user[0]->has_wallet != null){
                     $wallet =  $shop->has_user[0]->has_wallet;
                      $balance = $wallet->available;
                  }
                  else{
                      $balance = 0;
                  }
              }
              else{
                  $balance = 0;
              }

            }

            $view->with('balance', $balance );

        });
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
