<?php

namespace App\Modules\Payment\Mails;

use App\Modules\Order\Models\Order;
use App\Modules\Payment\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentCompleteMail extends Mailable
{
    use Queueable, SerializesModels;

    public Payment $payment;
    public Order $order;
    public $user;

    public function __construct(Payment $payment, Order $order)
    {
        $this->payment = $payment;
        $this->order = $order;
        $this->user = $order->user;
    }

    public function build()
    {
        $pdf = Pdf::loadView('emails.payment.invoice', [
            'order' => $this->order,
            'payment' => $this->payment,
            'user' => $this->user,
        ]);

        return $this->subject('Invoice Pembayaran #' . $this->order->code)
            ->view('emails.payment.complete')
            ->attachData($pdf->output(), "Invoice-{$this->order->code}.pdf", [
                'mime' => 'application/pdf',
            ]);
    }
}
