<?php

namespace Lifeboat\Tests\Resource;

use Lifeboat\Client;
use PHPUnit\Framework\TestCase;

class ResourceTest extends TestCase {

    private array $mock_resource_data = [
        'bool_test'     => '1',
        'int_test'      => '8',
        'float_test'    => '8.5',
        'date_test'     => '2021-10-05 00:13:25'
    ];

    /**
     * @test
     * @covers \Lifeboat\Resource\ApiResource::__construct
     * @covers \Lifeboat\Resource\ApiResource::setClient
     * @covers \Lifeboat\Resource\ApiResource::getClient
     */
    public function testConstruct()
    {
        $client = new Client('mock', 'mock');
        $mock   = new MockResource($client, $this->mock_resource_data);

        $this->assertEquals($client, $mock->getClient());

        // Test the casting
        $this->assertEquals(true, $mock->bool_test);
        $this->assertEquals(8, $mock->int_test);
        $this->assertEquals(8.5, $mock->float_test);

        $date = new \DateTime('2021-10-05 00:13:25 CET');
        $this->assertEquals($date->getTimestamp(), $mock->date_test->getTimestamp());

        $client_2 = new Client('mock2', 'mock2');
        $mock->setClient($client_2);
        $this->assertEquals($client_2, $mock->getClient());
    }

}
