<?php

namespace Lifeboat\Services;

use Lifeboat\Resource\ListResource;
use Lifeboat\Resource\SimpleList;

/**
 * Class Pages
 * @package Lifeboat\Services
 */
class Pages extends ApiService {

    /**
     * @return ListResource|SimpleList
     */
    public function all(): ListResource
    {
        return new SimpleList($this->getClient(), 'api/pages/all', []);
    }
}
