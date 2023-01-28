<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use App\Repository\CategoryRepository;
use App\Entity\Category;

class CategoryFixture extends BaseFixture implements FixtureGroupInterface
{
    public function __construct(protected CategoryRepository $categoryRepository) { }

    public function loadData()
    {
        $this->addCategory('Movies');
        $this->addCategory('TV shows');
    }

    private function addCategory($name)
    {
        $category = new Category();
        $category->setName($name);

        $this->categoryRepository->save($category, true);
    }

    public static function getGroups(): array
    {
        return ['live'];
    }
}
