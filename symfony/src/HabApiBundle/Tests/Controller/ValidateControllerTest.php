<?php

namespace HabApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ValidateControllerTest extends WebTestCase
{
    public function testValidateNoHotmail()
    {
        $client = static::createClient();
        $client->request('GET', '/validate', ['email' => 'asdf@gmail.com']);
        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
    }

    public function testValidateHotmail()
    {
        $client = static::createClient();
        $client->request('GET', '/validate', ['email' => 'asdf@hotmail.com']);
        $this->assertEquals(
            403,
            $client->getResponse()->getStatusCode()
        );
    }
}
