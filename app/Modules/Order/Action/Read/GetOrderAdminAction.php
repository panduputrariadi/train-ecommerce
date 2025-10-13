<?php

namespace App\Modules\Order\Action\Read;

use App\Modules\Order\DTOs\GetOrderDto;
use App\Modules\Order\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetOrderAdminAction
{
    public function execute(GetOrderDto $dto): LengthAwarePaginator
    {
        $search = $dto->search ?? '';
        $perPage = $dto->perPage ?? 10;

        $query = Order::with(['details']);
        if (filled($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('note', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }
}
