<?php

namespace Lifeboat\Models;

/**
 * Class TaxZone
 * @package Lifeboat\Models
 *
 * @property string $Name
 * @property float $BusinessPercent
 * @property array $TaxPercent
 * @property DeliveryZone $DeliveryZone
 * @property int $DeliveryZoneID
 */
class TaxZone extends Model {

    protected static $casting = [
        'TaxPercent'        => 'floatval',
        'BusinessPercent'   => 'floatval'
    ];
}
