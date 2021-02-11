<?php

namespace App\Console\Commands;

use App\MonthlyDiscountPreference;
use App\MonthlyDiscountSetting;
use App\RetailerOrder;
use App\User;
use Illuminate\Console\Command;

class CheckMonthlySalesTargetForNonShopifyUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check_monthly_sales_target:non_shopify_user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking Whatever a particular user has met sales target set by admin in last month';

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
        $users = User::role('non-shopify-users')
            ->whereNotIn('email', ['wordpress_admin@wefullfill.com','admin@wefullfill.com', 'super_admin@wefullfill.com'])->whereHas('has_orders')->doesntHave('has_shops')->get();
        $admin_settings = MonthlyDiscountSetting::first();

        foreach ($users as $user) {
            $sales = RetailerOrder::where('paid', 1)->where('user_id', $user->id)->where('created_at', '>=' ,now()->subDay(30))->sum('cost_to_pay');

            if($admin_settings && $admin_settings->enable) {
                if($sales >= $admin_settings->sales_target) {
                    MonthlyDiscountPreference::updateOrCreate(
                        [ 'user_id' => $user->id ],
                        [ 'enable' =>  true]
                    );
                }
                else {
                    MonthlyDiscountPreference::updateOrCreate(
                        [ 'user_id' => $user->id ],
                        [ 'enable' =>  false]
                    );
                }
            }
        }
    }
}
