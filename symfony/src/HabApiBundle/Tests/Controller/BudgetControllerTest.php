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
                'description' => '1435 una descripción',
                'category' => 91,
                'title' => '1435  un título',
                'email' => 'gary@1435gmail.com',
                'phone' => '1435 +34666123321',
                'address' => '1435 Plaça de Cort, 14, 07001, Palma, Illes Balears',
            ])
        );

        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
    }

    public function testUpdateBudget()
    {
        $client = static::createClient();
        $client->request('GET', '/budgets/107');
        $content = json_decode($client->getResponse()->getContent(), true);

        $client->request(
            'PATCH',
            '/budgets/107',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'title' => '107 un título nuevo',
            ])
        );

        switch ($content['status']) {
            case 'pendiente':
                $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
                break;
            default:
                $this->assertEquals(403, $client->getResponse()->getStatusCode(), 'response status is 403');
                break;
        }
    }

    public function testPublishBudget()
    {
        $client = static::createClient();
        $client->request('GET', '/budgets/116');
        $content = json_decode($client->getResponse()->getContent(), true);
        $client->request(
            'PATCH',
            '/budgets/116/publish',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([])
        );

        if (
            'pendiente' === $content['status'] &&
            !empty($content['title']) &&
            !empty($content['description'])
        ) {

            $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
        } else {
            $this->assertEquals(403, $client->getResponse()->getStatusCode(), 'response status is 403');
        }
    }

    public function testDiscardBudget()
    {
        $client = static::createClient();
        $client->request('GET', '/budgets/116');
        $content = json_decode($client->getResponse()->getContent(), true);
        $client->request(
            'PATCH',
            '/budgets/116/discard',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([])
        );

        switch ($content['status']) {
            case 'descartada':
                $this->assertEquals(403, $client->getResponse()->getStatusCode(), 'response status is 403');
                break;
            default:
                $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
                break;
        }
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
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(3, $content['_embedded']['budgets']);
    }

    public function testFilterBudgetByInexistentUser()
    {
        $client = static::createClient();
        $client->request('GET', '/budgets', ['email' => 'no@exists.com']);
        $this->assertTrue($client->getResponse()->isNotFound(), 'response status is 404');
    }

    public function testPaginatedList()
    {
        $client = static::createClient();
        $client->request('GET', '/budgets', ['limit' => '5']);
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(5, $content);
    }
}
