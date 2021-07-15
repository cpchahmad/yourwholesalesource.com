<?php

namespace App\Mail;

use App\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResourcesMail extends Mailable
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
        $this->template = EmailTemplate::find(22);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->sender,'YourWholeSaleSource')->subject('Dont forgot to check out these useful resources')->view('emails.resources-email')->with([
            'template' => $this->template,
        ]);
    }
}
