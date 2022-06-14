<?php

namespace Lifeboat\Services;

use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Models\ProductType;
use Lifeboat\Resource\ListResource;

/**
 * Class ProductTypes
 * @package Lifeboat\Services
 */
class ProductTypes extends ApiService {

    /**
     * @param int $id
     * @return ProductType|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function fetch(int $id): ?ProductType
    {
        /** @var ProductType|null $type */
        $type = $this->_get('api/product-types/type/' . $id);
        return $type;
    }

    /**
     * @param array $data
     * @return ProductType|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function create(array $data): ?ProductType
    {
        /** @var ProductType|null $type */
        $type = $this->_post('api/product-types/type', $data);
        return $type;
    }

    /**
     * @param int $id
     * @param array $data
     * @return ProductType|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function update(int $id, array $data): ?ProductType
    {
        /** @var ProductType|null $class */
        $type = $this->_post('api/product-types/type/' . $id, $data);
        return $type;
    }

    /**
     * @param string $search
     * @return ListResource
     */
    public function all(): ListResource {
        return new ListResource($this->getClient(), 'api/product-types/all');
    }
}
