<?php
namespace App\Repositories\Eloquent;


use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class BaseRepository implements EloquentRepositoryInterface {
    /**
     * @var Model
     */
    protected $model;

    /**
     * @param $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|mixed
     * @throws \Exception
     */
    public function getALl()
    {
        try {
            return $this->model->all();
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function find($id)
    {
        try {
            return $this->model->find($id);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /**
     * @param Model $model
     * @return mixed
     * @throws \Exception
     */
    public function add(Model $model)
    {
        try {
            $createRecord = $this->model->create($model->getAttributes());
            return $createRecord;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /**
     * @param Model $model
     * @return true
     * @throws \Exception
     */
    public function update(Model $model)
    {
        try {
            $model->update($model->getAttributes());
            return true;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /**
     * @param $id
     * @return true
     * @throws \Exception
     */
    public function delete($id)
    {
        try {
            $record = $this->model->findorFail($id);
            $record->delete();
            $this->model->save();
            return true;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}
