<?php

namespace Lifeboat\Tests;

use Lifeboat\Client;
use Lifeboat\Exceptions\InvalidArgumentException;

class ClientTest extends Connector {

    /**
     * @test
     * @covers \Lifeboat\Client::__construct
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(Client::class, new Client('mock', 'mock'));
        $this->expectException(InvalidArgumentException::class);
        new Client('', '');
    }

}
