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
    const VALID_PERIODS = [
        self::PERIOD_ALL, self::PERIOD_DAY, self::PERIOD_7,
        self::PERIOD_30, self::PERIOD_90, self::PERIOD_120
    ];

    const STATUS_OPEN   = 1;
    const STATUS_PAID   = 2;
    const VALID_STATUSES = [
        self::STATUS_OPEN, self::STATUS_PAID
    ];

    const FULFILLMENT_PENDING       = 1;
    const FULFILLMENT_FULFILLED     = 2;
    const FULFILLMENT_DELIVERED     = 3;
    const VALID_FULFILLMENT_STATUSES = [
        self::FULFILLMENT_PENDING, self::FULFILLMENT_FULFILLED, self::FULFILLMENT_DELIVERED
    ];

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
        $fetch = $this->_get('api/orders/order/' . $id);
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
     * @param string $period
     * @param int $status
     * @param int $fulfillment
     * @param string $search
     * @param int $page_length
     * @param string $url
     * @return ListResource
     */
    public function all(
        string $period = self::PERIOD_7,
        int $status = self::STATUS_PAID,
        int $fulfillment = self::FULFILLMENT_PENDING,
        string $search = '',
        int $page_length = 20,
        string $url = 'api/orders/all'
    ): ListResource {
        $data = $this->parse_filters($period, $status, $fulfillment, $search);
        return new ListResource($this->getClient(), $url, $data, $page_length);
    }

    /**
     * @param string $period
     * @param int $fulfillment
     * @param string $search
     * @param int $page_length
     * @return ListResource
     */
    public function deliveries(
        string $period = self::PERIOD_7,
        int $fulfillment = self::FULFILLMENT_PENDING,
        string $search = '',
        int $page_length = 20
    ): ListResource {
        return $this->all($period, self::STATUS_PAID, $fulfillment, $search, $page_length, 'api/orders/delivery');
    }

    /**
     * @param string $period
     * @param int $fulfillment
     * @param string $search
     * @param int $page_length
     * @return ListResource
     */
    public function pickups(
        string $period = self::PERIOD_7,
        int $fulfillment = self::FULFILLMENT_PENDING,
        string $search = '',
        int $page_length = 20
    ): ListResource
    {
        return $this->all($period, self::STATUS_PAID, $fulfillment, $search, $page_length, 'api/orders/pickup');
    }

    /**
     * @param string $period
     * @param int $status
     * @param int $fulfillment
     * @param string $search
     * @return array
     */
    private function parse_filters(
        string $period = self::PERIOD_7,
        int $status = self::STATUS_PAID,
        int $fulfillment = self::FULFILLMENT_PENDING,
        string $search = ''
    ): array {
        if (!in_array($period, self::VALID_PERIODS)) {
            throw new InvalidArgumentException("Orders::all expects parameter 1 to be a valid period");
        }

        if (!in_array($status, self::VALID_STATUSES)) {
            throw new InvalidArgumentException("Orders::all expects parameter 2 to be a valid status");
        }

        if (!in_array($fulfillment, self::VALID_FULFILLMENT_STATUSES)) {
            throw new InvalidArgumentException("Orders::all expects parameter 3 to be a valid fulfillment status");
        }

        return [
            'period'       => $period,
            'status'       => $status,
            'fulfillment'   => $fulfillment,
            'search'       => $search
        ];
    }
}
