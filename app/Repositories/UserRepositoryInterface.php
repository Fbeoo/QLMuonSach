<?php
namespace App\Repositories;
interface UserRepositoryInterface extends EloquentRepositoryInterface {
    public function getAllUser();

    public function filterUser($arrFilter);

    public function countUser();

    public function getUserByToken($token);

    public function getUserByMail($mail);

    public function getAllRequestRentBookExpirationOfUser();
}
