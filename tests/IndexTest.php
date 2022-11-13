<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexTest extends ApiTest
{
    public function testIndexGetsTimestamp(): void
    {
        $this->client->request('GET', '/');
        $response = $this->client->getResponse();

        $content = $response->getContent();
        $content = json_decode($content, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('msg', $content);
        $this->assertArrayHasKey('time', $content);
        $this->assertEquals($content['msg'], 'Server is running OK');
    }
}
