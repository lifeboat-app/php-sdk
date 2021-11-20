<?php

namespace Lifeboat\Tests;

use Lifeboat\Client;
use Lifeboat\Exceptions\InvalidArgumentException;

class ClientTest extends TestCase {

    /**
     * @test
     * @covers \Lifeboat\Client::__construct
     * @covers \Lifeboat\Client::getAPIKey
     * @covers \Lifeboat\Client::getAPISecret
     */
    public function testConstruct()
    {
        $client = new Client('key', 'secret');
        $this->assertInstanceOf(Client::class, $client);

        $this->assertEquals('key', $client->getAPIKey());
        $this->assertEquals('secret', $client->getAPISecret());

        try {
            new Client('', '');
            $this->fail('Client::__construct should have thrown an error for invalid arguments');
        } catch (InvalidArgumentException $e) {
            // Error should be thrown
        }
    }

}
