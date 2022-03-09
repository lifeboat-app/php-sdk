<?php

namespace Lifeboat\Services;

use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Models\Location;
use Lifeboat\Resource\ListResource;

/**
 * Class Locations
 * @package Lifeboat\Services
 */
class Locations extends ApiService {

    /**
     * @param int $id
     * @return Location|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function fetch(int $id): ?Location
    {
        /** @var Location|null $fetch */
        $fetch = $this->_get('api/locations/location/' . $id);
        return $fetch;
    }

    /**
     * @param array $data
     * @return Location|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function create(array $data): ?Location
    {
        /** @var Location|null $create */
        $create = $this->_post('api/locations/location', $data);
        return $create;
    }

    /**
     * @param int $id
     * @param array $data
     * @return Location|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function update(int $id, array $data): ?Location
    {
        /** @var Location|null $post */
        $post = $this->_post('api/locations/location/' . $id, $data);
        return $post;
    }

    /**
     * @return ListResource
     */
    public function all(): ListResource {
        return new ListResource($this->getClient(), 'api/locations/all', [], 20);
    }
}
