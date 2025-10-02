<?php

namespace App\Modules\Auth\Actions;

use App\Modules\Auth\Request\CreateRegisterUserRequest;
use App\Modules\Auth\Service\RegisterUserService;
use App\Modules\Share\Enum\RoleIdUser;
use App\Modules\Share\Enum\UserStatus;
use App\Modules\Share\Models\User;
use Illuminate\Http\UploadedFile;

class CreateRegisterUserAction
{
    public function __construct(protected RegisterUserService $registerUserService)
    {

    }
    public function execute(CreateRegisterUserRequest $request): User
    {
        return $this->registerUserService->register($request,RoleIdUser::REGULAR_CUSTOMER);
    }
}
