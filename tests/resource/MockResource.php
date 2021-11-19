<?php

namespace Lifeboat\Tests\Resource;

use Lifeboat\Client;
use Lifeboat\Resource\ObjectResource;
use Lifeboat\Services\ApiService;
use Lifeboat\Tests\Services\MockService;

/**
 * Class MockResource
 * @package Lifeboat\Tests
 *
 * @property bool $bool_test
 * @property int $int_test
 * @property float $float_test
 * @property \DateTime $date_test
 */
class MockResource extends ObjectResource {

    protected static array $casting = [
        'bool_test'     => 'boolval',
        'int_test'      => 'intval',
        'float_test'    => 'floatval',
        'date_test'     => 'lifeboat_date_formatter'
    ];

    public function getService(): ApiService
    {
        return new MockService(new Client('mock', 'mock'));
    }

}
