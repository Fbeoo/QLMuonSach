<?php
namespace App\Repositories;
interface AuthorBookRepositoryInterface extends EloquentRepositoryInterface {
    public function getAuthorBook ($bookId);
}
