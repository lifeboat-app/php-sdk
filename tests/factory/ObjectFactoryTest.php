<?php

namespace Lifeboat\Tests\Factory;

use Lifeboat\Client;
use Lifeboat\Exceptions\InvalidArgumentException;
use Lifeboat\Factory\ClassMap;
use Lifeboat\Factory\ObjectFactory;
use Lifeboat\Factory\ServiceFactory;
use Lifeboat\Models\LifeboatModel;
use Lifeboat\Models\Order;
use PHPUnit\Framework\TestCase;

class ObjectFactoryTest extends TestCase {

    /**
     * @test
     * @covers \Lifeboat\Factory\ObjectFactory::create
     * @covers \Lifeboat\Factory\ObjectFactory::make
     * @covers \Lifeboat\Models\LifeboatModel::__construct
     */
    public function testFactory()
    {
        $mock       = new Client('mock', 'mock');
        $mock_data  = ['ID' => 0];

        foreach (ClassMap::MODELS as $name => $class) {
            $this->assertInstanceOf($class, ObjectFactory::create($mock, $name, $mock_data));
            $this->assertInstanceOf($class, ObjectFactory::create($mock, strtoupper($name), $mock_data));
        }

        // Test the make function
        $this->assertInstanceOf(Order::class, ObjectFactory::make($mock, ['model' => 'order']));

        // Default if model is invalid
        $this->assertInstanceOf(LifeboatModel::class, ObjectFactory::create($mock, 'does_not_exist', $mock_data));
        $this->assertInstanceOf(LifeboatModel::class, ObjectFactory::make($mock, ['model' => 'does_not_exist']));

        // Expect error
        $this->expectException(InvalidArgumentException::class);
        ObjectFactory::create($mock, 'does_not_exist');
    }
}
