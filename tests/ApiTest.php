<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ApiTest extends WebTestCase
{
    protected $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    protected function request($type, $url, $params = [])
    {
        $params = [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => $params
        ];

        $this->client->request($type, $url, $params);
        $response = $this->client->getResponse();

        $data = $response->getContent();
        $data = json_decode($data, true);

        return [
            'statusCode' => $response->getStatusCode(),
            'content' => $data
        ];
    }
}
