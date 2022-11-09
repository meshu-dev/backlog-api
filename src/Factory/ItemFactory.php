<?php

namespace App\Factory;

use App\Entity\Category;
use App\Entity\Item;

class ItemFactory
{
    public function make(
        Category $category,
        string $name
    ): Item {
        $item = new Item();
        $item->setCategory($category);
        $item->setName($name);

        return $item;
    }
}
