<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository extends ModelRepository
{
    public function __construct(Category $category)
    {
        parent::__construct($category);
    }
}
