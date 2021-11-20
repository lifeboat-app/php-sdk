<?php

namespace Lifeboat\Factory;

use Lifeboat\Models;
use Lifeboat\Services;

/**
 * Class ClassMap
 * @package Lifeboat\Factory
 */
class ClassMap {

    const MODELS = [
        'order'         => Models\Order::class,
        'address'       => Models\Address::class,
        'customer'      => Models\Customer::class,
        'collection'    => Models\Collection::class,
        'page'          => Models\Page::class,
        'custompage'    => Models\CustomPage::class,
        'deliveryzone'  => Models\DeliveryZone::class,
    ];

    const SERVICES = [
        'orders'            => Services\Orders::class,
        'addresses'         => Services\Addresses::class,
        'customers'         => Services\Customers::class,
        'collections'       => Services\Collections::class,
        'pages'             => Services\Pages::class,
        'custom_pages'      => Services\CustomPages::class,
        'delivery_zones'    => Services\DeliveryZones::class
    ];

    const SERVICE_MODEL = [
        Services\Orders::class          => Models\Order::class,
        Services\Addresses::class       => Models\Address::class,
        Services\Customers::class       => Models\Customer::class,
        Services\Collections::class     => Models\Collection::class,
        Services\Pages::class           => Models\Page::class,
        Services\CustomPages::class     => Models\CustomPage::class,
        Services\DeliveryZones::class   => Models\DeliveryZone::class
    ];
}
