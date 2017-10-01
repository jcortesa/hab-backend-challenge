<?php

namespace HabApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BudgetControllerTest extends WebTestCase
{
    public function testCreateBudget()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/budgets',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'description' => 'una descripción',
                'category' => 91,
                'title' => 'un título',
                'email' => 'gary@gmail.com',
                'phone' => '+34666123321',
                'address' => 'Plaça de Cort, 14, 07001, Palma, Illes Balears',
            ])
        );

        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
    }

    public function testUpdateBudget()
    {
        $client = static::createClient();
        $client->request(
            'PATCH',
            '/budgets/31',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'title' => 'un título nuevo',
            ])
        );

        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
    }

    public function testPublishBudget()
    {
        $client = static::createClient();
        $client->request(
            'PATCH',
            '/budgets/31/publish',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([])
        );

        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
    }

    public function testListBudget()
    {
        $client = static::createClient();
        $client->request('GET', '/budgets');
        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
    }

    public function testFilterListBudget()
    {
        $client = static::createClient();
        $client->request('GET', '/budgets', ['email' => 'asdf@asd.com']);
        $this->assertCount(
            3,
            json_decode($client->getResponse()->getContent(), true)
        );
    }

}
