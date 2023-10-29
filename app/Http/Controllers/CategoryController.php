<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CategoryRepository;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryRepository $categoryRepository
    ) {
    }

    public function getAll(Request $request)
    {
        $params = $request->all();
        $rows = $this->categoryRepository->getAll($params);

        return CategoryResource::collection($rows);
    }
}
