<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\Base\ApiTest;

class IndexTest extends ApiTest
{
    public function testIndexGetsTimestamp(): void
    {
        $result = $this->request('GET', '/');

        $statusCode = $result['statusCode'];
        $data = $result['content'];

        $this->assertEquals(200, $statusCode);
        $this->assertArrayHasKey('msg', $data);
        $this->assertArrayHasKey('time', $data);
        $this->assertEquals($data['msg'], 'Server is running OK');
    }
}
