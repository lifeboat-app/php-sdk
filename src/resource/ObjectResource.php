<?php

namespace Lifeboat\Resource;

use Lifeboat\Connector;

/**
 * Class ResourceList
 * @package Lifeboat\Resource
 *
 * @property array $_object_data
 */
class ObjectResource extends ApiResource {

    private $_object_data   = [];

    public function __construct(Connector $connector, array $_object_data = [])
    {
        $this->setClient($connector);
        foreach ($_object_data as $k => $v) {
            $this->__set($k, $v);
        }
    }

    /**
     * @param string $field
     * @return mixed
     */
    public function __get(string $field)
    {
        return $this->_object_data[$field] ?? null;
    }

    /**
     * @param string $field
     * @param $value
     * @return $this
     */
    public function __set(string $field, $value): ObjectResource
    {
        $this->_object_data[$field] = $value;
        return $this;
    }

    public function offsetGet($offset)
    {
        return $this->_object_data[$offset] ?? null;
    }

    public function offsetSet($offset, $value)
    {
        return $this->_object_data[$offset] = $value;
    }

    /**
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->_object_data[$offset]);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->_object_data);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->_object_data);
    }

    /**
     * @return array
     */
    public function keys(): array
    {
        return \array_keys($this->_object_data);
    }

    /**
     * @return array
     */
    public function values(): array
    {
        return \array_values($this->_object_data);
    }
}
