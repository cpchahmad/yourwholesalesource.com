<?php namespace App\Jobs;

use App\DeletedShop;
use App\Shop;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AppUnistalledJob implements ShouldQueue
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
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $shop = Shop::where('shopify_domain', $this->shopDomain)->first();
        $user = $shop->has_user()->first();


        $deleted_shop = new DeletedShop();
        $deleted_shop->shop_id = $shop->id;
        $deleted_shop->shop_domain = $this->shopDomain;
        $deleted_shop->user_id = $user->id;
        $deleted_shop->save();


        $shop->delete();
        $user->has_shops()->detach([$shop->id]);
    }
}
