<?php

namespace App\Mail;

use App\Wallet;
use App\Wishlist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WalletRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $user = "info@wefullfill.com";

    private $sender;
    private $wallet;

    public function __construct($sender,Wallet $wallet)
    {
        $this->sender = $sender;
        $this->wallet = $wallet;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->sender,'Shopify user')->subject('There is a wallet request')->view('emails.wallet_request')->with([
            'user' => $this->user,
            'wallet' => $this->wallet,
        ]);
    }
}
