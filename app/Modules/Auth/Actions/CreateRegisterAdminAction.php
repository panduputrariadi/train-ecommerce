<?php

namespace App\Modules\Auth\Actions;

use App\Modules\Auth\Request\CreateRegisterUserRequest;
use App\Modules\Auth\Service\RegisterUserService;
use App\Modules\Share\Enum\RoleIdUser;
use App\Modules\Share\Models\User;

class CreateRegisterAdminAction
{
    public function __construct(protected RegisterUserService $registerUserService) {}

    public function execute(CreateRegisterUserRequest $request): User
    {
        return $this->registerUserService->register($request, RoleIdUser::ADMIN_SUPER);
    }
}
