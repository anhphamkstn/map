<?php namespace App\Services;

use App\Helpers\Response;
use App\Helpers\UserHelper;
use App\UploadFile;
use App\User;
use App\Events\UserWasRegistered;
use Illuminate\Support\Facades\Validator;
use Event;
use Mockery\CountValidator\Exception;

class UserService extends BaseService
{
    protected $fillable = ['avatar_id', 'name', 'email', 'role_id', 'active', 'phone_1', 'phone_2'];

    protected $bcrypt = ['password'];

    public function getUserListingQueryBuilder($userFilter)
    {
        $query = User::query();

        if (isset($userFilter['fields'])) {
            $responseFields = explode(',', $userFilter['fields']);
            for ($i = 0; $i < count($responseFields); $i++) {
                if (!in_array($responseFields[$i], $this->fillable))
                    unset($responseFields[$i]);
            }

            $responseFields = array_values($responseFields);

            $responseFields[] = 'id';
            call_user_func_array(array($query, 'select'), $responseFields);
        }

        if (isset($userFilter['search']) && trim($userFilter['search'])) {
            $keyword = $userFilter['search'];

            if (empty($userFilter['searchFields']))
                $query->where(function ($query) use ($keyword) {
                    $query->where('name', 'ilike', "%{$keyword}%")
                        ->orWhere('email', 'ilike', "%{$keyword}%")
                        ->orWhere('phone_1', 'ilike', "%{$keyword}%")
                        ->orWhere('phone_2', 'ilike', "%{$keyword}%");
                });
            else {
                $searchFields = explode(',', $userFilter['searchFields']);

                $query->where(function ($query) use ($searchFields, $keyword) {
                    foreach ($searchFields as $searchField) {
                        if (in_array($searchField, $this->fillable)) {
                            $query->orWhere($searchField, 'ilike', "%{$keyword}%");
                        }

                    }
                });
            }
        }

        if (isset($userFilter['role_id'])) {
            $role_id = $userFilter['role_id'];
            $query->where('role_id', '=', $role_id);
        }

        if (isset($userFilter['active'])) {
            $isUserActive = $userFilter['active'];
            $query->where('active', '=', $isUserActive);
        }

        $userFilter['sortBy'] = isset($userFilter['sortBy']) ? $userFilter['sortBy'] : 'id';
        $userFilter['orderDirection'] = isset($userFilter['orderDirection']) ? $userFilter['orderDirection'] : 'asc';

        $query->orderBy($userFilter['sortBy'], $userFilter['orderDirection']);

        return $query;
    }

    public function getUsersFromQueryBuilder($query)
    {
        $data = [];

        $users = $query->get();

        if (!$users->isEmpty()) {
            foreach ($users as $user) {
                $data[] = $this->transform($user);
            }
        }

        return $data;
    }


    public function insert($info)
    {
        foreach ($this->bcrypt as $attr) {
            $info[$attr] = bcrypt($info[$attr]);
        }

        if (!isset($info['role_id']))
            $info['role_id'] = 3;

        $user = User::create($info);

        //make an event
        Event::fire(new UserWasRegistered($user));

        return $this->transform($user);
    }

    public function checkExisted($id)
    {
        $user = User::find($id);

        if (!$user) {
            return Response::responseNotFound();
        }

        return $user;
    }

    public function findResource($id)
    {
        return User::find($id);
    }

    public function update(User $instance, $info)
    {
        foreach ($this->fillable as $attr) {
            if (isset($info[$attr])) {
                $instance->$attr = $info[$attr];
            }
        }

        foreach ($this->bcrypt as $attr) {
            if (isset($info[$attr]) && trim($info[$attr])) {
                $instance->$attr = bcrypt($info[$attr]);
            }
        }

        $instance->save();

        return $this->transform($instance);
    }

    public function changePassword(User $user, $password)
    {
        $password = bcrypt($password);

        $user->update([
            'password' => $password,
        ]);
    }

    public function validateInfo($info, $action = 'insert', $id = 0)
    {
        $messages = [
            'email.required' => "Email field is required.",
            'email.unique' => "The provided email address is existed.",
            'role_id.required' => "role_id is required.",
            'role_id.exists' => "role_id is not existed.",
        ];

        if ($action === 'insert') {
            $rules = [
                'name' => 'required',
                'email' => 'required|unique:users',
                'phone_1' => 'required',
                'password' => 'required|min:8',
                'role_id' => 'required|exists:roles,id',
            ];
        } elseif ($action === 'update' && $id > 0) {
            $rules = [
                'email' => 'unique:users,email,' . $id,
                'role_id' => 'exists:roles,id',
                'password' => 'min:8',
            ];
        }

        $validator = Validator::make($info, $rules, $messages);

        return $validator;
    }

    public function transform($user)
    {
        if (isset($user->avatar_id))
            $avatar = UploadFile::find($user->avatar_id);

        if ($user instanceof User) {
            return [
                'id' => $user->id,
                'avatar' => isset($avatar) ? $avatar->path : null,
                'name' => $user->name,
                'email' => $user->email,
                'phone_1' => $user->phone_1,
                'phone_2' => $user->phone_2,
                'active' => $user->active,
                'role_id' => $user->role_id,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];
        }
    }

    public function logout()
    {
        $user = UserHelper::getAuthenticatedUser();
        $user->update([
            'earliest_valid' => time(),
        ]);
    }

    public function getTotalNumberOfUsers()
    {
        $totalNumberOfUsers = User::count();
        return $totalNumberOfUsers;
    }

    public function delete($userId)
    {
        return User::destroy($userId);
    }
}
