<?php

namespace App\Providers;

use App\Notification;
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
            $query = Notification::where('read',0)->newQuery();

            if (Auth::check()) {
                $user = Auth::user();
                if ($user->has_wallet == null) {
                    $balance = 0;
                } else {
                    $balance  = $user->has_wallet->available;
                }
                $query->whereHas('to_users',function ($q) use ($user){
                    $q->where('email',$user->email);
                });
            }
            else {
              $auth_shop =  ShopifyApp::shop();
              if($auth_shop != null){

                  $shop = Shop::find($auth_shop->id);
                  $query->whereHas('to_shops',function ($q) use ($shop){
                      $q->where('shopify_domain',$shop->shopify_domain);
                  });
                  if(count($shop->has_user) > 0){
                      if($shop->has_user[0]->has_wallet != null){
                          $wallet =  $shop->has_user[0]->has_wallet;
                          $balance = $wallet->available;
                          $user = $shop->has_user[0];
                          $query->orwhereHas('to_users',function ($q) use ($user){
                              $q->where('email',$user->email);
                          });

                      }
                      else{
                          $balance = 0;
                      }
                  }
                  else{
                      $balance = 0;
                  }
              }
              else{
                  $balance = 0;
              }
            }
            $notifications = $query->orderBy('created_at','DESC')->paginate(10);
            $notifications_count = $query->orderBy('created_at','DESC')->count();

            $view->with([
                'balance' => $balance,
                'notifications' => $notifications,
                'notifications_count' =>$notifications_count
            ]);

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
