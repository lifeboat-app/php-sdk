<?php

namespace Lifeboat\Utils;

/**
 * Class ArrayLib
 * @package Lifeboat\Utils
 */
class ArrayLib {

    /**
     * @param $array
     * @return bool
     */
    public static function is_associative($array): bool
    {
        return !empty($array) && is_array($array) && ($array !== array_values($array));
    }

}
