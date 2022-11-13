<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Category;

class CategoryTest extends ApiTest
{
    protected $apiUrl = '/categories';
    protected $selectedId;

    protected function setUp(): void
    {
        $this->selectedId = 1;
        parent::setUp();
    }

    public function testGettingListOfCategories(): void
    {
        $result = $this->request('GET', $this->apiUrl);

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
        $result = $this->request('GET', "{$this->apiUrl}/{$this->selectedId}");

        $this->assertEquals(200, $result['statusCode']);
        $this->assertArrayHasKey('data', $result['content']);

        $category = $result['content']['data'] ?? null;

        $this->assertCategory($category);
    }

    public function testAddingCategory(): void
    {
        $categoryName = 'Category ' . time();
        $result = $this->request(
            'POST',
            $this->apiUrl,
            ['name' => $categoryName]
        );

        $this->assertEquals(201, $result['statusCode']);
        $this->assertArrayHasKey('data', $result['content']);

        $category = $result['content']['data'] ?? null;

        $this->assertCategory($category, $categoryName);
    }

    public function testEditingCategory(): void
    {
        $categoryName = 'Category ' . time();

        $result = $this->request('GET', "{$this->apiUrl}/{$this->selectedId}");
        $category = $result['content']['data'] ?? null;

        $categoryName = 'Category ' . time();
        $result = $this->request(
            'PUT',
            "{$this->apiUrl}/{$category['id']}",
            ['name' => $categoryName]
        );

        $this->assertEquals(200, $result['statusCode']);
        $this->assertArrayHasKey('data', $result['content']);

        $category = $result['content']['data'] ?? null;

        $this->assertCategory($category, $categoryName);
    }

    public function testDeletingCategory(): void
    {
        $result = $this->request('GET', $this->apiUrl);

        $data = $result['content']['data'] ?? null;
        
        if (count($data) > 0) {
            $category = $data[array_key_last($data)];
            $result = $this->request('DELETE', "{$this->apiUrl}/{$category['id']}");

            $this->assertEquals(204, $result['statusCode']);
        }
    }

    protected function assertCategory($category, $name = null)
    {
        if ($category) {
            $this->assertArrayHasKey('id', $category);
            $this->assertArrayHasKey('name', $category);

            if ($name) {
                $this->assertEquals($name, $category['name']);
            }
        }  
    }
}
