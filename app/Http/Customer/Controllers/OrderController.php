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
use App\Modules\Payment\Action\Read\GetInvoiceCustomer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use AuthorizesRequests;

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
        $this->authorize('view', $order);
        $data = $action->execute($order);

        return new GetDetailOrderResource($data);
    }

    public function getOrderInvoice(Order $order, GetInvoiceCustomer $action)
    {
        $this->authorize('downloadInvoice', $order);

        $order = $action->execute($order);

        $pdf = Pdf::loadView('pdf.order_invoice', ['order' => $order]);

        return $pdf->download("invoice_{$order->code}.pdf");
    }
}
