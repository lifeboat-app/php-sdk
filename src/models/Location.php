<?php

namespace Lifeboat\Models;

/**
 * Class Location
 * @package Lifeboat\Models
 *
 * @property string $Name
 * @property string $Description
 * @property int $Type
 * @property string $Address
 * @property string $AddressL2
 * @property string $City
 * @property string $SubdivisionCode
 * @property string $CountryCode
 * @property string $PostCode
 * @property string $Email
 * @property string $Tel
 * @property float $Latitude
 * @property float $Longitude
 * @property bool $DeliverFrom
 * @property float $DeliveryMinOrder
 * @property float $DeliveryBaseFee
 * @property float $DeliveryRadius
 * @property string $DeliveryNote
 * @property bool $PickupFrom
 * @property float $PickupMinOrder
 * @property string $PickupNote
 * @property DeliveryZone[] $DeliveryZones
 */
class Location extends Model {

    const TYPE_WAREHOUSE    = 1;
    const TYPE_OFFICE       = 2;
    const TYPE_OUTLET       = 3;

    const LOCATION_TYPES = [
        self::TYPE_WAREHOUSE    => 'warehouse',
        self::TYPE_OFFICE       => 'office',
        self::TYPE_OUTLET       => 'outlet',
    ];

    protected static $casting = [
        'Latitude'          => 'floatval',
        'Longitude'         => 'floatval',
        'DeliverFrom'       => 'boolval',
        'DeliveryMinOrder'  => 'floatval',
        'DeliveryBaseFee'   => 'floatval',
        'DeliveryRadius'    => 'floatval',
        'PickupFrom'        => 'boolval',
        'PickupMinOrder'    => 'floatval',
    ];

    /**
     * @return string
     */
    public function TypeName(): string
    {
        return (array_key_exists($this->Type, self::LOCATION_TYPES))
            ? self::LOCATION_TYPES[$this->Type]
            : '';
    }

}
