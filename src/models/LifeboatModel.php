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
        $class = get_called_class();
        throw new BadMethodException("{$class} does have an API service");
    }
}
