<?php

namespace App\Factory;

use App\Entity\Category;

class CategoryFactory
{
    public function make(
        string $name
    ): Category {
        $category = new Category();
        $category->setName($name);

        return $category;
    }
}
