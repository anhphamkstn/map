<?php

namespace App\Http\Middleware;

use App\Helpers\Response;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTAuthenticate
{

    public function handle($request, Closure $next)
    {
        //get token
        $token = JWTAuth::getToken();

        try {
            if (!$user = JWTAuth::authenticate($token)) {

                return Response::responseUnauthorized();
            }
        } catch (TokenBlacklistedException $e) {

            return Response::responseTokenBlacklist();

        } catch (TokenExpiredException $e) {

            return Response::responseTokenExpired();

        } catch (JWTException $e) {

            return Response::responseTokenInvalid();
        }

        //get payload of token
        $payload = JWTAuth::getPayload($token);

        //check earliest_valid of user
        if ($user->earliest_valid > $payload->get('iat'))
        {
            //blacklist this token
            JWTAuth::invalidate($token);

            return Response::responseTokenBlacklist();
        }

        return $next($request);
    }

}
