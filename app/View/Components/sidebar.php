<?php

namespace App\View\Components;

use App\Repositories\CategoryRepositoryInterface;
use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class sidebar extends Component
{
    protected $categoryRepository;
    public $category_parents;
    public $category_children;
    /**
     * Create a new component instance.
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->category_parents = $this->categoryRepository->getCategoryParent();
        $this->category_children = $this->categoryRepository->getCategoryChild();

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sidebar',['category_parent'=>$this->categoryRepository->getCategoryParent()]);
    }
}
