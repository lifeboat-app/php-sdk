<?php

namespace Lifeboat\Models;

use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\InvalidArgumentException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Resource\ListResource;

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
 * @property string $Notes
 * @property string $Latitude
 * @property string $Longitude
 * @property string $Email
 * @property string $Tel
 * @property string $VATNo
 * @property int $isDefault
 * @property string $Country
 * @property string $Subdivision
 * @property int $CustomerID
 */
class Address extends Model {

    /**
     * @param int $id
     * @return Model|null
     * @throws ApiException
     * @throws OAuthException
     * @throws InvalidArgumentException If param $id is less than 1
     */
    public function retrieve(int $id = -1): ?Model
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("Address::fetch() expects parameter 1 to be a positive integer");
        }

        return $this->curl_for_model('api/addresses/address/' . $id);
    }

    /**
     * @return ListResource
     */
    public function all(): ListResource
    {
        return new ListResource($this->getClient(), 'api/addresses/all', [], 20);
    }

    /**
     * @param string $search
     * @return ListResource
     */
    public function search(string $search): ListResource
    {
        $all = $this->all();
        return $all->setParams(['search' => $search]);
    }

    /**
     * @return string
     */
    protected function getSaveURL(): string
    {
        return 'api/addresses/address/' . $this->ID;
    }
}
