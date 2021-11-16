<?php

namespace Lifeboat\Services;

use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\BadMethodException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Models\Customer;
use Lifeboat\Resource\ListResource;

/**
 * Class Customers
 * @package Lifeboat\Services
 */
class Customers extends ApiService {

    /**
     * @param int $id
     * @return Customer|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function fetch(int $id): ?Customer
    {
        /** @var Customer|null $fetch */
        $fetch = $this->_get('api/customers/customer' . $id);
        return $fetch;
    }

    /**
     * @param array $data
     * @return Customer|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function create(array $data): ?Customer
    {
        /** @var Customer|null $create */
        $create = $this->_post('api/customers/customer', $data);
        return $create;
    }

    /**
     * @param int $id
     * @param array $data
     * @return Customer|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function update(int $id, array $data): ?Customer
    {
        /** @var Customer|null $post */
        $post = $this->_post('api/customers/customer/' . $id, $data);
        return $post;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        throw new BadMethodException("Customers cannot be deleted");
    }

    /**
     * @param string $search
     * @return ListResource
     */
    public function all(string $search = ''): ListResource {
        return new ListResource($this->getClient(), 'api/customers/all', ['search' => $search], 20);
    }
}
