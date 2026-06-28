<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HabitControllerTest extends WebTestCase {
    public function testShouldReturnTask() : void{
        $client = static::createClient();
        $client->request('GET', '/api/v1/habits/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $content = $client->getResponse()->getContent();

        $this->assertJson($content);
        $data = json_decode($content, true);

        $this->assertEquals(1,$data['id']);
        $this->assertEquals('Habit',$data['title']);
        $this->assertFalse($data['completed']);

    }
}
