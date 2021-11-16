<?php

namespace Lifeboat\Factory;

use Lifeboat\Connector;
use Lifeboat\Services\ApiService;

/**
 * Class ServiceFactory
 * @package Lifeboat\Services
 */
class ServiceFactory {

    const CLASS_MAP = [
        'orders'    => \Lifeboat\Services\Orders::class
    ];

    /**
     * @param Connector $connector
     * @param string $service
     * @return ApiService|null
     */
    public static function inst(Connector $connector, string $service): ?ApiService
    {
        $service = strtolower($service);
        if (!array_key_exists($service, self::CLASS_MAP)) return null;

        $cls = self::CLASS_MAP[$service];
        return new $cls($connector);
    }
}
