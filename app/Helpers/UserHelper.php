<?php namespace App\Helpers;

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserHelper
{

    const MOBILE_APP = 'mobile';
    const WEB_APP = 'web';

    public static function getAuthenticatedUser()
    {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return null;
        }

        return $user;
    }

    public static function checkExistedEmail($email)
    {
        return User::where('email', $email)->count() > 0 ? true : false;
    }

    public static function getAppType($encodeStr)
    {
        $app = DB::table('apps')->select('type')
            ->where('app_id', $encodeStr)
            ->first();

        return isset($app->type) ? $app->type : null;
    }

    public static function sendVerifyEmail($userId)
    {
        $user = User::findOrFail($userId);

        $data['email'] = $user->email;
        $data['activeLink'] = url('verify/' . $user->hash);

        Mail::send('emails.register_verification', $data, function ($message) use ($data) {
            $message->to($data['email'])->subject('Activating your Account');
        });
    }

    public static function sendWelcomeEmailSocial($info)
    {
        $data = [
            'email' => $info['email'],
            'name' => isset($info['name']) ? $info['name'] : '',
            'password' => $info['password'],
        ];

        Mail::send('emails.register_gg_welcome', $data, function ($message) use ($data) {
            $message->to($data['email'])->subject('Welcome to Bontrax');
        });
    }

    public static function checkUserHash($hash)
    {
        $user = User::where('hash', $hash)->first();

        if ($user) {
            $user->update([
                'is_verified' => 1
            ]);

            return $user;
        }

        return false;
    }

    public static function checkActivated($userId)
    {
        $user = User::findOrFail($userId);

        if ($user) {
            return $user->active == 1 ? true : false;
        }

        return false;
    }
}