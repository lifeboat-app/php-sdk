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
    ];

    const SERVICES = [
        'orders'        => Services\Orders::class,
        'addresses'     => Services\Addresses::class,
        'customers'     => Services\Customers::class,
        'collections'   => Services\Collections::class,
        'pages'         => Services\Pages::class,
        'custom_pages'  => Services\CustomPages::class
    ];
}
