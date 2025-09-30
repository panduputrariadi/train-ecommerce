<?php

namespace App\Http\Auth\Controllers;

use App\Http\Auth\Resources\CreateRegisterUserResource;
use App\Http\Auth\Resources\LoginUserResource;
use App\Http\Controllers\Controller;
use App\Modules\Auth\Actions\CreateRegisterUserAction;
use App\Modules\Auth\Actions\LoginUserAction;
use App\Modules\Auth\Actions\LogoutUserAction;
use App\Modules\Auth\Request\CreateRegisterUserRequest;
use App\Modules\Auth\Request\LoginUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(CreateRegisterUserRequest $request, CreateRegisterUserAction $createRegisterUserAction)
    {
        $data = DB::transaction(fn () => $createRegisterUserAction->execute($request));
        return new CreateRegisterUserResource($data);
    }

    public function login(LoginUserRequest $request, LoginUserAction $action)
    {
        $data = DB::transaction(fn ()=> $action->execute($request));
        return new LoginUserResource($data['user'], $data['token']);
    }

    public function logout(Request $request, LogoutUserAction $action)
    {
        $action->execute($request);

        return response()->json([
            'message' => 'Logout successful',
        ]);
    }
}
