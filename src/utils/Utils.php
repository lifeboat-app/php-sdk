<?php

namespace Lifeboat\SDK\Utils;

class Utils {

    /**
     * @param int $length
     * @param string $characters
     * @return string
     */
    public static function create_random_string(
        int $length = 24,
        string $characters = '0123456789abcdefghijklmnopqrstuvwxyz'
    ): string {
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * @param string $str
     * @return string
     */
    public static function pack(string $str): string
    {
        return rtrim(strtr(base64_encode(pack('H*',  hash('sha256', $str))), '+/', '-_'), '=');
    }
}
