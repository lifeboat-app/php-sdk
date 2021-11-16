<?php

namespace Lifeboat\Services;

use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\BadMethodException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Models\Address;
use Lifeboat\Resource\ListResource;

/**
 * Class Addresses
 * @package Lifeboat\Services
 */
class Addresses extends ApiService {

    /**
     * @param int $id
     * @return Address|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function fetch(int $id): ?Address
    {
        /** @var Address|null $address */
        $address = $this->_get('api/addresses/address' . $id);
        return $address;
    }

    /**
     * @param array $data
     * @return Address|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function create(array $data): ?Address
    {
        /** @var Address|null $address */
        $address = $this->_post('api/addresses/address', $data);
        return $address;
    }

    /**
     * @param int $id
     * @param array $data
     * @return Address|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function update(int $id, array $data): ?Address
    {
        /** @var Address|null $address */
        $address = $this->_post('api/addresses/address/' . $id, $data);
        return $address;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        throw new BadMethodException("Addresses cannot be deleted");
    }

    /**
     * @param string $search
     * @return ListResource
     */
    public function all(string $search = ''): ListResource {
        return new ListResource($this->getClient(), 'api/addresses/all', ['search' => $search], 20);
    }
}
