<?php

namespace Lifeboat\Tests;

use Lifeboat\Exceptions\InvalidArgumentException;
use Lifeboat\Resource\ListResource;
use Lifeboat\Resource\SimpleList;
use Lifeboat\Services\Addresses;
use Lifeboat\Services\Collections;
use Lifeboat\Services\Customers;
use Lifeboat\Services\CustomPages;
use Lifeboat\Services\DeliveryZones;
use Lifeboat\Services\Orders;
use Lifeboat\Services\Pages;

/**
 * Class ServicesTest
 * @package Lifeboat\Tests
 */
class ServicesTest extends TestCase {

    /**
     * @test
     *
     * @covers \Lifeboat\Resource\ListResource::getClient
     * @covers \Lifeboat\Resource\ListResource::setClient
     * @covers \Lifeboat\Resource\ListResource::getURL
     * @covers \Lifeboat\Resource\ListResource::setURL
     * @covers \Lifeboat\Resource\ListResource::getParams
     * @covers \Lifeboat\Resource\ListResource::setParams
     *
     * @covers \Lifeboat\Services\Addresses::all
     */
    public function test_address_all()
    {
        $service = new Addresses($this->getMockClient());
        $this->check_curl_all(
            $service->all('xxx'),
            'api/addresses/all',
            ListResource::class,
            ['search' => 'xxx']
        );
    }

    /**
     * @test
     * @covers \Lifeboat\Services\Collections::all
     */
    public function test_collections_all()
    {
        $service = new Collections($this->getMockClient());
        $this->check_curl_all($service->all(), 'api/collections/all');
    }

    /**
     * @test
     *
     * @covers \Lifeboat\Resource\ListResource::getClient
     * @covers \Lifeboat\Resource\ListResource::setClient
     * @covers \Lifeboat\Resource\ListResource::getURL
     * @covers \Lifeboat\Resource\ListResource::setURL
     * @covers \Lifeboat\Resource\ListResource::getParams
     * @covers \Lifeboat\Resource\ListResource::setParams
     *
     * @covers \Lifeboat\Services\Customers::all
     */
    public function test_customers_all()
    {
        $service = new Customers($this->getMockClient());
        $this->check_curl_all(
            $service->all('xxx'),
            'api/customers/all',
            ListResource::class,
            ['search' => 'xxx']
        );
    }

    /**
     * @test
     *
     * @covers \Lifeboat\Resource\ListResource::getClient
     * @covers \Lifeboat\Resource\ListResource::setClient
     * @covers \Lifeboat\Resource\ListResource::getURL
     * @covers \Lifeboat\Resource\ListResource::setURL
     * @covers \Lifeboat\Resource\ListResource::getParams
     * @covers \Lifeboat\Resource\ListResource::setParams
     *
     * @covers \Lifeboat\Services\CustomPages::all
     */
    public function test_custom_pages_all()
    {
        $service = new CustomPages($this->getMockClient());
        $this->check_curl_all(
            $service->all('xxx'),
            'api/pages/page/all',
            ListResource::class,
            ['search' => 'xxx', 'sort' => CustomPages::SORT_DEFAULT]
        );

        try {
            $service->all('xxx', 'xxx');
            $this->fail('CustomPages::all() parameter 2 should be a valid sort');
        } catch (InvalidArgumentException $e) {
            // Error should have been thrown
        }
    }

    /**
     * @test
     *
     * @covers \Lifeboat\Resource\ListResource::getClient
     * @covers \Lifeboat\Resource\ListResource::setClient
     * @covers \Lifeboat\Resource\ListResource::getURL
     * @covers \Lifeboat\Resource\ListResource::setURL
     * @covers \Lifeboat\Resource\ListResource::getParams
     * @covers \Lifeboat\Resource\ListResource::setParams
     *
     * @covers \Lifeboat\Services\DeliveryZones::all
     */
    public function test_delivery_zones_all()
    {
        $service = new DeliveryZones($this->getMockClient());
        $this->check_curl_all($service->all(), 'api/delivery-zones/all');
    }

    /**
     * @test
     * @covers \Lifeboat\Services\Orders::all
     */
    public function test_orders_all()
    {
        $params = ['period' => Orders::PERIOD_7, 'status' => Orders::STATUS_PAID, 'fulfillment' => Orders::FULFILLMENT_PENDING];

        $service = new Orders($this->getMockClient());
        $this->check_curl_all($service->all(), 'api/orders/all', ListResource::class, $params);

        try {
            $service->all('xxx');
            $this->fail('Orders::all expects parameter 1 to be a valid period');
        } catch (InvalidArgumentException $e) {
            // Error should have been thrown
        }

        try {
            $service->all(Orders::PERIOD_7, -1);
            $this->fail('Orders::all expects parameter 2 to be a valid status');
        } catch (InvalidArgumentException $e) {
            // Error should have been thrown
        }

        try {
            $service->all(Orders::PERIOD_7, Orders::STATUS_PAID, -1);
            $this->fail('Orders::all expects parameter 3 to be a valid fulfillment status');
        } catch (InvalidArgumentException $e) {
            // Error should have been thrown
        }
    }

    /**
     * @test
     *
     * @covers \Lifeboat\Resource\ListResource::getClient
     * @covers \Lifeboat\Resource\ListResource::setClient
     * @covers \Lifeboat\Resource\ListResource::getURL
     * @covers \Lifeboat\Resource\ListResource::setURL
     * @covers \Lifeboat\Resource\ListResource::getParams
     * @covers \Lifeboat\Resource\ListResource::setParams
     *
     * @covers \Lifeboat\Services\Pages::all
     */
    public function test_pages_all()
    {
        $service = new Pages($this->getMockClient());
        $this->check_curl_all($service->all(), 'api/pages/all', SimpleList::class);
    }

    /**
     * @param ListResource $all
     * @param string $list_class
     * @param string $url
     * @param array $params
     */
    private function check_curl_all(
        ListResource $all,
        string $url,
        string $list_class = ListResource::class,
        array $params = []
    ){
        $this->assertInstanceOf($list_class, $all);
        $this->assertEquals($url, $all->getURL());
        $this->assertEquals($this->getMockClient(), $all->getClient());
        $this->assertEquals($params, $all->getParams());
    }
}
