<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Http\Request;

/**
 *
 */
class CategoryController extends Controller
{
    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @param $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return mixed
     */
    public function getCategoryParent() {
        try {
            $categoryParent = $this->categoryRepository->getCategoryParent();
            return $categoryParent;
        }catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    /**
     * @param $categoryParentId
     * @return mixed
     */
    public function getCategoryChildren($categoryParentId) {
        try {
            $categoryChildren = $this->categoryRepository->getCategoryChildByCategoryParentId($categoryParentId);
            if (!$categoryChildren) {
                return response()->json(['error'=>'Không tìm thấy thể loại']);
            }
            return $categoryChildren;
        }catch (\Exception $e) {
            return response()->json(['error'=>$e]);
        }
    }
}
