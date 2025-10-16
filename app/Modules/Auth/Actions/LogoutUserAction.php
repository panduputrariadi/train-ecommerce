<?php

namespace App\Modules\Auth\Actions;

use Illuminate\Http\Request;

class LogoutUserAction
{
    public function execute(Request $request): void
    {
        $user = $request->user();

        if ($user === null) {
            return;
        }

        $user->currentAccessToken()?->delete();

    }
}
