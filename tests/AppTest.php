<?php

namespace Lifeboat\Tests;

use Lifeboat\App;
use Lifeboat\Client;
use Lifeboat\Exceptions\InvalidArgumentException;
use Lifeboat\Utils\Utils;

class AppTest extends Connector {

    /**
     * @test
     * @covers \Lifeboat\App::__construct
     * @covers \Lifeboat\App::getAppID
     * @covers \Lifeboat\App::getAppSecret
     * @covers \Lifeboat\App::setAppID
     * @covers \Lifeboat\App::setAppSecret
     */
    public function testConstruct()
    {
        $app = new App('app_id', 'secret');
        $this->assertInstanceOf(App::class, $app);

        $this->assertEquals('app_id', $app->getAppID());
        $this->assertEquals('secret', $app->getAppSecret());

        $this->expectException(InvalidArgumentException::class);
        new App('', '');
    }

    /**
     * @test
     * @covers \Lifeboat\App::getAPIChallenge
     * @covers \Lifeboat\App::setAPIChallenge
     */
    public function testChallenge()
    {
        $app = new App('mock', 'mock');

        $challenge = '123';
        $app->setAPIChallenge($challenge);
        $this->assertEquals($challenge, $app->getAPIChallenge());

        $app->setAPIChallenge('');
        $this->assertEquals(128, strlen($app->getAPIChallenge()));
    }

    /**
     * @test
     * @covers \Lifeboat\App::getAuthURL
     */
    public function testAuthURL()
    {
        $app    = new App('id', 'secret', 'http://test.domain');
        $url    = $app->getAuthURL('process.php', 'error.php', 'challenge');
        $expect = 'http://test.domain/oauth/code?app_id=id&process_url=' .
            urlencode('process.php') .
            '&error_url=' . urlencode('error.php') .
            '&challenge=' . Utils::pack('challenge');

        $this->assertEquals($expect, $url);
    }
}
