<?php

namespace Lifeboat\Services;

use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Models\Product;
use Lifeboat\Resource\ListResource;

/**
 * Class Product
 * @package Lifeboat\Services
 */
class Products extends ApiService {

    const LIST_DETAILED     = 'detailed';
    const LIST_SIMPLE       = 'simple';
    const LIST_BARE         = 'bare';
    const SORT_TITLE_ASC    = 'title_az';
    const SORT_TITLE_DESC   = 'title_za';
    const SORT_SKU_ASC      = 'title_az';
    const SORT_SKU_DESC     = 'title_za';
    const SORT_PRICE_ASC    = 'title_az';
    const SORT_PRICE_DESC   = 'title_za';
    const SORT_CREATED_ASC  = 'title_az';
    const SORT_CREATED_DESC = 'title_za';
    const SORT_EDITED_ASC   = 'title_az';
    const SORT_EDITED_DESC  = 'title_za';
    
    /**
     * @param int $id
     * @return Product|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function fetch(int $id): ?Product
    {
        /** @var Product|null $fetch */
        $fetch = $this->_get('api/products/product/' . $id);
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
        $create = $this->_post('api/products/product/', $data);
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
        $post = $this->_post('api/products/product' . $id, $data);
        return $post;
    }

    /**
     * @param string $search
     * @param string $sort
     * @param string $type
     * @return ListResource
     */
    public function all(
        string $search = '', 
        string $sort = self::SORT_CREATED_DESC, 
        string $type = self::LIST_BARE
    ): ListResource {
        return new ListResource($this->getClient(), 'api/products/all', [
            'search'    => $search,
            'sort'      => $sort,
            'data'      => $type
        ], 20);
    }
}
