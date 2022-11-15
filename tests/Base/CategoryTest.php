<?php

namespace App\Tests\Base;

use App\Tests\Base\ApiTest;

abstract class CategoryTest extends ApiTest
{
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

    protected function assertInvalidId($statusCode, $data)
    {
        $this->assertEquals(400, $statusCode);
        $this->assertArrayHasKey('errors', $data);
        $this->assertArrayHasKey('id', $data['errors']);
        $this->assertEquals(
            $data['errors']['id'],
            "Category with specified id doesn't exist"
        );
    }

    protected function getCategories($token)
    {
        $result = $this->request(
            'GET',
            $this->apiUrl,
            [],
            $token
        );

        $categories = $result['content']['data'] ?? null;

        return count($categories) > 0 ? $categories : null;
    }

    protected function getFirstCategory($token)
    {
        $categories = $this->getCategories($token);
        
        if (count($categories) > 0) {
            return $categories[0];
        }
        return null;
    }

    protected function getLastCategory($token)
    {
        $categories = $this->getCategories($token);
        
        if (count($categories) > 0) {
            return $categories[array_key_last($categories)];;
        }
        return null;
    }

    protected function getFirstCategoryId($token)
    {
        $category = $this->getFirstCategory($token);
        return $category['id'] ?? 0;
    }

    protected function getTestCategoryName()
    {
        return 'Category ' . time();
    }

    protected function addTestCategory($token)
    {
        $result = $this->request(
            'POST',
            $this->apiUrl,
            ['name' => $this->getTestCategoryName()],
            $token
        );

        $category = $result['content']['data'] ?? null;
        return $category;
    }
}
