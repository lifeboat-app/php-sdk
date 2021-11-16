<?php

namespace Lifeboat\Models;

use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\OAuthException;

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

    protected static array $casting = [
        'isDefault' => 'boolval'
    ];

    /**
     * @return string
     */
    public function model(): string
    {
        return 'Address';
    }

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
        $order = $this->write('api/addresses/address/' . $this->ID);
        return $order;
    }

    /**
     * @TODO
     */
    public function Customer() {}
}
