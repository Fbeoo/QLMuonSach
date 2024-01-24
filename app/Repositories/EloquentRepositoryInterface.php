<?php
namespace App\Repositories;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
interface EloquentRepositoryInterface {
    /**
     * @return mixed
     */
    public function getALl();

    /**
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * @param Model $model
     * @return mixed
     */
    public function add(Model $model);

    /**
     * @param Model $model
     * @return mixed
     */
    public function update (Model $model);

    /**
     * @param $id
     * @return mixed
     */
    public function delete ($id);
}
