<?php

namespace App\Modules\Order\Action\Read;

use App\Modules\Order\DTOs\GetOrderDto;
use App\Modules\Order\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetOrderAdminAction
{
    public function execute(GetOrderDto $dto): LengthAwarePaginator
    {
        $perPage = $dto->perPage ?? 10;

        $query = Order::with(['details'])->search($dto->search);

        return $query->paginate($perPage);
    }
}
