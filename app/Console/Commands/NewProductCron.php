<?php

namespace App\Console\Commands;

use App\Mail\NewProductsMail;
use App\Mail\NewUser;
use App\Mail\NewWallet;
use App\Mail\TopShopifyProuctMail;
use App\Product;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NewProductCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newproduct:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command execution completed successfully!';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = \Carbon\Carbon::today()->subDays(7);
        $new_products = Product::where('created_at','>=',$date)->where('status', 1)->where('global', 1)->latest()->limit(6)->get();

        $users_temp = User::role('non-shopify-users')
            ->whereNotIn('email', ['admin@wefullfill.com', 'super_admin@wefullfill.com'])
            ->pluck('email')
            ->toArray();

        $users = [];

        foreach($users_temp as $key => $ut){
            if($ut != null) {
                $ua = [];
                $ua['email'] = $ut;
                $users[$key] = (object)$ua;
            }
        }

        if(count($new_products)>5)
        {
//            try{
//                Mail::to($users)->send(new NewProductsMail($new_products));
//            }
//            catch (\Exception $e){
//            }
        }

//        $users_temp =['yasirnaseer.0@gmail.com'];
//        $users = [];
//
//        foreach($users_temp as $key => $ut){
//            if($ut != null) {
//                $ua = [];
//
//                $ua['email'] = $ut;
//
//                $ua['name'] = 'test';
//
//                $users[$key] = (object)$ua;
//            }
//        }
//
//        try{
//            Mail::to($users)->send(new NewProductsMail($new_products));
//        }
//        catch (\Exception $e){
//
//        }
    }
}
