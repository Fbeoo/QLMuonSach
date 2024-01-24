<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AuthorInfo;
use App\Repositories\AuthorInfoRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 *
 */
class AuthorInfoController extends Controller
{
    /**
     * @var AuthorInfoRepositoryInterface
     */
    protected $authorInfoRepository;

    /**
     * @param $authorInfoRepository
     */
    public function __construct(AuthorInfoRepositoryInterface $authorInfoRepository)
    {
        $this->authorInfoRepository = $authorInfoRepository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function getAllAuthor() {
        try {
            $author = $this->authorInfoRepository->getALl();
            if (!$author) {
                return response()->json(['error'=>'Không có tác giả']);
            }
            return $author;
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addAuthor(Request $request) {
        try {
            $authorInfo = new AuthorInfo();
            $validation = Validator::make($request->all(),[
                'authorName' => 'required|alpha'
            ],[
                'authorName.required' => @trans('message.authorNameValidateRequired'),
                'authorName.alpha' => @trans('message.authorNameValidateAlpha')
            ]);
            if ($validation->fails()) {
                return response()->json(['errorValidate'=>$validation->errors()]);
            }
            $authorInfo->author_name = $request->input('authorName');
            $this->authorInfoRepository->add($authorInfo);
            return response()->json(['success','Thêm tác giả thành công']);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }

    }
}
