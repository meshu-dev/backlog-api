<?php

namespace App\Tests\Base;

use App\Tests\Base\ApiTest;

abstract class ItemTest extends ApiTest
{
    protected function assertItem($item, $params = [])
    {
        if ($item) {
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('name', $item);

            $this->assertArrayHasKey('category', $item);
            $this->assertArrayHasKey('id', $item['category']);
            $this->assertArrayHasKey('name', $item['category']);

            if (count($params) > 0) {
                $this->assertEquals($params['category_id'], $item['category']['id']);
                $this->assertEquals($params['name'], $item['name']);
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
            "Item with specified id doesn't exist"
        );
    }

    protected function getItems($token)
    {
        $result = $this->request(
            'GET',
            $this->apiUrl,
            [],
            $token
        );

        $items = $result['content']['data'] ?? null;

        return count($items) > 0 ? $items : null;
    }

    protected function getFirstItem($token)
    {
        $items = $this->getItems($token);
        
        if (count($items) > 0) {
            return $items[0];
        }
        return null;
    }

    protected function getLastItem($token)
    {
        $items = $this->getItems($token);
        
        if (count($items) > 0) {
            return $items[array_key_last($items)];;
        }
        return null;
    }

    protected function getFirstItemId($token)
    {
        $item = $this->getFirstItem($token);
        return $item['id'] ?? 0;
    }

    protected function getFirstItemCategoryId($token)
    {
        $item = $this->getFirstItem($token);
        return $item['category']['id'] ?? 0;
    }

    protected function getTestItemName()
    {
        return 'Category ' . time();
    }
}
