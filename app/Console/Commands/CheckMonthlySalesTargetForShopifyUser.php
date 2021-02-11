<?php

namespace App\Console\Commands;

use App\Shop;
use Illuminate\Console\Command;

class CheckMonthlySalesTargetForShopifyUser extends Command
{
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
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $shops = Shop::whereNotNull("shopify_token")->get();
    }
}
