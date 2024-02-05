<?php
namespace App\Repositories;
interface UserRepositoryInterface extends EloquentRepositoryInterface {
    public function getAllUser();
}
