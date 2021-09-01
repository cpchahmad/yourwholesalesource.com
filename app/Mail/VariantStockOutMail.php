<?php

namespace App\Mail;

use App\EmailTemplate;
use App\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VariantStockOutMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private $product;
    private $template;
    private $sender = 'info@fundraisingforacause.com';


    public function __construct(Product $product)
    {
        $this->product = $product;
        $this->template = EmailTemplate::find(17);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->sender,'AwarenessDropshipping')->subject('Products Variant Is Out Of Stock on AwarenessDropshipping')->view('emails.variant_stock')->with([
            'product' => $this->product,
            'template' => $this->template,
        ]);
    }
}
