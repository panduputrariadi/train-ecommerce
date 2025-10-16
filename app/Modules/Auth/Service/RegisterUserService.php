<?php

namespace App\Modules\Auth\Service;

use App\Modules\Auth\Request\CreateRegisterUserRequest;
use App\Modules\Share\Enum\RoleIdUser;
use App\Modules\Share\Enum\UserStatus;
use App\Modules\Share\Helper\CodeGenerator;
use App\Modules\Share\Models\User;
use App\Modules\Share\Models\UserRole;
use App\Modules\Share\Traits\HandlePhotoUploadTrait;

class RegisterUserService
{
    use HandlePhotoUploadTrait;

    public function register(CreateRegisterUserRequest $request, RoleIdUser $roleId): User
    {
        $dto = $request->validatedDto();
        $user = User::create([
            'email' => $dto->email,
            'password' => $dto->password,
            'status' => UserStatus::ACTIVE,
        ]);

        $photoPath = $this->uploadPhoto(
            $dto->photo,
            'profile-photos',
            $user->id,
            $dto->name
        );

        $code = CodeGenerator::generate('user_profiles', 'UPR', $dto->name);

        $user->profile()->create([
            'code' => $code,
            'name' => $dto->name,
            'otp_id' => $dto->otpId,
            'photo' => $photoPath,
            'phone' => $dto->phone,
        ]);

        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $roleId->value,
        ]);

        return $user->fresh(['profile', 'roles']);

    }
}
