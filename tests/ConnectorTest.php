<?php

namespace Lifeboat\Tests;

use Lifeboat\App;
use Lifeboat\Client;
use Lifeboat\Exceptions\BadMethodException;
use Lifeboat\Exceptions\InvalidArgumentException;
use Lifeboat\Factory\ClassMap;
use Lifeboat\Utils\Utils;

class ConnectorTest extends TestCase {

    /**
     * @test
     * @covers \Lifeboat\Connector::__get
     */
    public function test_service_factory()
    {
        $client = new Client('mock', 'mock');

        foreach (ClassMap::SERVICES as $service => $class) {
            $this->assertInstanceOf($class, $client->$service);
        }

        try {
            $client->not_existant;
            $this->fail('Connector::__get should have thrown an error for a service that does not exist');
        } catch (BadMethodException $e) {
            // Error should be thrown
        }
    }

    /**
     * @test
     *
     * @covers \Lifeboat\Connector::getSiteKey
     * @covers \Lifeboat\Connector::getHost
     * @covers \Lifeboat\Connector::setActiveSite
     */
    public function test_active_site()
    {
        $client = new Client('mock', 'mock');
        $client->setActiveSite('test.example', '123');

        $this->assertEquals('test.example', $client->getHost());
        $this->assertEquals('123', $client->getSiteKey());
    }
}
