<?php
namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Helpers\Response;
use App\Helpers\UserHelper;
use App\Http\Requests;
use Mockery\CountValidator\Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Input;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\FactoryService;
use Illuminate\Support\Facades\DB;
use App\Role;


class UserController extends ApiController
{
    public function __construct()
    {
        $this->service = FactoryService::getUserService();
    }

    public function postLogin(Request $request)
    {
        $count = DB::table('Users')->where('name','=','jonny')->get();
        return $count;
    }
    public function store(Request $request)
    {
        $name=$request['user'];
        $pass=$request['password'];
        DB::table('Users')->insert(
            ['name' => $name, 'password' => $pass]
        );
        return response(['OK']);
    }
}
