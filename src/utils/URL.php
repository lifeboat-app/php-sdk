<?php

namespace Lifeboat\Utils;

use Lifeboat\Exceptions\InvalidArgumentException;

/**
 * Class URL
 * @package Lifeboat\Utils
 */
class URL {

    /**
     * @param string $key
     * @param string $value
     * @param string $url
     * @return string
     * @throws InvalidArgumentException If URL is malformed
     */
    public static function setGetVar(string $key, string $value, string $url): string
    {
        if (!self::is_absolute_url($url)) {
            $url = 'http://dummy.com/' . ltrim($url, '/');
        }

        // try to parse uri
        $parts = parse_url($url);
        if (empty($parts)) {
            throw new InvalidArgumentException("Can't parse URL: " . $url);
        }

        // Parse params and add new variable
        $params = [];
        if (array_key_exists('query', $parts)) {
            parse_str($parts['query'], $params);
        }

        $params[$key] = $value;

        // Generate URI segments and formatting
        $scheme = (array_key_exists('scheme', $parts)) ? $parts['scheme'] : 'http';
        $user = (array_key_exists('user', $parts)) ? $parts['user'] : '';
        $port = (array_key_exists('port', $parts) && $parts['port']) ? ':' . $parts['port'] : '';

        if ($user != '') {
            // format in either user:pass@host.com or user@host.com
            $user .= (array_key_exists('pass', $parts) && $parts['pass']) ? ':' . $parts['pass'] . '@' : '';
        }

        // handle URL params which are existing / new
        $params = ($params) ? '?' . http_build_query($params) : '';

        // Recompile URI segments
        $newUri = $scheme . '://' . $user . $parts['host'] . $port . $parts['path'] . $params;

        return str_replace('http://dummy.com/', '', $newUri);
    }

    /**
     * @param string $url
     * @return bool
     */
    public static function is_absolute_url(string $url): bool
    {
        // Strip off the query and fragment parts of the URL before checking
        if (($queryPosition = strpos($url, '?')) !== false) {
            $url = substr($url, 0, $queryPosition - 1);
        }
        if (($hashPosition = strpos($url, '#')) !== false) {
            $url = substr($url, 0, $hashPosition - 1);
        }
        $colonPosition = strpos($url, ':');
        $slashPosition = strpos($url, '/');
        return (
            // Base check for existence of a host on a compliant URL
            parse_url($url, PHP_URL_HOST)
            // Check for more than one leading slash without a protocol.
            // While not a RFC compliant absolute URL, it is completed to a valid URL by some browsers,
            // and hence a potential security risk. Single leading slashes are not an issue though.
            || preg_match('%^\s*/{2,}%', $url)
            || (
                // If a colon is found, check if it's part of a valid scheme definition
                // (meaning its not preceded by a slash).
                $colonPosition !== false
                && ($slashPosition === false || $colonPosition < $slashPosition)
            )
        );
    }
}
