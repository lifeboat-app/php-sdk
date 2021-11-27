<?php

namespace Lifeboat\Models;

/**
 * Class DeliveryZone
 * @package Lifeboat\Models
 *
 * @property string $Name
 * @property float $HandlingFee
 * @property array $ZoneData
 * @property bool $is_default
 */
class DeliveryZone extends Model {

    protected static $casting = [
        'HandlingFee' => 'floatval'
    ];
}
