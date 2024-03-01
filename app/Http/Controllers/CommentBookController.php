<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CommentBook;
use App\Repositories\CommentBookRepositoryInterface;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class CommentBookController extends Controller
{
    protected $commentBookRepository;

    /**
     * @param $commentBookRepository
     */
    public function __construct(CommentBookRepositoryInterface $commentBookRepository)
    {
        $this->commentBookRepository = $commentBookRepository;
    }


    public function addComment(Request $request) {
        try {
            $validation = Validator::make($request->all(),[
                'content' => 'required'
            ],[
                'content.required' => 'Bình luận không được để trống'
            ]);

            if ($validation->fails()) {
                return \response()->json(['error' => $validation->errors()->getMessages()]);
            }

            $comment = new CommentBook();
            $comment->book_id = $request->input('bookId');
            $comment->user_id = Session::get('user')->id;
            $comment->content = $request->input('content');

            $this->commentBookRepository->add($comment);

            return \response()->json(['success' => 'Bình luận thành công']);

        }catch (\Exception $e) {
            return response()->json(['error' => $e]);
        }
    }

    public function getCommentOfBook($bookId) {
        try {
            $comments = $this->commentBookRepository->getCommentOfBook($bookId);
            return $comments;
        }catch (\Exception $e) {
            return \response()->json(['error' => $e]);
        }
    }
}
