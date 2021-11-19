<?php

namespace Lifeboat\Tests\Models;

use Lifeboat\Client;
use Lifeboat\Factory\ClassMap;
use Lifeboat\Models\Model;
use Lifeboat\Services\ApiService;
use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase {

    /**
     * @test
     * @covers \Lifeboat\Models\Model::getService
     */
    public function testGetService()
    {
        $client = new Client('mock', 'mock');

        /** @var Model $class */
        foreach (ClassMap::MODELS as $class) {
            $mock = new $class($client, ['ID' => 0]);
            $this->assertInstanceOf(ApiService::class, $mock->getService());
        }
    }
}
