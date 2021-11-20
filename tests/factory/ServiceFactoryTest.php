<?php

namespace Lifeboat\Tests\Factory;

use Lifeboat\Factory\ClassMap;
use Lifeboat\Factory\ServiceFactory;
use Lifeboat\Tests\TestCase;

class ServiceFactoryTest extends TestCase {

    /**
     * @test
     * @covers \Lifeboat\Factory\ServiceFactory::inst
     */
    public function testFactory()
    {
        foreach (ClassMap::SERVICES as $name => $class) {
            $this->assertInstanceOf($class, ServiceFactory::inst($this->getMockClient(), $name));
            $this->assertInstanceOf($class, ServiceFactory::inst($this->getMockClient(), strtoupper($name)));
        }

        $this->assertEquals(null, ServiceFactory::inst($this->getMockClient(), 'does_not_exist'));
    }
}
