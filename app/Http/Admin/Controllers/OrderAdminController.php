<?php

namespace App\Http\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Customer\Collection\GetOrderCollection;
use App\Modules\Order\Action\Read\GetOrderAdminAction;
use App\Modules\Order\Request\GetOrderRequest;

class OrderAdminController extends Controller
{
    public function getOrderAdmin(GetOrderRequest $request, GetOrderAdminAction $action): GetOrderCollection
    {
        $dto = $request->validatedDto();
        $data = $action->execute($dto);
        return new GetOrderCollection($data);
    }
}
