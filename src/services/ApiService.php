<?php

namespace Lifeboat\Services;

use Lifeboat\Connector;
use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Factory\ObjectFactory;
use Lifeboat\Resource\ApiResource;

/**
 * Class ApiService
 * @package Lifeboat\Services
 *
 * @property Connector $client
 */
abstract class ApiService {
    
    /** @var Connector $client */
    protected $client;

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
    protected function _get(string $url, array $params = []): ?ApiResource
    {
        $curl = $this->getClient()->curl_api($url, 'GET', $params);

        if ($curl->isValid() && $curl->isJSON()) {
            return ObjectFactory::make($this->getClient(), $curl->getJSON());
        }

        throw new ApiException($curl->getError());
    }

    /**
     * @param string $url
     * @param array $params
     * @return ApiResource|bool|null
     * @throws ApiException
     * @throws OAuthException
     */
    protected function _post(string $url, array $params = [])
    {
        $curl = $this->getClient()->curl_api($url, 'POST', $params);
        
        if ($curl->isValid()) {
            if ($curl->isJSON()) {
                return ObjectFactory::make($this->getClient(), $curl->getJSON());
            } else {
                return true;
            }
        }

        throw new ApiException($curl->getError());
    }

    /**
     * @param string $url
     * @param array $params
     * @return bool
     * @throws ApiException
     * @throws OAuthException
     */
    protected function _delete(string $url, array $params = []): bool
    {
        $curl = $this->getClient()->curl_api($url, 'DELETE', $params);

        if ($curl->isValid()) return true;

        throw new ApiException($curl->getError());
    }
}
