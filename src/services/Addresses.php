<?php

namespace Lifeboat\Services;

use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\InvalidArgumentException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Models\Address;
use Lifeboat\Models\Order;
use Lifeboat\Resource\ListResource;

/**
 * Class Addresses
 * @package Lifeboat\Services
 */
class Addresses extends ApiService {

    /**
     * @param int $id
     * @return Order|null
     * @throws ApiException
     * @throws OAuthException
     * @throws InvalidArgumentException If param $id is less than 1
     */
    public function fetch(int $id = -1): ?Address
    {
        $class = get_called_class();
        if ($id <= 0) {
            throw new InvalidArgumentException("{$class}::fetch() expects parameter 1 to be a positive integer");
        }

        /** @var Address|null $order */
        $order = $this->retrieve('api/addresses/address/' . $id);
        return $order;
    }

    /**
     * @param string $search
     * @return ListResource
     */
    public function all(string $search = ''): ListResource {
        return new ListResource($this->getClient(), 'api/addresses/all', ['search' => $search], 20);
    }
}
