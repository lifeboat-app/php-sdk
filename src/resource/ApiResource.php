<?php

namespace Lifeboat\Resource;

use Lifeboat\Connector;

/**
 * Class ApiResource
 * @package Lifeboat\Resource
 *
 * @property Connector|null $_client
 */
abstract class ApiResource implements \ArrayAccess, \Countable {

    private $_client;

    /**
     * @param Connector $_client
     * @return $this
     */
    public function setClient(Connector $_client): ApiResouce
    {
        $this->_client = $_client;
        return $this;
    }

    /**
     * @return Connector
     */
    public function getClient(): Connector
    {
        return $this->_client;
    }
}
