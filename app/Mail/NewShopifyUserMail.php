<?php

namespace App\Mail;

use App\EmailTemplate;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewShopifyUserMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $user;
    private $template;

    private $sender = 'info@fundraisingforacause.com';
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->template = EmailTemplate::find(2);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->sender,'AwarenessDropshipping')->subject('Welcome to AwarenessDropshipping')->view('emails.new_shopify_user')->with([
            'user' => $this->user,
            'template' => $this->template,
        ]);
    }
}
