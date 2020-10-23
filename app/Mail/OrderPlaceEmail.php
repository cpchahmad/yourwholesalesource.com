<?php

namespace App\Mail;

use App\RetailerOrder;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class OrderPlaceEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private $user = "info@wefullfill.com";

    private $sender;
    private $retailerOrder;

    public function __construct($sender,RetailerOrder $retailerOrder)
    {
        $this->sender = $sender;
        $this->retailerOrder = $retailerOrder;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->sender,'Shopify user')->subject('Order is Placed')->view('emails.order_place')->with([
            'user' => $this->user,
            'retail_order' => $this->retailerOrder,
        ]);
    }
}
