<?php

namespace Lifeboat\Services;

use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Models\ShippingClass;
use Lifeboat\Resource\ListResource;

/**
 * Class ShippingClasses
 * @package Lifeboat\Services
 */
class ShippingClasses extends ApiService {

    /**
     * @param int $id
     * @return ShippingClass|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function fetch(int $id): ?ShippingClass
    {
        /** @var ShippingClass|null $class */
        $class = $this->_get('api/shipping-classes/class/' . $id);
        return $class;
    }

    /**
     * @param array $data
     * @return ShippingClass|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function create(array $data): ?ShippingClass
    {
        /** @var ShippingClass|null $class */
        $class = $this->_post('api/shipping-classes/class', $data);
        return $class;
    }

    /**
     * @param int $id
     * @param array $data
     * @return ShippingClass|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function update(int $id, array $data): ?ShippingClass
    {
        /** @var ShippingClass|null $class */
        $class = $this->_post('api/shipping-classes/class/' . $id, $data);
        return $class;
    }

    /**
     * @param string $search
     * @return ListResource
     */
    public function all(): ListResource {
        return new ListResource($this->getClient(), 'api/shipping-classes/all');
    }
}
