<?php

namespace Lifeboat\Tests;

use Lifeboat\App;

/**
 * Class TestCase
 * @package Lifeboat\Tests
 */
class TestCase extends \PHPUnit\Framework\TestCase {

    private $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = $this->createMock(App::class);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function getMockClient(): App
    {
        return $this->client;
    }
}
