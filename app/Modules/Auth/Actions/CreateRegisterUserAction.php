<?php

namespace App\Modules\Auth\Actions;

use App\Modules\Auth\DTOs\CreateRegisterUserDto;
use App\Modules\Auth\Enum\UserStatus;
use App\Modules\Auth\Models\User;
use App\Modules\Auth\Request\CreateRegisterUserRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class CreateRegisterUserAction
{
    public function execute(CreateRegisterUserRequest $request): User
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $dto = CreateRegisterUserDto::fromArray($data);
            $user = User::create([
                'email' => $dto->email,
                'password' => $dto->password,
                'is_active' => UserStatus::ACTIVE,
            ]);
            $photoPath = null;
            if ($dto->photo instanceof UploadedFile) {
                $nameSlug = preg_replace('/[^a-z0-9\-]/', '', str_replace(' ', '-', strtolower($dto->name)));
                $filename = "{$user->id}_{$nameSlug}.{$dto->photo->getClientOriginalExtension()}";
                $photoPath = $dto->photo->storeAs('profile-photos', $filename, 'public');
            }
            $user->profile()->create([
                'name' => $dto->name,
                'otp_id' => $dto->otp_id,
                'photo' => $photoPath,
                'phone' => $dto->phone,
            ]);

            DB::commit();
        }catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        return $user->fresh();
    }
}
