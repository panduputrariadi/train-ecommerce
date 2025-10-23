<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Resources\Detail\GetDetailOrderPaymentResource;
use App\Http\Controllers\Controller;
use App\Modules\Order\Models\Order;
use App\Modules\Payment\Action\Detail\GetDetailOrderPaymentDetailAction;
use App\Modules\Payment\Action\Update\ApprovePaymentOrder;
use App\Modules\Payment\Action\Update\DeclinePaymentOrder;
use App\Modules\Payment\Export\SalesReportExport;
use App\Modules\Payment\Models\Payment;
use App\Modules\Share\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PaymentAdminController extends Controller
{
    use AuthorizesRequests;

    public function approvePayment(Payment $code, ApprovePaymentOrder $action): JsonResponse
    {
        $this->authorize('paymentDecission', Payment::class);

        $action->execute($code);

        return response()->json([
            'message' => 'Success approve payment',
        ]);
    }

    public function declinePayment(Payment $code, DeclinePaymentOrder $action): JsonResponse
    {
        $this->authorize('paymentDecission', Payment::class);

        $action->execute($code);

        return response()->json([
            'message' => 'Success approve payment',
        ]);
    }

    public function getDetailOrderPayment(Order $code, GetDetailOrderPaymentDetailAction $action): GetDetailOrderPaymentResource
    {
        $order = $action->execute($code);

        return new GetDetailOrderPaymentResource($order->payment);
    }

    public function export(Request $request)
    {
        $fileName = 'sales_report_'.now()->format('Ymd_His').'.xlsx';

        return Excel::download(
            new SalesReportExport(
                $request->input('start_date'),
                $request->input('end_date'),
                $request->input('status'),
            ),
            $fileName
        );
    }
}
