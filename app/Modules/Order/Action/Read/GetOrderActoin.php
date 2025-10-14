<?php

namespace App\Modules\Order\Action\Read;

use App\Modules\Order\DTOs\GetOrderDto;
use App\Modules\Order\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class GetOrderActoin
{
    public function execute(GetOrderDto $dto): LengthAwarePaginator
    {
        $perPage = $dto->perPage ?? 10;

        $query = Order::with(['details'])->where('user_id', Auth::user()->id)->search($dto->search);

        return $query->paginate($perPage);
    }
}
