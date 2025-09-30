<?php

namespace App\Http\Auth\Controllers;

use App\Http\Auth\Resources\LoginUserResource;
use App\Http\Controllers\Controller;
use App\Modules\Auth\Actions\LoginUserAction;
use App\Modules\Auth\DTOs\LoginUserDto;
use App\Modules\Auth\Request\LoginUserRequest;

class LoginUserController extends Controller
{
    public function login(LoginUserRequest $request, LoginUserAction $action)
    {
        $result = $action->execute($request);

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => $result
        ]);
    }
}
