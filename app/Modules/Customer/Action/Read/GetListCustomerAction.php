<?php

namespace App\Modules\Customer\Action\Read;

use App\Modules\Customer\DTOs\GetListCustomerDto;
use App\Modules\Share\Models\UserProfile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetListCustomerAction
{
    public function execute(GetListCustomerDto $dto): LengthAwarePaginator
    {
        $search = $dto->search ?? '';
        $perPage = $dto->perPage ?? 10;

        $query = UserProfile::with(['user.roles'])->customer()
            ->when(filled($search), function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            });

        return $query->paginate($perPage);
    }
}
