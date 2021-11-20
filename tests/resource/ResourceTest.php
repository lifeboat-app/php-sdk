<?php

namespace Lifeboat\Tests\Resource;

use Lifeboat\Client;
use Lifeboat\Tests\TestCase;

class ResourceTest extends TestCase {

    private array $mock_resource_data = [
        'bool_test'     => '1',
        'int_test'      => '8',
        'float_test'    => '8.5',
        'date_test'     => '2021-10-05 00:13:25',
        'date_test_neg' => '-'
    ];

    /**
     * @test
     * @covers \Lifeboat\Resource\ApiResource::__construct
     * @covers \Lifeboat\Resource\ApiResource::setClient
     * @covers \Lifeboat\Resource\ApiResource::getClient
     * @covers \lifeboat_date_formatter()
     */
    public function testConstruct()
    {
        $mock = new MockResource($this->getMockClient(), $this->mock_resource_data);

        $this->assertEquals($this->getMockClient(), $mock->getClient());

        // Test the casting
        $this->assertEquals(true, $mock->bool_test);
        $this->assertEquals(8, $mock->int_test);
        $this->assertEquals(8.5, $mock->float_test);
        $this->assertEquals(null, $mock->date_test_neg);

        $date = new \DateTime('2021-10-05 00:13:25 CET');
        $this->assertEquals($date->getTimestamp(), $mock->date_test->getTimestamp());

        $client_2 = $this->createMock(Client::class);
        $mock->setClient($client_2);
        $this->assertEquals($client_2, $mock->getClient());
    }

}
