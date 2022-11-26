<?php

namespace App\Factory;

use App\Entity\Category;
use App\Entity\Item;

class ItemFactory
{
    public function make(
        Category $category,
        string $name,
        string $imageUrl
    ): Item {
        $item = new Item();
        $item->setCategory($category);
        $item->setName($name);
        $item->setImageUrl($imageUrl);

        return $item;
    }
}
