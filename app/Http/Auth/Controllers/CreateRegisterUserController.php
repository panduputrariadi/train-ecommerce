<?php

namespace App\Http\Auth\Controllers;

use App\Http\Auth\Resources\CreateRegisterUserResource;
use App\Http\Controllers\Controller;
use App\Modules\Auth\Actions\CreateRegisterUserAction;
use App\Modules\Auth\Request\CreateRegisterUserRequest;
use Illuminate\Http\Response;

class CreateRegisterUserController extends Controller
{
    public function register(CreateRegisterUserRequest $request)
    {
        $action = new CreateRegisterUserAction();
        $data = $action->execute($request);

        return response()->json([
            'message' => 'User created successfully',
            'status' => Response::HTTP_CREATED,
            'data' => new CreateRegisterUserResource($data)
        ]);
    }
}
