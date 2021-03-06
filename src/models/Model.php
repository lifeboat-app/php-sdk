<?php

namespace Lifeboat\Models;

use Lifeboat\Connector;
use Lifeboat\Exceptions\InvalidArgumentException;
use Lifeboat\Factory\ClassMap;
use Lifeboat\Resource\ListResource;
use Lifeboat\Resource\ObjectResource;
use Lifeboat\Factory\ObjectFactory;
use Lifeboat\Services\ApiService;
use Lifeboat\Utils\ArrayLib;
use ErrorException;

/**
 * Class Model
 * @package Lifeboat\Models
 *
 * @property int $ID
 */
abstract class Model extends ObjectResource {

    public function __construct(Connector $client, array $_object_data = [])
    {
        if (!ArrayLib::is_associative($_object_data)) {
            throw new InvalidArgumentException("Model::__construct() expects an associative array");
        }

        parent::__construct($client, $_object_data);

        // Mutate objects if needs be
        foreach ($this->toArray() as $field => $value) {
            if (is_array($value)) {
                if (ArrayLib::is_associative($value)) {
                    // Model or Object
                    if (!array_key_exists('field_name', $value)) {
                        $this->$field = ObjectFactory::make($client, $value);
                    } else {
                        // Has One Relation - Deprecated
                        $name = $value['field_name'];
                        $this->$name    = $value['field_value'];
                        $this->$field   = ObjectFactory::make($client, $value['object_data']);
                    }
                } else {
                    // HasMany / ManyMany Relation
                    $list = [];
                    foreach ($value as $item) {
                        if (is_array($item) && ArrayLib::is_associative($item)) {
                            $list[] = ObjectFactory::make($client, $item);
                        } else {
                            $list[] = $item;
                        }
                    }

                    $this->$field = $list;
                }
            }
        }
    }

    /**
     * @param bool $flatten
     * @return array
     */
    public function toArray(bool $flatten = false): array
    {
        if (!$flatten) return iterator_to_array($this->getIterator());

        $data = [];

        foreach ($this->getIterator() as $key => $value) {
            $data[$key] = $this->flatten($value);
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
        return $this->ID > 0;
    }

    /**
     * @return ApiService
     * @throws ErrorException
     */
    public function getService(): ApiService
    {
        foreach (ClassMap::SERVICE_MODEL as $service => $model) {
            if ($model === static::class) return new $service($this->getClient());
        }

        throw new ErrorException("Could not determine which service to use for " . static::class);
    }

    /**
     * @return $this|null
     * @throws ErrorException
     */
    protected function save(): ?Model
    {
        $service = $this->getService();

        if ($this->exists()) {
            if (!method_exists($service, 'update')) return $this;
            return $service->update($this->ID, $this->toArray());
        } else {
            if (!method_exists($service, 'create')) return $this;
            return $service->create($this->toArray());
        }
    }
    
    /**
     * @param $obj
     * @return array
     */
    private function flatten($obj)
    {
        if ($obj instanceof self) {
            return $obj->toArray(true);
        } else if ($obj instanceof ListResource) {
            $data = [];
            /** @var self $item */
            foreach ($obj as $item) $data[] = $this->flatten($item);
            return $data;
        } else if (is_array($obj)) {
            $data = [];
            foreach ($obj as $key => $value) {
                $data[$key] = $this->flatten($value);
            }
            return $data;
        }
        
        return $obj;
    }
}
