<?php

namespace Lifeboat\Models;

/**
 * Class Order
 * @package Lifeboat\Models
 *
 *
 */
class Order extends Model {

    /**
     * @return string
     */
    protected function getSaveURL(): string
    {
        return 'api/orders/order/' . $this->ID;
    }
}
