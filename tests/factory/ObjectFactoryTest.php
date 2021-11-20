<?php

namespace Lifeboat\Tests\Factory;

use Lifeboat\Exceptions\InvalidArgumentException;
use Lifeboat\Factory\ClassMap;
use Lifeboat\Factory\ObjectFactory;
use Lifeboat\Models\LifeboatModel;
use Lifeboat\Models\Order;
use Lifeboat\Tests\TestCase;

class ObjectFactoryTest extends TestCase {

    /**
     * @test
     * @covers \Lifeboat\Factory\ObjectFactory::create
     * @covers \Lifeboat\Factory\ObjectFactory::make
     * @covers \Lifeboat\Models\LifeboatModel::__construct
     */
    public function testFactory()
    {
        $mock_data  = ['ID' => 0];

        foreach (ClassMap::MODELS as $name => $class) {
            $this->assertInstanceOf($class, ObjectFactory::create($this->getMockClient(), $name, $mock_data));
            $this->assertInstanceOf($class, ObjectFactory::create($this->getMockClient(), strtoupper($name), $mock_data));
        }

        // Test the make function
        $this->assertInstanceOf(Order::class, ObjectFactory::make($this->getMockClient(), ['model' => 'order']));

        // Default if model is invalid
        $this->assertInstanceOf(LifeboatModel::class, ObjectFactory::create($this->getMockClient(), 'does_not_exist', $mock_data));
        $this->assertInstanceOf(LifeboatModel::class, ObjectFactory::make($this->getMockClient(), ['model' => 'does_not_exist']));


        try {
            ObjectFactory::create($this->getMockClient(), 'does_not_exist');
            $this->fail('ObjectFactory::create should have thrown error for a model class that does not exist');
        } catch (InvalidArgumentException $e) {}
    }
}
