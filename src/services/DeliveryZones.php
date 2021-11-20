<?php

namespace Lifeboat\Services;

use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Models\DeliveryZone;
use Lifeboat\Resource\ListResource;

/**
 * Class DeliveryZones
 * @package Lifeboat\Services
 */
class DeliveryZones extends ApiService {

    /**
     * @param int $id
     * @return DeliveryZone|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function fetch(int $id): ?DeliveryZone
    {
        /** @var DeliveryZone|null $fetch */
        $fetch = $this->_get('api/delivery-zones/zone' . $id);
        return $fetch;
    }

    /**
     * @param array $data
     * @return DeliveryZone|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function create(array $data): ?DeliveryZone
    {
        /** @var DeliveryZone|null $create */
        $create = $this->_post('api/delivery-zones/zone', $data);
        return $create;
    }

    /**
     * @param int $id
     * @param array $data
     * @return DeliveryZone|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function update(int $id, array $data): ?DeliveryZone
    {
        /** @var DeliveryZone|null $post */
        $post = $this->_post('api/delivery-zones/zone/' . $id, $data);
        return $post;
    }

    /**
     * @return ListResource
     */
    public function all(): ListResource {
        return new ListResource($this->getClient(), 'api/delivery-zones/all', [], 20);
    }
}
