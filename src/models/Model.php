<?php

namespace Lifeboat\Models;

use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Resource\ObjectResource;
use Lifeboat\Services\ObjectFactory;

/**
 * Class Model
 * @package Lifeboat\Models
 *
 * @property int $ID
 */
abstract class Model extends ObjectResource {

    abstract public function retrieve(int $id = -1): ?Model;
    abstract protected function getSaveURL(): string;

    /**
     * @return array
     */
    public function toArray(): array
    {
        return iterator_to_array($this->getIterator());
    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
        return $this->ID > 0;
    }

    /**
     * @see Model::curl_for_model()
     *
     * @return Model|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function save(): ?Model
    {
        return $this->curl_for_model($this->getSaveURL(),'POST', $this->toArray());
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $data
     * @param array $headers
     * @return Model|null
     * @throws ApiException If the API threw an error
     * @throws OAuthException If the client could not connect
     */
    protected function curl_for_model(string $url, string $method = 'GET', array $data = [], array $headers = []): ?Model
    {
        $curl = $this->getClient()->curl_api($url, $method, $data, $headers);

        if ($curl->isValid() && $curl->isJSON()) {
            $data   = $curl->getJSON();
            $model  = $data['model'];
            unset($data['model']);

            return ObjectFactory::create($this->getClient(), $model, $data);
        }

        throw new ApiException($curl->getError());
    }
}
