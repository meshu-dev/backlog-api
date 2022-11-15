<?php

namespace App\Tests\Base;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ApiTest extends WebTestCase
{
    protected $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    protected function getAuthToken()
    {
        $params = [
            'email' => 'harmeshuppal@gmail.com',
            'password' => 'testtest'
        ];
        $this->client->request(
          'POST',
          'login',
          [],
          [],
          ['CONTENT_TYPE' => 'application/json'],
          json_encode([
            'username' =>'harmeshuppal@gmail.com',
            'password' =>'testtest'
          ])
        );

        $response = $this->client->getResponse();

        $data = $response->getContent();
        $data = json_decode($data, true);

        return $data['token'] ?? null;
    }

    protected function request($type, $url, $params = [], $token = null)
    {
        $headers = [
            'Content-Type' => 'application/json'
        ];

        if ($token !== null) {
            $headers['HTTP_AUTHORIZATION'] = "Bearer $token";
        }

        $params = json_encode($params);

        $this->client->request(
            $type,
            $url,
            [],
            [],
            $headers,
            $params
        );

        $response = $this->client->getResponse();

        $data = $response->getContent();
        $data = json_decode($data, true);

        return [
            'statusCode' => $response->getStatusCode(),
            'content' => $data
        ];
    }

    protected function assertUnauthenticated($data)
    {
        if ($data) {
            $this->assertArrayHasKey('code', $data);
            $this->assertArrayHasKey('message', $data);

            if (isset($data['code']) === true) {
                $this->assertEquals($data['code'], 401);
            }

            if (isset($data['message']) === true) {
                $this->assertEquals($data['message'], 'JWT Token not found');
            }
        }
    }

    /*
    protected function login2()
    {
        $params = [
            'email' => 'harmeshuppal@gmail.com',
            'password' => 'testtest'
        ];
        $result = $this->request('POST', 'login_check3', $params);

        dd($result);
    }

    protected function getAuthToken2()
    {
        $params = [
            'email' => 'harmeshuppal@gmail.com',
            'password' => 'testtest'
        ];

        $result = $this->request('POST', 'login', $params);

        return $result['token'] ?? null;
    } */
}
