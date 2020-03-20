<?php

namespace App\Jobs;

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
        if($user != null){
            if(!in_array($currentShop->id,$user->has_shops->pluck('id')->toArray())){
                $user->has_shops()->attach([$currentShop->id]);
            }
            session(['return_to'=>'/users/home']);
        }
        else{
            if($currentShop->shopify_domain != 'fantasy-supplier.myshopify.com'){
                session(['return_to'=>'/store/dashboard']);
            }
            else{
                session(['return_to' => '/']);
            }
    }
    }
}
