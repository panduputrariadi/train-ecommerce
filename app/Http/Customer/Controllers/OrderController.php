<?php

namespace App\Http\Customer\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Customer\Collection\GetOrderCollection;
use App\Http\Customer\Resources\CreateOrderResource;
use App\Http\Customer\Resources\GetDetailOrderResource;
use App\Modules\Order\Action\Create\CreateOrderAction;
use App\Modules\Order\Action\Detail\GetDetailOrderAction;
use App\Modules\Order\Action\Read\GetOrderActoin;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Request\CreateOrderRequest;
use App\Modules\Order\Request\GetOrderRequest;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(CreateOrderRequest $request, CreateOrderAction $action): CreateOrderResource
    {
        $dto = $request->validatedDto();
        $data = DB::transaction(fn () => $action->execute($dto));

        return new CreateOrderResource($data);
    }

    public function get(GetOrderRequest $request, GetOrderActoin $action): GetOrderCollection
    {
        $dto = $request->validatedDto();
        $data = $action->execute($dto);
        return new GetOrderCollection($data);
    }

    public function detail(Order $order, GetDetailOrderAction $action): GetDetailOrderResource
    {
        $data = $action->execute($order);
        return new GetDetailOrderResource($data);
    }
}
