<?php

namespace Lifeboat\Tests\Factory;

use Lifeboat\Client;
use Lifeboat\Factory\ClassMap;
use Lifeboat\Factory\ServiceFactory;
use PHPUnit\Framework\TestCase;

class ServiceFactoryTest extends TestCase {

    /**
     * @test
     * @covers \Lifeboat\Factory\ServiceFactory::inst
     */
    public function testFactory()
    {
        $mock = new Client('mock', 'mock');

        foreach (ClassMap::SERVICES as $name => $class) {
            $this->assertInstanceOf($class, ServiceFactory::inst($mock, $name));
            $this->assertInstanceOf($class, ServiceFactory::inst($mock, strtoupper($name)));
        }

        $this->assertEquals(null, ServiceFactory::inst($mock, 'does_not_exist'));
    }
}
