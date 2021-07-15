<?php

namespace App\Mail;

use App\EmailTemplate;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StoreIntegrationMail extends Mailable
{
    use Queueable, SerializesModels;


    public $template;

    private $sender = 'info@tetralogicx.com';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->template = EmailTemplate::find(21);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->sender,'YourWholeSaleSource')->subject('Continue with store integration')->view('emails.integration')->with([
            'template' => $this->template,
        ]);
    }
}
