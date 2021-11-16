<?php

namespace Lifeboat\Factory;

use Lifeboat\Connector;
use Lifeboat\Models\Model;
use Lifeboat\Resource\ObjectResource;

/**
 * Class ObjectFactory
 * @package Lifeboat\Services
 */
class ObjectFactory {

    /**
     * @param Connector $connector
     * @param string $model
     * @param array $data
     * @return Model|null
     */
    public static function create(Connector $connector, string $model, array $data = []): ?Model
    {
        $model = strtolower($model);
        if (!array_key_exists($model, ClassMap::MODELS)) return null;

        $cls = ClassMap::MODELS[$model];
        return new $cls($connector, $data);
    }

    /**
     * @param Connector $connector
     * @param array $data
     * @return ObjectResource
     */
    public static function make(Connector $connector, array $data): ?ObjectResource
    {
        $model = $data['model'] ?? '';
        if (!$model) return new ObjectResource($connector, $data);
        return self::create($connector, $model, $data);
    }
}
