<?php

namespace App\Modules\Payment\Listeners;

use App\Modules\Payment\Events\PaymentCompleted;
use App\Modules\Payment\Jobs\PaymentCompleteJob;
use App\Modules\Payment\Notifications\PaymentGroupNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPaymentInvoiceListener implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(PaymentCompleted $event): void
    {
        PaymentCompleteJob::dispatch($event->payment);

        $user = $event->payment->order->user;

        $user->notify(new PaymentGroupNotification(
            'payment.completed',
            [
                'payment_id' => $event->payment->id,
                'order_id' => $event->payment->order->id,
                'status' => $event->payment->status->value,
            ]
        ));
    }
}
