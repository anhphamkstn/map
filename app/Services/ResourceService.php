<?php namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

interface ResourceService
{

    /**
     * Get resource list
     *
     * @param array $input
     * @return array
     * @internal param string $keyword
     * @internal param int $page
     * @internal param int $perPage
     */
    public function find($input);

    /**
     * Insert a new record
     *
     * @param $info
     * @return mixed
     */
    public function insert($info);

    /**
     * @param Model $instance
     * @param $info
     * @return mixed
     */
    public function update(Model $instance, $info);

    /**
     * Find a resource by id
     *
     * @param integer $id
     * @return Model object
     */
    public function findResource($id);

    /**
     * Validate information with rules before insert or update
     *
     * @param array $info
     * @param string $action
     * @param integer $resourceId
     * @return Validator
     */
    public function validateInfo($info, $action, $resourceId);

    /**
     * Define an return array
     *
     * @param Model $instance
     * @return array
     */
    public function transform(Model $instance);


    /**
     * Delete an instance of model
     *
     * @param Model $instance
     * @return boolean
     */
    public function delete(Model $instance);
}

