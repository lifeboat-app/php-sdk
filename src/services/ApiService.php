<?php

namespace Lifeboat\Services;

use Lifeboat\Connector;
use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Factory\ObjectFactory;
use Lifeboat\Models\Model;
use Lifeboat\Resource\ApiResource;

/**
 * Class ApiService
 * @package Lifeboat\Services
 *
 * @property Connector $client
 */
abstract class ApiService {

    /** @var Connector $client */
    protected Connector $client;

    /**
     * @param int $id
     * @return Model|null
     */
    abstract public function fetch(int $id = -1): ?Model;

    public function __construct(Connector $client)
    {
        $this->setClient($client);
    }

    /**
     * @return Connector
     */
    public function getClient(): Connector
    {
        return $this->client;
    }

    /**
     * @param Connector $client
     * @return $this
     */
    public function setClient(Connector $client): ApiService
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @see ObjectFactory::make()
     * @see Connector::curl_api()
     *
     * @param string $url
     * @param array $params
     * @return ApiResource|null
     * @throws ApiException
     * @throws OAuthException
     */
    protected function retrieve(string $url, array $params = []): ?ApiResource
    {
        $curl = $this->getClient()->curl_api($url, 'GET', $params);

        if ($curl->isValid() && $curl->isJSON()) {
            return ObjectFactory::make($this->getClient(), $curl->getJSON());
        }

        throw new ApiException($curl->getError());
    }
}
