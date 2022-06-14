<?php

namespace Lifeboat\Models;

/**
 * Class ShippingClass
 * @package Lifeboat\Models
 *
 * @property string $Name
 * @property float $BaseFee
 * @property int $ProductCount
 */
class ShippingClass extends Model {

    protected static $casting = [
        'BaseFee' => 'floatval'
    ];
}
