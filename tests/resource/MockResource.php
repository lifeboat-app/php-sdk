<?php

namespace Lifeboat\Tests\Resource;

use Lifeboat\App;
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
 * @property null $date_test_neg
 */
class MockResource extends ObjectResource {

    protected static $casting = [
        'bool_test'     => 'boolval',
        'int_test'      => 'intval',
        'float_test'    => 'floatval',
        'date_test'     => 'lifeboat_date_formatter',
        'date_test_neg' => 'lifeboat_date_formatter',
    ];

    public function getService(): ApiService
    {
        return new MockService(new App('mock', 'mock'));
    }

}
