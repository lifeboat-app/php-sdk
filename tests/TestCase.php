<?php

namespace Lifeboat\Tests;

use Lifeboat\Client;

/**
 * Class TestCase
 * @package Lifeboat\Tests
 */
class TestCase extends \PHPUnit\Framework\TestCase {

    private $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = $this->createMock(Client::class);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function getMockClient(): Client
    {
        return $this->client;
    }
}
