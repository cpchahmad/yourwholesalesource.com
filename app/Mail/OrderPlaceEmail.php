<?php

namespace App\Mail;

use App\EmailTemplate;
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

    private $user = "cp.chahmad@gmail.com";

    private $sender;
    private $retailerOrder;
    private $template;

    public function __construct($sender,RetailerOrder $retailerOrder)
    {
        $this->sender = $sender;
        $this->retailerOrder = $retailerOrder;
        $this->template = EmailTemplate::find(3);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->user,'Wefullfill')->subject('Order is Placed')->view('emails.order_place')->with([
            'template' => $this->template,
            'order' => $this->retailerOrder,
        ]);
    }
}
