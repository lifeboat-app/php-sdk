<?php

namespace Lifeboat\Models;

use Lifeboat\Resource\ListResource;
use Lifeboat\Resource\ObjectResource;

/**
 * Class Model
 * @package Lifeboat\Models
 */
abstract class Model extends ObjectResource {

    abstract public function all(): ListResource;

}
