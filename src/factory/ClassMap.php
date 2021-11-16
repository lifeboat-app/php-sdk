<?php

namespace Lifeboat\Factory;

use Lifeboat\Models;
use Lifeboat\Services;

class ClassMap {

    const MODELS = [
        'order'     => Models\Order::class,
        'address'   => Models\Address::class
    ];

    const SERVICES = [
        'orders'    => Services\Orders::class,
        'addresses' => Services\Addresses::class,
    ];
}
