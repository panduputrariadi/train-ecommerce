<?php

namespace App\Http\Auth\Controllers;

use App\Http\Auth\Resources\CreateRegisterUserResource;
use App\Http\Auth\Resources\LoginUserResource;
use App\Http\Controllers\Controller;
use App\Modules\Auth\Actions\CreateRegisterAdminAction;
use App\Modules\Auth\Actions\CreateRegisterUserAction;
use App\Modules\Auth\Actions\LoginAdminAction;
use App\Modules\Auth\Actions\LoginUserAction;
use App\Modules\Auth\Actions\LogoutUserAction;
use App\Modules\Auth\Request\CreateRegisterUserRequest;
use App\Modules\Auth\Request\LoginAdminRequest;
use App\Modules\Auth\Request\LoginUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(CreateRegisterUserRequest $request, CreateRegisterUserAction $createRegisterUserAction): CreateRegisterUserResource
    {
        $data = DB::transaction(fn () => $createRegisterUserAction->execute($request));

        return new CreateRegisterUserResource($data);
    }

    public function registerAdmin(CreateRegisterUserRequest $request, CreateRegisterAdminAction $action): CreateRegisterUserResource
    {
        $data = DB::transaction(fn () => $action->execute($request));

        return new CreateRegisterUserResource($data);
    }

    public function login(LoginUserRequest $request, LoginUserAction $action): LoginUserResource
    {
        $dto = $request->validatedLogin();
        $data = DB::transaction(fn () => $action->execute($dto));

        return new LoginUserResource($data['user'], $data['token']);
    }

    public function loginAdmin(LoginAdminRequest $request, LoginAdminAction $action): LoginUserResource
    {
        $dto = $request->validatedLogin();
        $data = DB::transaction(fn () => $action->execute($dto));

        return new LoginUserResource($data['user'], $data['token']);
    }

    public function logout(Request $request, LogoutUserAction $action): JsonResponse
    {
        $action->execute($request);

        return response()->json([
            'message' => 'Logout successful',
        ]);
    }
}
