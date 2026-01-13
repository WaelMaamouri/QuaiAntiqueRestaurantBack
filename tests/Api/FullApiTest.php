<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FullApiTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    // -------------------- Nelmio --------------------
    public function testApiDoc(): void
    {
        $this->client->request('GET', '/api/doc');
        self::assertResponseIsSuccessful();

        $this->client->request('GET', '/api/doc.json');
        self::assertResponseIsSuccessful();
    }

    // -------------------- User --------------------
    public function testUserRegistrationAndLogin(): void
    {
        // Registration
        $this->client->request('POST', '/api/registration', [
            'json' => [
                'email' => 'test@example.com',
                'password' => '123456',
                'firstName' => 'Wael',
                'lastName' => 'Maamouri'
            ]
        ]);
        self::assertResponseIsSuccessful();
        $data = json_decode($this->client->getResponse()->getContent(), true);
        self::assertArrayHasKey('email', $data);

        // Login (si JWT ou API token)
        $this->client->request('POST', '/api/login', [
            'json' => [
                'email' => 'test@example.com',
                'password' => '123456'
            ]
        ]);
        self::assertResponseIsSuccessful();
        $loginData = json_decode($this->client->getResponse()->getContent(), true);
        self::assertArrayHasKey('token', $loginData ?? []);
    }

    // -------------------- Category --------------------
    public function testCategoryCrud(): void
    {
        // Create
        $this->client->request('POST', '/api/category', ['json' => ['name' => 'Entrée']]);
        self::assertResponseIsSuccessful();

        // Get
        $this->client->request('GET', '/api/category/1');
        self::assertResponseIsSuccessful();

        // Edit
        $this->client->request('PUT', '/api/category/1', ['json' => ['name' => 'Plat']]);
        self::assertResponseIsSuccessful();

        // Delete
        $this->client->request('DELETE', '/api/category/1');
        self::assertResponseIsSuccessful();
    }

    // -------------------- Food --------------------
    public function testFoodCrud(): void
    {
        // Create
        $this->client->request('POST', '/api/food', [
            'json' => ['name' => 'Salade César', 'price' => 12.50, 'category' => 1]
        ]);
        self::assertResponseIsSuccessful();

        // Get
        $this->client->request('GET', '/api/food/1');
        self::assertResponseIsSuccessful();

        // Edit
        $this->client->request('PUT', '/api/food/1', [
            'json' => ['name' => 'Salade Niçoise', 'price' => 14.50, 'category' => 1]
        ]);
        self::assertResponseIsSuccessful();

        // Delete
        $this->client->request('DELETE', '/api/food/1');
        self::assertResponseIsSuccessful();
    }

    // -------------------- Restaurant --------------------
    public function testRestaurantCrud(): void
    {
        // Create
        $this->client->request('POST', '/api/restaurant', [
            'json' => ['name' => 'Chez Wael', 'address' => '1 rue Test', 'email' => 'contact@chezwael.com']
        ]);
        self::assertResponseIsSuccessful();

        // Get
        $this->client->request('GET', '/api/restaurant/1');
        self::assertResponseIsSuccessful();

        // Edit
        $this->client->request('PUT', '/api/restaurant/1', [
            'json' => ['name' => 'Chez Wael Updated', 'address' => '2 rue Test', 'email' => 'contact@chezwael.com']
        ]);
        self::assertResponseIsSuccessful();

        // Delete
        $this->client->request('DELETE', '/api/restaurant/1');
        self::assertResponseIsSuccessful();
    }
}
