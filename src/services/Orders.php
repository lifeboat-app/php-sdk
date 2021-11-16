<?php

namespace Lifeboat\Services;

use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\BadMethodException;
use Lifeboat\Exceptions\InvalidArgumentException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Models\Customer;
use Lifeboat\Models\Order;
use Lifeboat\Resource\ListResource;

/**
 * Class Orders
 * @package Lifeboat\Services
 */
class Orders extends ApiService {

    const PERIOD_ALL    = 'all';
    const PERIOD_DAY    = '1';
    const PERIOD_7      = '7';
    const PERIOD_30     = '30';
    const PERIOD_90     = '90';
    const PERIOD_120    = '120';

    const STATUS_OPEN   = 1;
    const STATUS_PAID   = 2;

    const FULFILLMENT_PENDING       = 1;
    const FULFILLMENT_FULFILLED     = 2;
    const FULFILLMENT_DELIVERED     = 3;

    const FULFILLMENT_SHIP          = 0;
    const FULFILLMENT_DELIVER       = 1;
    const FULFILLMENT_PICKUP        = 2;

    /**
     * @param int $id
     * @return Order|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function fetch(int $id): ?Order
    {
        /** @var Order|null $fetch */
        $fetch = $this->_get('api/orders/order' . $id);
        return $fetch;
    }

    /**
     * @param array $data
     * @return Order|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function create(array $data): ?Order
    {
        /** @var Order|null $create */
        $create = $this->_post('api/orders/order', $data);
        return $create;
    }

    /**
     * @param int $id
     * @param array $data
     * @return Order|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function update(int $id, array $data): ?Order
    {
        /** @var Order|null $post */
        $post = $this->_post('api/orders/order/' . $id, $data);
        return $post;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        throw new BadMethodException("Orders cannot be deleted");
    }

    /**
     * @param string $period
     * @param int $status
     * @param int $fulfillment
     * @return ListResource
     */
    public function all(
        string $period = self::PERIOD_7,
        int $status = self::STATUS_PAID,
        int $fulfillment = self::FULFILLMENT_PENDING
    ): ListResource {
        $data = [
            'period'        => $period,
            'status'        => $status,
            'fulfillment'   => $fulfillment
        ];

        return new ListResource($this->getClient(), 'api/orders/all', $data, 20);
    }

    /**
     * @return ListResource
     */
    public function deliveries(): ListResource
    {
        return new ListResource($this->getClient(), 'api/orders/delivery', [], 20);
    }

    /**
     * @return ListResource
     */
    public function pickups(): ListResource
    {
        return new ListResource($this->getClient(), 'api/orders/pickup', [], 20);
    }
}
