<?php

namespace Lifeboat\Models;

use Lifeboat\Resource\ObjectResource;
use Lifeboat\Services\Orders;

/**
 * Class Order
 * @package Lifeboat\Models
 *
 * @property string $Status
 * @property string $Fulfillment
 * @property string|null $DiscountCode
 * @property \DateTime|string|null $Created
 * @property \DateTime|string|null $LastModified
 * @property string $OID
 * @property float $Subtotal
 * @property float $Tax
 * @property float $Delivery
 * @property float $Handling
 * @property float $Discount
 * @property float $Total
 * @property \DateTime|string|null $PaidOn
 * @property \DateTime|string|null $DeliveredOn
 * @property string $PaymentMethod
 * @property string $Provider
 * @property int $FulfillmentType
 * @property array $Discounts
 * @property string $Currency
 * @property array $Products
 * @property ObjectResource|null $ShipTo
 * @property ObjectResource|null $BillTo
 * @property array $Waypoints
 * @property ObjectResource|null $Route
 */
class Order extends Model {

    protected static $casting = [
        'Created'       => 'lifeboat_date_formatter',
        'LastModified'  => 'lifeboat_date_formatter',
        'Subtotal'      => 'floatval',
        'Tax'           => 'floatval',
        'Delivery'      => 'floatval',
        'Handling'      => 'floatval',
        'Discount'      => 'floatval',
        'Total'         => 'floatval',
        'PaidOn'        => 'lifeboat_date_formatter',
        'DeliveredOn'   => 'lifeboat_date_formatter',
    ];

    /**
     * @return string
     */
    public function FulfillmentType(): string
    {
        switch ($this->FulfillmentType) {
            case Orders::FULFILLMENT_SHIP:      return 'ship';
            case Orders::FULFILLMENT_DELIVER:   return 'deliver';
            case Orders::FULFILLMENT_PICKUP:    return 'pickup';
        }

        return '';
    }


    /**
     * @return string
     */
    public function Status(): string
    {
        switch ($this->Status) {
            case Orders::STATUS_OPEN:   return 'open';
            case Orders::STATUS_PAID:   return 'paid';
        }

        return '';
    }

    /**
     * @param string $price
     * @return string
     */
    public function formatPrice(string $price): string
    {
        switch (strtolower($price)) {
            case 'subtotal' : $value = $this->Subtotal; break;
            case 'tax'      : $value = $this->Tax; break;
            case 'delivery' : $value = $this->Delivery; break;
            case 'handling' : $value = $this->Handling; break;
            case 'discount' : $value = $this->Discount; break;
            case 'total'    : $value = $this->Total; break;
            default: return '';
        }

        return ($value !== 0) ?  number_format($value, 2) . $this->Currency : '-';
    }
}
