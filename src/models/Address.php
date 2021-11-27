<?php

namespace Lifeboat\Models;

use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Services\Addresses;

/**
 * Class Address
 * @package Lifeboat\Models
 *
 * @property string $Name
 * @property string $Address
 * @property string $AddressL2
 * @property string $City
 * @property string $SubdivisionCode
 * @property string $CountryCode
 * @property string $PostCode
 * @property string|null $Notes
 * @property string|null $Latitude
 * @property string|null $Longitude
 * @property string $Email
 * @property string|null $Tel
 * @property string|null $VATNo
 * @property bool $isDefault
 * @property string $Country
 * @property string $Subdivision
 * @property int $CustomerID
 */
class Address extends Model {

    protected static $casting = [
        'isDefault'     => 'boolval',
        'CustomerID'    => 'intval'
    ];

    /**
     * @return Customer|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function Customer(): ?Customer
    {
        if (!$this->CustomerID) return null;

        /** @var Customer|null $customer */
        $customer = $this->getClient()->customers->fetch($this->CustomerID);

        return $customer;
    }
}
