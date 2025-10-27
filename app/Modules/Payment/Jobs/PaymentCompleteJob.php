<?php

namespace App\Modules\Payment\Jobs;

use App\Modules\Payment\Mails\PaymentCompleteMail;
use App\Modules\Payment\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class PaymentCompleteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $detail = $order->details;

        $detailArray = $detail->map(function ($item) {
            $arr = $item->toArray();

            if (is_object($item->product_data)) {
                $arr['product_data'] = $item->product_data->toArray();
            }

            return $arr;
        });

        $data = [
            'payment' => $payment->toArray(),
            'order' => $order->toArray(),
            'detail' => $detailArray->toArray(),
            'user' => $user->toArray(),
            'profile' => $user->profile?->toArray(),
        ];

        Mail::to($user->email)->queue(new PaymentCompleteMail($data));
    }
}
