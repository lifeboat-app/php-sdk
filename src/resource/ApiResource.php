<?php

namespace Lifeboat\Resource;

use Lifeboat\Connector;
use ArrayAccess;
use Countable;

/**
 * Class ApiResource
 * @package Lifeboat\Resource
 *
 * @property Connector|null $_client
 */
abstract class ApiResource implements ArrayAccess, Countable {

    private Connector $_client;

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
