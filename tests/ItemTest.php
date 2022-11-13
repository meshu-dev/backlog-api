<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ItemTest extends ApiTest
{
    protected $apiUrl = '/items';
    protected $selectedCategoryId;
    protected $selectedItemId;

    protected function setUp(): void
    {
        $this->selectedCategoryId = 1;
        $this->selectedItemId = 1;
        parent::setUp();
    }

    public function testGettingListOfItems(): void
    {
        $result = $this->request('GET', $this->apiUrl);

        $this->assertEquals(200, $result['statusCode']);
        $this->assertArrayHasKey('data', $result['content']);

        $items = $result['content']['data'] ?? null;
        
        if (count($items) > 0) {
            $item = $items[0];
            $this->assertItem($item);
        }
    }

    public function testGettingItemById(): void
    {
        $result = $this->request('GET', "{$this->apiUrl}/{$this->selectedItemId}");

        $this->assertEquals(200, $result['statusCode']);
        $this->assertArrayHasKey('data', $result['content']);

        $item = $result['content']['data'] ?? null;

        $this->assertItem($item);
    }

    public function testAddingItem(): void
    {
        $params = [
            'category_id' => $this->selectedCategoryId,
            'name' => 'Item ' . time()
        ];

        $result = $this->request(
            'POST',
            $this->apiUrl,
            $params
        );

        $item = $result['content']['data'] ?? null;

        $this->assertItem($item, $params);
    }

    public function testEditingItem(): void
    {
        $params = [
            'category_id' => $this->selectedCategoryId,
            'name' => 'Item ' . time()
        ];

        $result = $this->request(
            'PUT',
            "{$this->apiUrl}/{$this->selectedItemId}",
            $params
        );

        $item = $result['content']['data'] ?? null;

        $this->assertItem($item, $params);
    }

    public function testDeletingItem(): void
    {
        $result = $this->request('GET', $this->apiUrl);

        $data = $result['content']['data'] ?? null;
        
        if (count($data) > 0) {
            $item = $data[array_key_last($data)];
            $result = $this->request('DELETE', "{$this->apiUrl}/{$item['id']}");

            $this->assertEquals(204, $result['statusCode']);
        }
    }

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
}
