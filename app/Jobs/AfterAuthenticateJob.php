<?php

namespace App\Jobs;

use App\ErrorLog;
use App\Http\Controllers\InventoryController;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;

class AfterAuthenticateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle()
    {
        $currentShop = ShopifyApp::shop();
        $user = Auth::user();


        if(!in_array($currentShop->shopify_domain,['wefullfill.myshopify.com'])){
            $new = new InventoryController();
            $new->create_service();
        }

        if($user != null && !in_array($user->email,['super_admin@wefullfill.com']) && !in_array($currentShop->shopify_domain,['wefullfill.myshopify.com'])){
            if(!in_array($currentShop->id,$user->has_shops->pluck('id')->toArray())){
                $user->has_shops()->attach([$currentShop->id]);

                $new = new ErrorLog();
                $new->message = "New store installed";
                $new->save;

                $currentShop->api()->rest('POST', '/admin/webhooks.json', [
                    'webhook' => [
                        'topic' => 'orders/create',
                        'address' => 'https://app.yourwholesalesource.com/webhook/orders-create',
                        "format"=> "json"
                    ]
                ]);


                $currentShop->api()->rest('POST', '/admin/webhooks.json', [
                    'webhook' => [
                        'topic' => 'customers/create',
                        'address' => 'https://app.yourwholesalesource.com/webhook/customers-create',
                        "format"=> "json"
                    ]
                ]);

                $currentShop->api()->rest('POST', '/admin/webhooks.json', [
                    'webhook' => [
                        "topic" => "products/delete",
                        "address" => "https://app.yourwholesalesource.com/webhook/products-delete",
                        "format"=> "json"
                    ]
                ]);

                $currentShop->api()->rest('POST', '/admin/webhooks.json', [
                    'webhook' => [
                        'topic' => 'orders/cancelled',
                        'address' => 'https://app.yourwholesalesource.com/webhook/orders-cancelled',
                        "format"=> "json"
                    ]
                ]);

            }
            session(['return_to'=>'/store/dashboard?ftl=1']);
        }
        else{
            if(!in_array($currentShop->shopify_domain,['wefullfill.myshopify.com'])){
                session(['return_to'=>'/store/dashboard?ftl=1']);
            }
            else{
                session(['return_to' => '/']);
            }
    }
    }
}
