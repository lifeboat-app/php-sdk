<?php

namespace Lifeboat\Models;

use Lifeboat\Services\Customers;

/**
 * Class Customer
 * @package Lifeboat\Models
 *
 */
class Customer extends Model {

    protected static array $casting = [
    ];

    /**
     * @return string
     */
    public function model(): string
    {
        return 'Customer';
    }

    /**
     * @return Customers
     */
    public function getService(): Customers
    {
        return new Customers($this->getClient());
    }
}
