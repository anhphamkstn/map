<?php namespace App\Services;

use App\Helpers\UserHelper;
use App\Events\TaskWasAdded;
use Illuminate\Support\Facades\Validator;
use Event;
use DB;
use App\User;

class TestService extends SharedService
{
    public function demo()
    {

        return "done";

    }
}