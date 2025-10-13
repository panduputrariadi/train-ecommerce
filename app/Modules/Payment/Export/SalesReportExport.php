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

    public function __construct(
        protected ?string $startDate = null,
        protected ?string $endDate = null,
        protected ?string $status = null
    ) {}

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

    public function title(): string
    {
        return 'Sales Report';
    }
}
