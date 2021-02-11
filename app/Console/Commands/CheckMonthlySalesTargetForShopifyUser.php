<?php

namespace App\Console\Commands;

use App\MonthlyDiscountPreference;
use App\MonthlyDiscountSetting;
use App\Shop;
use Illuminate\Console\Command;

class CheckMonthlySalesTargetForShopifyUser extends Command
{

    public $admin_settings;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check_monthly_sales_target:shopify_user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking Whatever a particular shop has met sales target set by admin in last month';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->admin_settings = MonthlyDiscountSetting::first();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $shops = Shop::whereNotNull("shopify_token")->get();

        foreach ($shops as $shop) {
            $sales = RetailerOrder::where('paid', 1)->where('shop_id', $shop->id)->where('created_at', '>=' ,now()->subDay(30))->sum('cost_to_pay');

            if($this->admin_settings && $this->admin_settings->enabled) {
                if($sales >= $this->admin_settings->sales_target) {
                    MonthlyDiscountPreference::updateOrCreate(
                        [ 'shop_id' => $shop->id ],
                        [ 'enable' =>  true]
                    );
                }
                else {
                    MonthlyDiscountPreference::updateOrCreate(
                        [ 'shop_id' => $shop->id ],
                        [ 'enable' =>  false]
                    );
                }

            }
        }
    }
}
