<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PingControllerTest extends WebTestCase
{
    public function testPing(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/ping');

        $this->assertResponseIsSuccessful();

        $response = $client->getResponse();
        $content = $response->getContent();

        $this->assertJson($content);

        $data = json_decode($content, true);

        $this->assertSame([
            'ping' => true
        ], $data);

    }
}
