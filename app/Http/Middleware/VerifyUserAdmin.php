<?php

namespace App\Http\Middleware;

use App\Helpers\Response;
use App\Helpers\UserHelper;
use App\Role;
use Closure;

class VerifyUserAdmin
{

    public function handle($request, Closure $next)
    {
        $user = UserHelper::getAuthenticatedUser();

        //if is not administrator => rejected
        if ($user->role->id !== Role::ROLE_ADMIN && $user->role->id !== Role::ROLE_SYS_ADMIN)
        {
            return Response::responseForbidden();
        }

        return $next($request);
    }
}
