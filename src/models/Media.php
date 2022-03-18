<?php

namespace Lifeboat\Models;

use Lifeboat\Services\ApiService;

/**
 * Class Media
 * @package Lifeboat\Models
 *
 * @todo Implement Video object
 *
 * @property string $Name
 * @property string $Title
 * @property string $Link
 * @property int $Size
 * @property string $Type
 */
class Media extends Model {

    public function getService(): ApiService
    {
        return new \Lifeboat\Services\Media($this->getClient());
    }

}
