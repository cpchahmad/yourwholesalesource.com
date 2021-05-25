<?php

namespace App\Jobs;

use App\ErrorLog;
use App\Http\Controllers\InventoryController;
use App\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncInflowInventory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $inventory;
    public function __construct()
    {
        $this->inventory = new InventoryController();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $log = new ErroLog();
            $log->message = "Sdsfds";
            $log->save();
//            Product::whereNotNull('inflow_id')->chunk(300, function ($products) {
//                foreach ($products as $product) {
//                    $this->inventory->syncProductInventory($product);
//                }
//            });
        }
        catch(\Exception $e) {
            $log = new ErrorLog();
            $log->message = "Sync Inflow Job: ". $e->getMessage();
            $log->save();

        }
    }
}
