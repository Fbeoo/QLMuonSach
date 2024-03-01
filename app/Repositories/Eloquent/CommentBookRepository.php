<?php
namespace App\Repositories\Eloquent;
use App\Repositories\CommentBookRepositoryInterface;

class CommentBookRepository extends BaseRepository implements CommentBookRepositoryInterface {

    public function getCommentOfBook($bookId)
    {
        try {
            $comments = $this->model->where('book_id',$bookId)->with('user')->get();
            return $comments;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}
