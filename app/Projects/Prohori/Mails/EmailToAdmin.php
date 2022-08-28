<?php

namespace App\Projects\Prohori\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailToAdmin extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The invoice instance.
     *
     * @var mixed
     */
    protected $invoice;

    /**
     * Create a new message instance.
     *
     * @param $invoice
     */
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('projects.prohori.emails.send-invoice-admin')
            ->subject('Request for Invoice')
            ->with(['invoice' => $this->invoice]);
    }
}