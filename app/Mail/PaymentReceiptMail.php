<?php

namespace App\Mail;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $client;
    public $invoice;
    public $payment;

    /**
     * Create a new message instance.
     *
     * @param Client $client
     * @param Invoice $invoice
     * @param Payment $payment
     */
    public function __construct(Client $client, Invoice $invoice, Payment $payment)
    {
        $this->client = $client;
        $this->invoice = $invoice;
        $this->payment = $payment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Reçu de paiement - Hôtel Manager')
                    ->view('emails.payment_receipt')
                    ->with([
                        'client' => $this->client,
                        'invoice' => $this->invoice,
                        'payment' => $this->payment,
                    ]);
    }
}
