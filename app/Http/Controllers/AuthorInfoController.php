<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AuthorInfo;
use App\Repositories\AuthorInfoRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
                'authorName' => 'required',
                'authorImage' => 'required|mimes:jpeg,png,jpg|max:4096'
            ],[
                'authorName.required' => @trans('message.authorNameValidateRequired'),
                'authorName.alpha' => @trans('message.authorNameValidateAlpha')
            ]);
            if ($validation->fails()) {
                return response()->json(['errorValidate'=>$validation->errors()]);
            }
            $path = $request->file('authorImage')->store('public/img');
            $authorInfo->author_name = $request->input('authorName');
            $authorInfo->author_image = Str::after($path,'/');
            $this->authorInfoRepository->add($authorInfo);
            return response()->json(['success','Thêm tác giả thành công']);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }

    public function showAuthorInAllAuthorPage() {
        try {
            $authors = $this->authorInfoRepository->getAuthorForAllAuthorPage();
            return view('allAuthor',['authors'=>$authors]);
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}
