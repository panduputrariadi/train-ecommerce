<?php

namespace App\Modules\Auth\Service;

use App\Modules\Auth\Request\CreateRegisterUserRequest;
use App\Modules\Share\Enum\RoleIdUser;
use App\Modules\Share\Enum\UserStatus;
use App\Modules\Share\Models\User;
use App\Modules\Share\Models\UserRole;
use Illuminate\Http\UploadedFile;

class RegisterUserService
{
    public function register(CreateRegisterUserRequest $request, RoleIdUser $roleId): User
    {
        $dto = $request->validatedDto();
        $user = User::create([
            'email' => $dto->email,
            'password' => $dto->password,
            'status' => UserStatus::ACTIVE,
        ]);

        $photoPath = null;
        if ($dto->photo instanceof UploadedFile) {
            $nameSlug = preg_replace('/[^a-z0-9\-]/', '', str_replace(' ', '-', strtolower($dto->name)));
            $filename = "{$user->id}_{$nameSlug}.{$dto->photo->getClientOriginalExtension()}";
            $photoPath = $dto->photo->storeAs('profile-photos', $filename, 'public');
        }

        $user->profile()->create([
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
