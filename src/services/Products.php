<?php

namespace Lifeboat\Services;

use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Models\Location;
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
    const SORT_SKU_ASC      = 'sku_az';
    const SORT_SKU_DESC     = 'sku_za';
    const SORT_PRICE_ASC    = 'price_az';
    const SORT_PRICE_DESC   = 'price_za';
    const SORT_CREATED_ASC  = 'created_az';
    const SORT_CREATED_DESC = 'created_za';
    const SORT_EDITED_ASC   = 'edited_az';
    const SORT_EDITED_DESC  = 'edited_za';

    private static $_cache_lists = [];

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
        $post = $this->_post('api/products/product/' . $id, $data);
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
        return $this->_delete('api/products/product/' . $id);
    }

    /**
     * @param int $id
     * @param int $quantity
     * @param Location $location
     * @return bool
     * @throws ApiException
     * @throws OAuthException
     */
    public function setStockLevel(int $id, int $quantity, Location $location): bool
    {
        return (bool) $this->_post('api/products/inventory/'. $id . '/quantity', [
            'location'  => $location->ID,
            'quantity'  => $quantity
        ]);
    }

    /**
     * @param string $search
     * @param string $sort
     * @param string $type
     * @param bool $clear_cache
     * @return ListResource
     */
    public function all(
        string $search = '',
        string $sort = self::SORT_CREATED_DESC,
        string $type = self::LIST_BARE,
        bool $clear_cache = false
    ): ListResource {
        $client = $this->getClient();
        $key = md5($client->getSiteKey().$search.$sort.$type);

        if (!array_key_exists($key, self::$_cache_lists) || $clear_cache) {
            self::$_cache_lists[$key] = new ListResource($client, 'api/products/all', [
                'search'    => $search,
                'sort'      => $sort,
                'data'      => $type
            ], 20);
        }

        return self::$_cache_lists[$key];
    }
}
