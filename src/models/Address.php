<?php

namespace Lifeboat\Models;

use Lifeboat\Resource\ListResource;

/**
 * Class Address
 * @package Lifeboat\Models
 */
class Address extends Model {

    public function all(): ListResource
    {
        return new ListResource($this->getClient(), );
    }

}
