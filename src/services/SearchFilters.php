<?php

namespace Lifeboat\Services;

use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Models\Product;
use Lifeboat\Resource\ListResource;

/**
 * Class SearchFilters
 * @package Lifeboat\Services
 */
class SearchFilters extends ApiService {

    /**
     * @param int $id
     * @return Product|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function fetch(int $id): ?Product
    {
        /** @var Product|null $fetch */
        $fetch = $this->_get('api/product-search-filters/filter/' . $id);
        return $fetch;
    }

    /**
     * @param array $data
     * @return Product|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function create(array $data): ?Product
    {
        /** @var Product|null $create */
        $create = $this->_post('api/product-search-filters/filter/', $data);
        return $create;
    }

    /**
     * @param int $id
     * @param array $data
     * @return Product|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function update(int $id, array $data): ?Product
    {
        /** @var Product|null $post */
        $post = $this->_post('api/product-search-filters/filter/' . $id, $data);
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
        return $this->_delete('api/product-search-filters/filter/' . $id);
    }

    /**
     * @return ListResource
     */
    public function all(): ListResource
    {
        return new ListResource($this->getClient(), 'api/product-search-filters/all');
    }
}
