<?php
namespace App\Repositories\Eloquent;


use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements EloquentRepositoryInterface {
    protected $model;

    /**
     * @param $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getALl()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function add(Model $model)
    {
        $this->model->create($model->getAttributes());
        return true;
    }

    public function update(Model $model)
    {
        $model->update($model->getAttributes());
        return true;
    }

    public function delete($id)
    {
        $record = $this->model->findorFail($id);
        $record->delete();
        $this->model->save();
        return true;
    }
}
