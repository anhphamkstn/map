<?php namespace App\Http\Middleware;

use App\Helpers\Response;
use App\Helpers\UserHelper;
use App\User;
use Closure;

class VerifyUserActive
{

    public function handle($request, Closure $next)
    {
        $user = UserHelper::getAuthenticatedUser();
        if ($user->active == User::INACTIVE)
        {
            return Response::responseInactiveUser();
        }

        return $next($request);
    }
}
