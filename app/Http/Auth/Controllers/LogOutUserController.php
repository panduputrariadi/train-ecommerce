<?php

namespace App\Http\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Actions\LogoutUserAction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LogOutUserController extends Controller
{
    public function logout(Request $request, LogoutUserAction $action)
    {
        $action->execute($request);

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Logout successful',
        ]);
    }
}
