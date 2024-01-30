<?php
namespace App\Repositories;
interface AuthorInfoRepositoryInterface extends EloquentRepositoryInterface {
    public function getAuthorForHomePage();

    public function getAuthorForAllAuthorPage();

}
