<?php

namespace App\Modules\Payment\Export;

use App\Modules\Order\Models\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesReportExport implements FromView, ShouldAutoSize, WithTitle
{
    use Exportable;

    /**
     * Constructor
     *
     * @param string|null $startDate The start date of the payment orders to export
     * @param string|null $endDate The end date of the payment orders to export
     * @param string|null $status The status of the payment orders to export
     */
    public function __construct(
        protected ?string $startDate = null,
        protected ?string $endDate = null,
        protected ?string $status = null
    ) {}

    /**
     * Return a view instance for the sales report export
     *
     * The view will contain the payment orders with the given status and date range
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function view(): View
    {
        $query = Order::query()
            ->with(['user.profile', 'payment.method'])
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->startDate && $this->endDate, fn ($q) => $q->whereBetween('created_at', [$this->startDate, $this->endDate])
            );

        return view('exports.sales_report', [
            'orders' => $query->orderByDesc('created_at')->get(),
        ]);
    }

    /**
     * Return the title of the sales report export
     *
     * @return string The title of the sales report export
     */
    public function title(): string
    {
        return 'Sales Report';
    }
}
