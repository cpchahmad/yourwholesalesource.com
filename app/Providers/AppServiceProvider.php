<?php

namespace App\Providers;

use App\Notification;
use App\Refund;
use App\Shop;
use App\Ticket;
use App\TicketStatus;
use App\WalletRequest;
use App\Wishlist;
use App\WishlistStatus;
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
            $notifications = $query->orderBy('created_at','DESC')->paginate(5);
            $notifications_count = $query->orderBy('created_at','DESC')->count();

            $wishlist_request_count = Wishlist::where('status_id', 1)->count();
            $wallet_request_count = WalletRequest::where('status', 0)->count();
            $refund_request_count = Refund::where('status', 'New')->count();
            $tickets_request_count = Ticket::where('status_id', 1)->count();

            $manager_wishlist_request_count = Wishlist::where('manager_id' ,Auth::id())->where('status_id', 1)->count();
            $manager_refund_request_count = Refund::where('manager_id',Auth::id())->where('status', 'New')->count();
            $manager_tickets_request_count = Ticket::where('manager_id',Auth::id())->where('status_id', 1)->count();


            $view->with([
                'balance' => $balance,
                'notifications' => $notifications,
                'notifications_count' =>$notifications_count,
                'wishlist_request_count' => $wishlist_request_count,
                'wallet_request_count' => $wallet_request_count,
                'refund_request_count' => $refund_request_count,
                'tickets_request_count' => $tickets_request_count,
                'manager_wishlist_request_count' => $manager_wishlist_request_count,
                'manager_refund_request_count' => $manager_refund_request_count,
                'manager_tickets_request_count' => $manager_tickets_request_count,
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
