<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Category;
use App\Tests\Base\CategoryTest as BaseCategoryTest;

class CategoryTest extends BaseCategoryTest
{
    protected $apiUrl = '/categories';

    public function testGettingListOfCategories(): void
    {
        $token = $this->getAuthToken();

        $result = $this->request(
            'GET',
            $this->apiUrl,
            [],
            $token
        );

        $this->assertEquals(200, $result['statusCode']);
        $this->assertArrayHasKey('data', $result['content']);

        $data = $result['content']['data'] ?? null;
        
        if (count($data) > 0) {
            $category = $data[0];
            $this->assertCategory($category);
        }
    }

    public function testGettingCategoryById(): void
    {
        $token = $this->getAuthToken();
        $selectedCategoryId = $this->getFirstCategoryId($token);

        $result = $this->request(
            'GET',
            "{$this->apiUrl}/$selectedCategoryId",
            [],
            $token
        );

        $this->assertEquals(200, $result['statusCode']);
        $this->assertArrayHasKey('data', $result['content']);

        $category = $result['content']['data'] ?? null;

        $this->assertCategory($category);
    }

    public function testAddingCategory(): void
    {
        $token = $this->getAuthToken();
        $categoryName = 'Category ' . time();

        $result = $this->request(
            'POST',
            $this->apiUrl,
            ['name' => $categoryName],
            $token
        );

        $this->assertEquals(201, $result['statusCode']);
        $this->assertArrayHasKey('data', $result['content']);

        $category = $result['content']['data'] ?? null;

        $this->assertCategory($category, $categoryName);
    }

    public function testEditingCategory(): void
    {
        $token = $this->getAuthToken();
        $selectedCategoryId = $this->getFirstCategoryId($token);

        $categoryName = 'Category ' . time();
        $result = $this->request(
            'PUT',
            "{$this->apiUrl}/$selectedCategoryId",
            ['name' => $categoryName],
            $token
        );

        $this->assertEquals(200, $result['statusCode']);
        $this->assertArrayHasKey('data', $result['content']);

        $category = $result['content']['data'] ?? null;

        $this->assertCategory($category, $categoryName);
    }

    public function testDeletingCategory(): void
    {
        $token = $this->getAuthToken();
        $categoryName = 'Category ' . time();

        $result = $this->request(
            'POST',
            $this->apiUrl,
            ['name' => $categoryName],
            $token
        );

        $category = $result['content']['data'] ?? null;
        
        if (isset($category) === true) {
            $result = $this->request(
                'DELETE',
                "{$this->apiUrl}/{$category['id']}",
                [],
                $token
            );

            $this->assertEquals(204, $result['statusCode']);
        }
    }
}
