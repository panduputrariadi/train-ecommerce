<?php

namespace App\Modules\Payment\Mails;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentCompleteMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $pdf = Pdf::loadView('emails.payment.invoice', [
            'payment' => $this->data['payment'],
            'order' => $this->data['order'],
            'user' => $this->data['user'],
            'profile' => $this->data['profile'],
        ]);

        return $this->subject('Invoice Pembayaran #'.$this->data['order']['code'])
            ->view('emails.payment.complete', $this->data)
            ->attachData(
                $pdf->output(),
                "Invoice-{$this->data['order']['code']}.pdf",
                ['mime' => 'application/pdf']
            );
    }
}
