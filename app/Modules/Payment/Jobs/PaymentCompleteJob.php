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
        $payment = $this->payment->loadMissing(['order.user.profile']);
        $order = $payment->order;
        $user = $order->user;

        $data = [
            'payment' => $payment->toArray(),
            'order' => $order->toArray(),
            'user' => $user->toArray(),
            'profile' => $user->profile?->toArray(),
        ];

        Mail::to($user->email)->queue(new PaymentCompleteMail($data));
    }

}
