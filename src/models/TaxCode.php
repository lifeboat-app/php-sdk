<?php

namespace Lifeboat\Models;

/**
 * Class TaxCode
 * @package Lifeboat\Models
 *
 * @property string $Name
 * @property float $TaxPercent
 * @property TaxZone[] $TaxZones
 */
class TaxCode extends Model {

    protected static $casting = [
        'TaxPercent' => 'floatval'
    ];
}
