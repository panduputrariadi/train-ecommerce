<?php

namespace App\Modules\Payment\Jobs;

use App\Modules\Payment\Mails\PaymentCompleteMail;
use App\Modules\Payment\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class PaymentCompleteJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public Payment $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function handle(): void
    {
        $order = $this->payment->order;

        if (! $order || ! $order->user) {
            return;
        }

        // Kirim email invoice
        Mail::to($order->user->email)->queue(
            new PaymentCompleteMail($this->payment, $order)
        );
    }
}
