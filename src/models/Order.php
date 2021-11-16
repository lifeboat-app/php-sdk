<?php

namespace Lifeboat\Models;

use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\OAuthException;

/**
 * Class Order
 * @package Lifeboat\Models
 *
 * @property string $Status
 * @property string $Fulfillment
 * @property string|null $DiscountCode
 * @property \DateTime $Created
 * @property \DateTime $LastModified
 * @property
 */
class Order extends Model {

    protected static array $casting = [
        'Created'       => 'lifeboat_date_formatter',
        'LastModified'  => 'lifeboat_date_formatter'
    ];

    /**
     * @see Model::write()
     *
     * @return Order|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function save(): ?Order
    {
        /** @var Order|null $order */
        $order = $this->write('api/orders/order/' . $this->ID);
        return $order;
    }
}
