<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\Base\ItemTest as BaseItemTest;

class ItemTest extends BaseItemTest
{
    protected $apiUrl = '/items';

    public function testGettingListOfItems(): void
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

        $items = $result['content']['data'] ?? null;
        
        if (count($items) > 0) {
            $item = $items[0];
            $this->assertItem($item);
        }
    }

    public function testStopGettingListOfItemsWithNoToken(): void
    {
        $result = $this->request('GET', $this->apiUrl);

        $this->assertEquals(401, $result['statusCode']);
        $this->assertUnauthenticated($result['content']);
    }

    public function testGettingItemById(): void
    {
        $token = $this->getAuthToken();
        $selectedItemId = $this->getFirstItemId($token);

        $result = $this->request(
            'GET',
            "{$this->apiUrl}/$selectedItemId",
            [],
            $token
        );

        $this->assertEquals(200, $result['statusCode']);
        $this->assertArrayHasKey('data', $result['content']);

        $item = $result['content']['data'] ?? null;

        $this->assertItem($item);
    }

    public function testStopGettingItemByIdWithNoToken(): void
    {
        $token = $this->getAuthToken();
        $selectedItemId = $this->getFirstItemId($token);

        $result = $this->request('GET', "{$this->apiUrl}/$selectedItemId");

        $this->assertEquals(401, $result['statusCode']);
        $this->assertUnauthenticated($result['content']);
    }

    public function testGettingItemByInvalidId(): void
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

    public function testAddingItem(): void
    {
        $token = $this->getAuthToken();
        $selectedCategoryId = $this->getFirstItemCategoryId($token);

        $params = [
            'category_id' => $selectedCategoryId,
            'name' => $this->getTestItemName()
        ];

        $result = $this->request(
            'POST',
            $this->apiUrl,
            $params,
            $token
        );

        $item = $result['content']['data'] ?? null;

        $this->assertItem($item, $params);
    }

    public function testStopAddingItemWithNoToken(): void
    {
        $token = $this->getAuthToken();
        $selectedCategoryId = $this->getFirstItemCategoryId($token);

        $params = [
            'category_id' => $selectedCategoryId,
            'name' => $this->getTestItemName()
        ];

        $result = $this->request(
            'POST',
            $this->apiUrl,
            $params
        );

        $this->assertEquals(401, $result['statusCode']);
        $this->assertUnauthenticated($result['content']);
    }

    public function testEditingItem(): void
    {
        $token = $this->getAuthToken();
        $firstItem = $this->getFirstItem($token);

        $params = [
            'category_id' => $firstItem['category']['id'],
            'name' => $this->getTestItemName()
        ];

        $result = $this->request(
            'PUT',
            "{$this->apiUrl}/{$firstItem['id']}",
            $params,
            $token
        );

        $item = $result['content']['data'] ?? null;

        $this->assertItem($item, $params);
    }

    public function testStopEditingItemWithNoToken(): void
    {
        $token = $this->getAuthToken();
        $firstItem = $this->getFirstItem($token);

        $params = [
            'category_id' => $firstItem['category']['id'],
            'name' => $this->getTestItemName()
        ];

        $result = $this->request(
            'PUT',
            "{$this->apiUrl}/{$firstItem['id']}",
            $params
        );

        $this->assertEquals(401, $result['statusCode']);
        $this->assertUnauthenticated($result['content']);
    }

    public function testDeletingItem(): void
    {
        $token = $this->getAuthToken();
        $item = $this->getLastItem($token);
        
        if (isset($item) === true) {
            $result = $this->request(
                'DELETE',
                "{$this->apiUrl}/{$item['id']}",
                [],
                $token
            );

            $this->assertEquals(204, $result['statusCode']);
        }
    }

    public function testStopDeletingItemWithNoToken(): void
    {
        $token = $this->getAuthToken();
        $item = $this->getLastItem($token);

        $result = $this->request(
            'DELETE',
            "{$this->apiUrl}/{$item['id']}"
        );

        $this->assertEquals(401, $result['statusCode']);
        $this->assertUnauthenticated($result['content']);
    }

    public function testDeletingItemByInvalidId(): void
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
