<?php

namespace Lifeboat\Services;

use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Models\Supplier;
use Lifeboat\Resource\ListResource;

/**
 * Class Suppliers
 * @package Lifeboat\Services
 */
class Suppliers extends ApiService {


    /**
     * @param int $id
     * @return Supplier|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function fetch(int $id): ?Supplier
    {
        /** @var Supplier|null $fetch */
        $fetch = $this->_get('api/suppliers/supplier/' . $id);
        return $fetch;
    }

    /**
     * @param array $data
     * @return Supplier|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function create(array $data): ?Supplier
    {
        /** @var Supplier|null $create */
        $create = $this->_post('api/suppliers/supplier/', $data);
        return $create;
    }

    /**
     * @param int $id
     * @param array $data
     * @return Supplier|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function update(int $id, array $data): ?Supplier
    {
        /** @var Supplier|null $post */
        $post = $this->_post('api/suppliers/supplier/' . $id, $data);
        return $post;
    }

    /**
     * @param int $id
     * @return bool
     * @throws ApiException
     * @throws OAuthException
     */
    public function delete(int $id): bool
    {
        return $this->_delete('api/suppliers/supplier/' . $id);
    }


    /**
     * @return ListResource
     */
    public function all(): ListResource {
        return new ListResource($this->getClient(), 'api/suppliers/all');
    }
}
