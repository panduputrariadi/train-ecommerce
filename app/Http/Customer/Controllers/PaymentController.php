<?php

namespace App\Http\Customer\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Customer\Resources\Create\CreatePaymentResource;
use App\Modules\Order\Models\Order;
use App\Modules\Payment\Action\Create\CreatePaymentAction;
use App\Modules\Payment\Request\Create\CreatePaymentRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    use AuthorizesRequests;
    public function store(Order $order, CreatePaymentRequest $request, CreatePaymentAction $action): CreatePaymentResource
    {
        $this->authorize('storePayment', $order);
        $dto = $request->validatedDto();
        $payment = DB::transaction(fn () => $action->execute($order, $dto));
        return new CreatePaymentResource($payment);
    }
}
