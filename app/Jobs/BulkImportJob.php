<?php

namespace App\Jobs;

use App\Http\Controllers\ProductController;
use App\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BulkImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $helper;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->helper = new ProductController();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $products = Product::where('to_woocommerce', null)->limit(2)->pluck('id')->toArray();

        foreach($products as $id) {
            $this->helper->importToWoocommerce($id);
        }

    }
}
