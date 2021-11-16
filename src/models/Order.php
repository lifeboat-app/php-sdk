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

    protected static array $casting = [
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
    public function model(): string
    {
        return 'Order';
    }

    /**
     * @return Orders
     */
    public function getService(): Orders
    {
        return new Orders($this->getClient());
    }

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
    public function FulfillmentStatus(): string
    {
        switch ($this->Status) {
            case Orders::FULFILLMENT_PENDING:   return 'pending';
            case Orders::FULFILLMENT_FULFILLED: return 'fulfilled';
            case Orders::FULFILLMENT_DELIVERED: return 'delivered';
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
}
