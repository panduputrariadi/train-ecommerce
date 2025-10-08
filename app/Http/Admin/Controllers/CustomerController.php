<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Collections\GetCustomerCollection;
use App\Http\Controllers\Controller;
use App\Modules\Customer\Action\Read\GetListCustomerAction;
use App\Modules\Customer\Action\Update\BlockCustomerAction;
use App\Modules\Customer\Action\Update\UnblockCustomerAction;
use App\Modules\Customer\Request\GetListCustomer;
use App\Modules\Share\Models\UserProfile;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    public function index(GetListCustomerAction $action, GetListCustomer $request): GetCustomerCollection
    {
        $dto = $request->validatedDto();
        $data = $action->execute($dto);

        return new GetCustomerCollection($data);
    }

    public function blockCustomer(UserProfile $profile, BlockCustomerAction $action): JsonResponse
    {
        $action->execute($profile);
        return response()->json([
            'message' => 'Success block customer'
        ]);
    }

    public function unblockCustomer(UserProfile $profile, UnblockCustomerAction $action): JsonResponse
    {
        $action->execute($profile);
        return response()->json([
            'message' => 'Success block customer'
        ]);
    }
}
