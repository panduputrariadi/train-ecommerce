<?php

namespace App\Http\Customer\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Customer\Resources\Create\CreatePaymentResource;
use App\Modules\Payment\Action\Create\CreatePaymentAction;
use App\Modules\Payment\Request\Create\CreatePaymentRequest;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function store(CreatePaymentRequest $request, CreatePaymentAction $action): CreatePaymentResource
    {
        $dto = $request->validatedDto();
        $data = DB::transaction(fn () => $action->execute($dto));

        return new CreatePaymentResource($data);
    }
}
