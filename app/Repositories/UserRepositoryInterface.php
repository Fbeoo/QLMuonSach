<?php
namespace App\Repositories;
interface UserRepositoryInterface extends EloquentRepositoryInterface {
    public function getAllUser();

    public function filterUser($arrFilter);

    public function countUser();
}
