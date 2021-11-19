<?php

namespace Lifeboat\Tests;

use Lifeboat\Client;
use Lifeboat\Exceptions\InvalidArgumentException;

class ClientTest extends Connector {

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

        $this->expectException(InvalidArgumentException::class);
        new Client('', '');
    }

}
