<?php

namespace Lifeboat\Models;

/**
 * Class ProductType
 * @package Lifeboat\Models
 *
 * @property string $Type
 * @property int $FacebookCategoryID
 * @property int $GoogleCategoryID
 * @property int $ProductCount
 */
class ProductType extends Model {

    protected static $casting = [
        'FacebookCategoryID'    => 'intval',
        'GoogleCategoryID'      => 'intval',
    ];
}
