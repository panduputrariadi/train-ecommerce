<?php

namespace App\Modules\Payment\Providers;

use App\Modules\Payment\Events\PaymentCompleted;
use App\Modules\Payment\Listeners\SendPaymentInvoiceListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PaymentCompleted::class => [
            SendPaymentInvoiceListener::class,
        ],
    ];
}
