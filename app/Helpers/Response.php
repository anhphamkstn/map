<?php namespace App\Helpers;

class Response
{

    public static function response($result, $statusCode = 200, $message = 'OK', $headers = [])
    {
        $responseBody = array (
            'code'    => $statusCode,
            'message' => $message,
            'result'  => $result
        );

        return response()->json($responseBody, $statusCode, $headers);
    }

    public static function responseWithPageCount($result, $statusCode = 200, $message = 'OK', $headers = [], $pageCount = 0)
    {
        $responseBody = array (
            'code'    => $statusCode,
            'message' => $message,
            'result'  => $result,
            'page_count' => $pageCount
        );

        return response()->json($responseBody, $statusCode, $headers);
    }

    public static function responseWithError($message, $statusCode, $headers = [])
    {
        return self::response([], $statusCode, $message, $headers);
    }

    public static function responseInactiveUser($message = 'User not activate.', $headers = [])
    {
        return self::response([], 401, $message, $headers);
    }

    public static function responseNotFound($message = 'Resource is not found', $headers = [])
    {
        return self::response([], 404, $message, $headers);
    }

    public static function responseInvalidCredentials($message = 'Invalid email or password.', $headers = [])
    {
        return self::response([], 401, $message, $headers);
    }

    public static function responseCouldNotCreateToken($message = 'Could not create token.', $headers = [])
    {
        return self::response([], 500, $message, $headers);
    }

    public static function responseTokenBlacklist($message = 'Token has been blacklisted.', $headers = [])
    {
        return self::response([], 429, $message, $headers);
    }

    public static function responseForbidden($message = 'Forbidden.', $headers = [])
    {
        return self::response([], 403, $message, $headers);
    }

    public static function responseUnauthorized($message = 'Unauthorized.', $headers = [])
    {
        return self::response([], 401, $message, $headers);
    }

    public static function responseTokenExpired($message = 'Token is expired.', $headers = [])
    {
        return self::response([], 419, $message, $headers);
    }

    public static function responseTokenInvalid($message = 'Invalid bearer token.', $headers = [])
    {
        return self::response([], 401, $message, $headers);
    }

    public static function responseOauthTokenInvalid($message = 'Invalid oauth token.', $headers = [])
    {
        return self::response([], 401, $message, $headers);
    }

    public static function responseTokenAbsent($message = 'Token is absent.', $headers = [])
    {
        return self::response([], 401, $message, $headers);
    }

    public static function responseEmailNotFound($message = 'Email not found.', $headers = [])
    {
        return self::response([], 401, $message, $headers);
    }

    public static function responseProviderInvalid($message = 'Provider is invalid.', $headers = [])
    {
        return self::response([], 401, $message, $headers);
    }

    public static function responseTokenIsMissing($message = 'Token is missing.', $headers = [])
    {
        return self::response([], 428, $message, $headers);
    }

    public static function responseValidateFailed($message = 'Validate failed.', $data = [], $headers = [])
    {
        return self::response($data, 422, $message, $headers);
    }

    public static function responseNotBelongTo($message = 'Resource is not belongs to user', $data = [], $headers = [])
    {
        return self::response($data, 403, $message, $headers);
    }

    public static function responseMissingParameter($message = 'Missing parameter', $data = [], $headers = [])
    {
        return self::response($data, 429, $message, $headers);
    }

    public static function responseInvalidUser($message = 'Invalid user.', $data = [], $headers = [])
    {
        return self::response($data, 423, $message, $headers);
    }

    public static function responseResetPasswordFails($message = 'Reset password failed.', $data = [], $headers = [])
    {
        return self::response([], 424, $message, $headers);
    }

    public static function responseFileTypeNotAllowed($message = 'File type is not allowed.', $data = [], $headers = [])
    {
        return self::response($data, 426, $message, $headers);
    }

    public static function responseNotSupportedType($message = 'This type is not supported.', $data = [], $headers = [])
    {
        return self::response($data, 427, $message, $headers);
    }

    public static function responseFileNotFound($message = 'File not found.', $data = [], $headers = [])
    {
        return self::response($data, 430, $message, $headers);
    }
}

