<?php

namespace Lifeboat\Resource;

use Lifeboat\Connector;
use ArrayAccess;
use Countable;
use Lifeboat\Services\ApiService;

/**
 * Class ApiResource
 * @package Lifeboat\Resource
 *
 * @property Connector $_client
 */
abstract class ApiResource implements ArrayAccess, Countable {

    private Connector $_client;

    abstract public function getService(): ApiService;

    /**
     * ApiResource constructor.
     * @param Connector $client
     */
    public function __construct(Connector $client)
    {
        $this->setClient($client);
    }

    /**
     * @param Connector $client
     * @return $this
     */
    public function setClient(Connector $client): ApiResource
    {
        $this->_client = $client;
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
