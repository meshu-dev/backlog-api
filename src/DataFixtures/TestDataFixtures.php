<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;
use App\Entity\Item;

class TestDataFixtures extends BaseFixture implements FixtureGroupInterface
{
    public function loadData()
    {
        $totalCategories = 5;
        $totalItems = 10;

        $this->createMany(Category::class, $totalCategories, function(Category $category, $count) {
            $categoryNo = $count + 1;
            $category->setName("Test Category $categoryNo");

            $this->manager->persist($category);
        });

        $this->manager->flush();

        $this->createMany(Item::class, $totalItems, function(Item $item, $count) use ($totalCategories) {
            $categoryNo = random_int(0, ($totalCategories - 1));
            $category = $this->getReference("App\Entity\Category_$categoryNo");

            $itemNo = $count + 1;

            $item->setCategory($category);
            $item->setName("Test Item $itemNo");

            $this->manager->persist($item);
        });

        $this->manager->flush();
    }

    public static function getGroups(): array
    {
        return ['test'];
    }
}
