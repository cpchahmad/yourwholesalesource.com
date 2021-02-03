<?php

namespace App\Console\Commands;

use App\Http\Controllers\HelperController;
use App\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateWinningProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updatewinning:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating Winning Products Daily';

    public $helper;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->helper = new HelperController();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $woocommerce = $this->helper->getWooCommerceAdminShop();

        $top_products_stores = Product::join('retailer_products', function ($join) {
            $join->on('retailer_products.linked_product_id', '=', 'products.id')
                ->join('retailer_order_line_items', function ($join) {
                    $join->on('retailer_order_line_items.shopify_product_id', '=', 'retailer_products.shopify_id')
                        ->join('retailer_orders', function ($o) {
                            $o->on('retailer_order_line_items.retailer_order_id', '=', 'retailer_orders.id')
                                ->where('retailer_orders.paid', '>=', 1);
                        });
                });
        })->select('products.*')
            ->groupBy('products.id')
            ->get()
            ->take(5);

        foreach ($top_products_stores as $product) {
            $product->has_categories()->sync(466);
            $product->save();

            $product_categories = $product->has_categories->pluck('woocommerce_id')->toArray();
            $categories_id_array = [];

            foreach($product_categories as $item) {
                array_push($categories_id_array, [
                    'id' => $item,
                ]);
            }

            $productdata = [
                "categories" => $categories_id_array
            ];

            /*Updating Product On Woocommerce*/
            $response = $woocommerce->put('products/'. $product->woocommerce_id, $productdata);
        }
    }
}
