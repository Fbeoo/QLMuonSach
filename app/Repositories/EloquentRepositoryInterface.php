<?php
namespace App\Repositories;
use Illuminate\Database\Eloquent\Model;

interface EloquentRepositoryInterface {
    public function getALl();
    public function find($id);
    public function add(Model $model);
    public function update (Model $model);
    public function delete ($id);
}
