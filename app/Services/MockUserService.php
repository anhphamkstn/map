<?php namespace App\Services;

use App\Helpers\Response;
use App\Helpers\UserHelper;
use App\User;
use Illuminate\Support\Facades\Validator;

class MockUserService
{

    public function all()
    {
        return $users = [
            new User([
                'id'    => 1,
                'name'  => 'Jonny Nguyen',
                'email' => 'lichntbk@gmail.com',
                'phone_1'   => '098778999',
                'role'  => 'administrator',
            ]),
            new User([
                'id'    => 2,
                'name'  => 'dao anh tuan ',
                'email' => 'dao.tuan@gmail.com',
                'phone_1'   => '0938718981',
                'role'  => 'owner',
            ]),
            new User([
                'id'    => 3,
                'name'  => 'Anh Dao',
                'email' => 'anh.dao@gmail.com',
                'phone_1'   => '0988600555',
                'role'  => 'partner',
            ]),
        ];
    }

    public function find()
    {
        $users = $this->allUsers;

        return array_map([$this, 'transform'], $users);
    }

    public function findUser($id)
    {
        foreach ($this->all() as $user)
        {
            if ($id == $user->id) return $user;
        }

        return Response::responseNotFound();
    }

    public function insert($info)
    {
        return $this->transform($info);
    }

    public function update($id, $info)
    {
        return $this->transform($info);
    }

    public function changePassword(User $user, $password)
    {
        return null;
    }

    public function checkExisted($id)
    {
        return true;
    }

    public function logout()
    {

    }

    public function validateInfo()
    {
        return Validator::make([], []);
    }

    public function transform($user)
    {
        if ($user instanceof User)
        {
            return [
                'name'  => $user->name,
                'email' => $user->email,
                'phone_1'   => $user->tel,
                'role'  => $user->role,
            ];
        } else
        {
            return [
                'name'  => $user['name'],
                'email' => $user['email'],
                'phone_1'   => $user['phone_1'],
            ];
        }
    }

}