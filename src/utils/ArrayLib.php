<?php

namespace Lifeboat\Utils;

/**
 * Class ArrayLib
 * @package Lifeboat\Utils
 */
class ArrayLib {

    /**
     * @param array $array
     * @return bool
     */
    public static function is_associative(array $array): bool
    {
        return is_array($array) && !empty($array) && is_array($array) && ($array !== array_values($array));
    }

}
