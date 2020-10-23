<?php

namespace App\Mail;

use App\RetailerOrder;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $user;
    private $order;

    private $sender = 'info@wefullfill.com';
    public function __construct(User $user, RetailerOrder $order)
    {

        $this->user = $user;
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->sender,'Wefullfill')->subject('Order Status Updated')->view('emails.order_status')->with([
            'user' => $this->user,
            'order' => $this->order,
        ]);
    }
}
