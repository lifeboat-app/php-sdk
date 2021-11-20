<?php

namespace Lifeboat\Tests\Models;

use Lifeboat\Factory\ClassMap;
use Lifeboat\Models\Collection;
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

    /**
     * @test
     * @covers \Lifeboat\Models\Model::exists
     * @covers \Lifeboat\Models\Model::toArray
     * @covers \Lifeboat\Models\Model::getIterator
     * @covers \Lifeboat\Models\Model::__set
     * @covers \Lifeboat\Models\Model::__get
     * @covers \Lifeboat\Models\Model::offsetGet
     * @covers \Lifeboat\Models\Model::offsetSet
     * @covers \Lifeboat\Models\Model::offsetExists
     * @covers \Lifeboat\Models\Model::count
     * @covers \Lifeboat\Models\Model::keys
     * @covers \Lifeboat\Models\Model::values
     */
    public function test_model_base_functions()
    {
        /** @var Model $class */
        foreach (ClassMap::MODELS as $class) {
            /** @var Model $mock */
            $mock = new $class($this->getMockClient(), ['ID' => 0]);
            $this->assertFalse($mock->exists());
            $mock->test = 'x';
            $this->assertEquals('x', $mock->test);
            $mock->offsetSet('test2', 'y');
            $this->assertEquals(['ID' => 0, 'test' => 'x', 'test2' => 'y'], $mock->toArray());
            $this->assertEquals('x', $mock->offsetGet('test'));
            $this->assertTrue($mock->offsetExists('test'));
            $mock->offsetUnset('test2');
            $this->assertEquals(['ID' => 0, 'test' => 'x'], $mock->toArray());
            $this->assertEquals(2, $mock->count());
            $this->assertEquals(['ID', 'test'], $mock->keys());
            $this->assertEquals([0, 'x'], $mock->values());

            /** @var Model $exists */
            $exists = new $class($this->getMockClient(), ['ID' => 1]);
            $this->assertTrue($exists->exists());
            $exists->test = 'y';
            $this->assertEquals(['ID' => 1, 'test' => 'y'], $exists->toArray());
            $this->assertEquals('y', $exists->offsetGet('test'));
        }
    }

    /**
     * @test
     * @covers \Lifeboat\Models\Collection::__construct
     */
    public function testCollection()
    {
        $data = ['ID' => 1, 'Rules' => '{"1":1}'];
        $collection = new Collection($this->getMockClient(), $data);
        $this->assertEquals([1 => 1], $collection->Rules);
    }
}
