<?php

namespace Lifeboat\Factory;

use Lifeboat\Connector;
use Lifeboat\Services\ApiService;

/**
 * Class ServiceFactory
 * @package Lifeboat\Services
 */
class ServiceFactory {

    /**
     * @param Connector $connector
     * @param string $service
     * @return ApiService|null
     */
    public static function inst(Connector $connector, string $service): ?ApiService
    {
        $service = strtolower($service);
        if (!array_key_exists($service, ClassMap::SERVICES)) return null;

        $cls = ClassMap::SERVICES[$service];
        return new $cls($connector);
    }
}
