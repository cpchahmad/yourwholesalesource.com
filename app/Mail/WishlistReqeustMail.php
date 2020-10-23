<?php

namespace App\Mail;

use App\RetailerOrder;
use App\Wishlist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WishlistReqeustMail extends Mailable
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

    public function __construct($sender,Wishlist $wishlist)
    {
        $this->sender = $sender;
        $this->wishlist = $wishlist;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->sender,'Shopify user')->subject('There is a wishlist request')->view('emails.wishlist_request')->with([
            'user' => $this->user,
            'wishlist' => $this->wishlist,
        ]);
    }
}
