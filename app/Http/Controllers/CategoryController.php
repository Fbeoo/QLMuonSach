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
        return $this->categoryRepository->getCategoryParent();
    }

    /**
     * @param $categoryParentId
     * @return mixed
     */
    public function getCategoryChildren($categoryParentId) {
        return $this->categoryRepository->getCategoryChildByCategoryParentId($categoryParentId);
    }
}
