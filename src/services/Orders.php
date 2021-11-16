<?php

namespace Lifeboat\Services;

use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\InvalidArgumentException;
use Lifeboat\Exceptions\OAuthException;
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
     * @throws InvalidArgumentException If param $id is less than 1
     */
    public function fetch(int $id = -1): ?Order
    {
        $class = get_called_class();
        if ($id <= 0) {
            throw new InvalidArgumentException("{$class}::fetch() expects parameter 1 to be a positive integer");
        }

        /** @var Order|null $order */
        $order = $this->retrieve('api/orders/order/' . $id);
        return $order;
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
