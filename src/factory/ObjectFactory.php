<?php

namespace Lifeboat\Factory;

use Lifeboat\Connector;
use Lifeboat\Models\LifeboatModel;
use Lifeboat\Models\Model;

/**
 * Class ObjectFactory
 * @package Lifeboat\Services
 */
class ObjectFactory {

    /**
     * @param Connector $connector
     * @param string $model
     * @param array $data
     * @return Model
     */
    public static function create(Connector $connector, string $model, array $data = []): Model
    {
        $model = strtolower($model);
        if (!array_key_exists($model, ClassMap::MODELS)) return new LifeboatModel($connector, $data);

        $cls = ClassMap::MODELS[$model];
        return new $cls($connector, $data);
    }

    /**
     * @param Connector $connector
     * @param array $data
     * @return Model
     */
    public static function make(Connector $connector, array $data): Model
    {
        $model = $data['model'] ?? '';
        if (!$model) return new LifeboatModel($connector, $data);
        return self::create($connector, $model, $data);
    }
}
