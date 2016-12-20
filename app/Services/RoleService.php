<?php

namespace App\Services;

use App\Helpers\Response;
use App\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class RoleService implements ResourceService
{

    protected $fillable = ['name', 'description'];

    public function find($input = [])
    {
        $roles = Role::all();

        $data = [];
        if (!$roles->isEmpty())
        {
            foreach ($roles as $role)
            {
                $data[] = $this->transform($role);
            }
        }

        return $data;
    }

    /**
     * Insert a new record
     *
     * @param $info
     * @return mixed
     */
    public function insert($info)
    {
        // TODO: Implement insert() method.
        $role = Role::create($info);

        return $this->transform($role);
    }

    /**
     * @param Model $instance
     * @param $info
     * @return mixed
     */
    public function update(Model $instance, $info)
    {
        // TODO: Implement update() method.

        foreach ($this->fillable as $attr)
        {
            if (isset($info[ $attr ]))
                $instance->$attr = $info[ $attr ];
        }

        $instance->save();

        return $this->transform($instance);
    }

    /**
     * Find a resource by id
     *
     * @param integer $id
     * @return Model object
     */
    public function findResource($id)
    {
        // TODO: Implement findResource() method.
        if (!$role = Role::findOrFail($id))
        {
            return Response::responseNotFound();
        }

        return $role;
    }

    public function validateInfo($info, $action = '', $resourceId = '')
    {
        // TODO: Implement validateInfo() method.
        $rules = [
            'name' => 'required',
        ];

        $validator = Validator::make($info, $rules);

        return $validator;
    }

    /**
     * Define an return array
     *
     * @param Model $role
     * @return array
     */
    public function transform(Model $role)
    {
        // TODO: Implement transform() method.
        if ($role instanceof Role)
        {
            return [
                'id'          => $role->id,
                'name'        => $role->name,
                'description' => $role->description,
                'created_at'  => date('Y-m-d H:i:s', strtotime($role->created_at)),
                'updated_at'  => date('Y-m-d H:i:s', strtotime($role->updated_at)),
            ];
        }
    }

    /**
     * Delete an instance of model
     *
     * @param Model $instance
     * @return boolean
     */
    public function delete(Model $instance)
    {
        // TODO: Implement delete() method.
    }

}