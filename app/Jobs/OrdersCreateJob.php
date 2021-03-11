<?php namespace App\Jobs;

use App\Customer;
use App\ErrorLog;
use App\FulfillmentLineItem;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AdminMaintainerController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WebhookController;
use App\Mail\OrderPlaceEmail;
use App\Mail\WalletBalanceMail;
use App\OrderFulfillment;
use App\OrderLog;
use App\OrderTransaction;
use App\ProductVariant;
use App\RetailerOrder;
use App\RetailerOrderLineItem;
use App\RetailerProduct;
use App\RetailerProductVariant;
use App\ShippingRate;
use App\Shop;
use App\User;
use App\WalletLog;
use App\WalletSetting;
use App\Webhook;
use App\Zone;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrdersCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Shop's myshopify domain
     *
     * @var string
     */
    public $shopDomain;

    /**
     * The webhook data
     *
     * @var object
     */
    public $data;
    private $log;
    private $notify;
    private $admin;
    private $inventory;



    /**
     * Create a new job instance.
     *
     * @param string $shopDomain The shop's myshopify domain
     * @param object $data    The webhook data (JSON decoded)
     *
     * @return void
     */
    public function __construct($shopDomain, $data)
    {
        $this->shopDomain = $shopDomain;
        //$this->data = json_encode($data);
        $this->log = new ActivityLogController();
        $this->notify = new NotificationController();
        $this->admin = new AdminMaintainerController();
        $this->inventory = new InventoryController();

        $log = new ErrorLog();
        $log->message = "const new here again";
        $log->save();
//
//        $hook = new Webhook();
//        $hook->type = 'order_created';
//        $hook->status = 0;
//        $hook->body = json_encode($data);
//        $hook->save();





    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $log = new ErrorLog();
        $log->message = "bodyy";
        $log->save();
        //    $webhook = new WebhookController();
    //    $webhook->createOrder($this->data, $this->shopDomain);


    }
}
