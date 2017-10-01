<?php

namespace HabApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
    public function testListCategories()
    {
        $client = static::createClient();
        $client->request('GET', '/categories');
        $categories = json_decode($client->getResponse()->getContent(), true);
        $this->assertNotEmpty($categories);
    }

}
