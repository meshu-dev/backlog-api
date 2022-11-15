<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Category;
use App\Tests\Base\CategoryTest as BaseCategoryTest;

class CategoryTest extends BaseCategoryTest
{
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

    public function testGettingListOfCategoriesWithNoToken(): void
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

    public function testGettingCategoryByIdWithNoToken(): void
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

        $this->assertInvalidId($statusCode, $data);
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

    public function testAddingCategoryWithNoToken(): void
    {
        $result = $this->request(
            'POST',
            $this->apiUrl,
            ['name' => $this->getTestCategoryName()]
        );

        $this->assertEquals(401, $result['statusCode']);
        $this->assertUnauthenticated($result['content']);
    }

    public function testAddingCategoryWithDuplicateName(): void
    {
        $token = $this->getAuthToken();
        $category = $this->getFirstCategory($token);

        $result = $this->request(
            'POST',
            $this->apiUrl,
            ['name' => $category['name']],
            $token
        );

        $data = $result['content'] ?? null;
        
        $this->assertDuplicateName(
            $result['statusCode'],
            $data
        );
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

    public function testEditingCategoryWithNoToken(): void
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

    public function testDeletingCategoryWithNoToken(): void
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

    public function testDeletingCategoryByInvalidId(): void
    {
        $token = $this->getAuthToken();
        $invalidId = $this->getInvalidId();

        $result = $this->request(
            'DELETE',
            "{$this->apiUrl}/$invalidId",
            [],
            $token
        );

        $statusCode = $result['statusCode'];
        $data = $result['content'];

        $this->assertInvalidId($statusCode, $data);
    }
}
