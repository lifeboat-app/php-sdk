<?php

namespace Lifeboat\Models;

use Lifeboat\Services\Customers;

/**
 * Class Customer
 * @package Lifeboat\Models
 *
 * @property string $FirstName
 * @property string $Surname
 * @property string $Email
 * @property string|null $VATNo
 * @property string|null $Tel
 * @property \DateTime|string|null $Birthday
 * @property string $FullName
 * @property int $Loyalty
 * @property array $Orders
 * @property array $Addresses
 * @property array $WholesalePriceGroups
 * @property array $LoyaltyActions
 *
 */
class Customer extends Model {

    protected static $casting = [
        'Birthday' => 'lifeboat_date_formatter'
    ];
}
