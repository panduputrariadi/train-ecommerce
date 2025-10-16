<?php

namespace App\Modules\Payment\Listeners;

use App\Modules\Payment\Events\PaymentCompleted;
use App\Modules\Payment\Jobs\PaymentCompleteJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Bus;

class SendPaymentInvoiceListener implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(PaymentCompleted $event): void
    {
        Bus::dispatch(new PaymentCompleteJob($event->payment));
    }
}
