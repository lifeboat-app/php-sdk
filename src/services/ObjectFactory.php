<?php

namespace Lifeboat\Services;

use Lifeboat\Connector;
use Lifeboat\Models\Address;
use Lifeboat\Models\Model;

/**
 * Class ObjectFactory
 * @package Lifeboat\Services
 */
class ObjectFactory {

    const CLASS_MAP = [
        'address'   => Address::class
    ];

    /**
     * @param Connector $connector
     * @param string $model
     * @param array $data
     * @return Model|null
     */
    public static function create(Connector $connector, string $model, array $data = []): ?Model
    {
        $model = strtolower($model);
        if (!array_key_exists($model, self::CLASS_MAP)) return null;

        $cls = self::CLASS_MAP[$model];
        return new $cls($connector, $data);
    }

    /**
     * @param Connector $connector
     * @param array $data
     * @return Model|null
     */
    public static function make(Connector $connector, array $data): ?Model
    {
        $model = $data['model'] ?? '';
        unset($data['model']);

        return self::create($connector, $model, $data);
    }
}
