<?php

namespace Lifeboat\Models;

use Lifeboat\Exceptions\BadMethodException;
use Lifeboat\Services\ApiService;

/**
 * Class LifeboatModel
 * @package Lifeboat\Models
 */
class LifeboatModel extends Model {

    /**
     * @return ApiService
     * @throws BadMethodException
     */
    public function getService(): ApiService
    {
        throw new BadMethodException("LifeboatModel does have an API service");
    }
}
