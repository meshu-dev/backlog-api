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

    public function testStopGettingListOfCategoriesWithNoToken(): void
    {
        $result = $this->request('GET', $this->apiUrl);

        $this->assertEquals(401, $result['statusCode']);
        $this->assertUnauthenticated($result['content']);
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

    public function testStopGettingCategoryByIdWithNoToken(): void
    {
        $token = $this->getAuthToken();
        $selectedCategoryId = $this->getFirstCategoryId($token);

        $result = $this->request('GET', "{$this->apiUrl}/$selectedCategoryId");

        $this->assertEquals(401, $result['statusCode']);
        $this->assertUnauthenticated($result['content']);
    }

    public function testGettingCategoryByInvalidId(): void
    {
        $token = $this->getAuthToken();
        $invalidId = $this->getInvalidId();

        $result = $this->request(
            'GET',
            "{$this->apiUrl}/$invalidId",
            [],
            $token
        );

        $statusCode = $result['statusCode'];
        $data = $result['content'];

        $this->assertEquals(400, $statusCode);
        $this->assertArrayHasKey('errors', $data);
        $this->assertArrayHasKey('id', $data['errors']);
        $this->assertEquals(
            $data['errors']['id'],
            "Category with specified id doesn't exist"
        );
    }

    public function testAddingCategory(): void
    {
        $token = $this->getAuthToken();
        $categoryName = $this->getTestCategoryName();

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

    public function testStopAddingCategoryWithNoToken(): void
    {
        $result = $this->request(
            'POST',
            $this->apiUrl,
            ['name' => $this->getTestCategoryName()]
        );

        $this->assertEquals(401, $result['statusCode']);
        $this->assertUnauthenticated($result['content']);
    }

    public function testEditingCategory(): void
    {
        $token = $this->getAuthToken();
        $selectedCategoryId = $this->getFirstCategoryId($token);

        $categoryName = $this->getTestCategoryName();
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

    public function testStopEditingCategoryWithNoToken(): void
    {
        $token = $this->getAuthToken();
        $selectedCategoryId = $this->getFirstCategoryId($token);

        $result = $this->request(
            'PUT',
            "{$this->apiUrl}/$selectedCategoryId",
            ['name' => $this->getTestCategoryName()]
        );

        $this->assertEquals(401, $result['statusCode']);
        $this->assertUnauthenticated($result['content']);
    }

    public function testDeletingCategory(): void
    {
        $token = $this->getAuthToken();
        $category = $this->addTestCategory($token);
        
        $result = $this->request(
            'DELETE',
            "{$this->apiUrl}/{$category['id']}",
            [],
            $token
        );

        $this->assertEquals(204, $result['statusCode']);
    }

    public function testStopDeletingCategoryWithNoToken(): void
    {
        $token = $this->getAuthToken();
        $category = $this->addTestCategory($token);

        $result = $this->request(
            'DELETE',
            "{$this->apiUrl}/{$category['id']}",
        );

        $this->assertEquals(401, $result['statusCode']);
        $this->assertUnauthenticated($result['content']);
    }
}