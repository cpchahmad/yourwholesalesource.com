<?php

namespace App\Mail;

use App\Wallet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WalletBalanceMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private $user = "info@wefullfill.com";

    private $wallet;


    public function __construct(Wallet $wallet)
    {
        $this->wallet = $wallet;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->user,'Wefullfill')->subject('Your Wallet is running out of Balance')->view('emails.wallet_balance')->with([
            'wallet' => $this->wallet,
        ]);
    }
}
