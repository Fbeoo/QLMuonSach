<?php
namespace App\Repositories;
interface CommentBookRepositoryInterface extends EloquentRepositoryInterface {
    public function getCommentOfBook($bookId);
}
