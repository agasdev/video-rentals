<?php

namespace App\Test\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DvdControllerTest extends WebTestCase
{
    public function testCreateDvdInvalidData()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/dvd',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"title": ""}'
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testSuccess()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/dvd',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"title": "Example Dvd 9"}'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}