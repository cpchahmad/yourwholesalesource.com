<?php

namespace App\Console\Commands;

use App\ErrorLog;
use App\Http\Controllers\WebhookController;
use App\Webhook;
use Illuminate\Console\Command;

class ProcessWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processing each webhook';


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

        $webhooks = Webhook::where('status', 0)->get();
        $helper = new WebhookController();

        foreach ($webhooks as $webhook) {
            if($webhook->status == 'order_created')
                $helper->create_order($webhook);
            if($webhook->status == 'product_deleted')
                $helper->product_deleted($webhook);
            if($webhook->status == 'customer_created')
                $helper->create_customer($webhook);
            if($webhook->status == 'order_cancelled')
                $helper->cancel_order($webhook);

        }
    }
}
