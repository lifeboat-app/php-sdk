<?php

namespace Lifeboat\Tests\Models;

use Lifeboat\Factory\ClassMap;
use Lifeboat\Models\Model;
use Lifeboat\Services\ApiService;
use Lifeboat\Tests\TestCase;

class ModelTest extends TestCase {

    /**
     * @test
     * @covers \Lifeboat\Models\Model::getService
     */
    public function testGetService()
    {
        /** @var Model $class */
        foreach (ClassMap::MODELS as $class) {
            $mock = new $class($this->getMockClient(), ['ID' => 0]);
            $this->assertInstanceOf(Model::class, $mock);
            $this->assertInstanceOf(ApiService::class, $mock->getService());
        }
    }
}
