<?php

namespace Lifeboat\Utils;

use Lifeboat\CurlResponse;
use Lifeboat\Exceptions\InvalidArgumentException;
use LogicException;

/**
 * Class Curl
 * @package Lifeboat
 */
class Curl {

    const ALLOWED_METHODS   = ['GET', 'POST', 'DELETE', 'PUT'];
    const USER_AGENT        = 'LifeboatSDK/curl-service';

    private static $_cache = [];

    private $_method = 'GET';
    private $_url    = '';
    private $_data    = [];
    private $_isfile   = false;
    private $_headers = [
        'Content-Type'      => 'application/x-www-form-urlencoded',
        'X-Requested-By'    => self::USER_AGENT
    ];
    private $_enable_cache = false;

    /**
     * Curl constructor.
     *
     * @param string $url
     * @param array $data
     * @param array $headers
     *
     * @throws LogicException
     */
    public function __construct(string $url, array $data = [], array $headers = [])
    {
        $this->setURL($url);
        foreach ($data as $name => $value)      $this->addDataParam($name, $value);
        foreach ($headers as $name => $value)   $this->addHeader($name, $value);
    }

    /**
     * @param string $url
     * @return Curl
     */
    public function setURL(string $url): Curl
    {
        $this->_url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getURL(): string
    {
        return $this->_url;
    }

    /**
     * @param string $method
     * @return $this
     * @throws InvalidArgumentException If $method specified is invalid
     */
    public function setMethod(string $method = 'GET'): Curl
    {
        $method = strtoupper($method);

        if (!in_array($method, self::ALLOWED_METHODS)) {
            throw new InvalidArgumentException("HTTP Method '{$method}' is not allowed");
        }

        $this->_method = $method;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->_method;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function addDataParam(string $name, $value): Curl
    {
        $this->_data[$name] = $value;
        return $this;
    }

    public function removeDataParam(string $name): Curl
    {
        if (array_key_exists($name, $this->_data)) unset($this->_data[$name]);
        return $this;
    }

    /**
     * @return array
     */
    public function getDataParams(): array
    {
        return $this->_data;
    }

    /**
     * @param string $name
     * @param string $value
     * @return Curl
     */
    public function addHeader(string $name, string $value): Curl
    {
        $this->_headers[$name] = $value;
        return $this;
    }

    /**
     * @param string $name
     * @return Curl
     */
    public function removeHeader(string $name): Curl
    {
        if (array_key_exists($name, $this->_headers)) unset($this->_headers[$name]);
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->_headers;
    }

    public function setIsFileUpload(bool $is_file): Curl
    {
        $this->addHeader('Content-Type', 'multipart/form-data');
        $this->_isfile = $is_file;
        return $this;
    }

    public function isFileUpload(): bool
    {
        return $this->_isfile;
    }

    /**
     * @param bool $switch
     * @return $this
     */
    public function cacheRequests(bool $switch): Curl
    {
        $this->_enable_cache = $switch;
        return $this;
    }

    /**
     * @return CurlResponse
     */
    public function curl(): CurlResponse
    {
        $post_data      = null;
        $send_headers   = [];
        $request_uri    = $this->getURL();

        //  Headers
        foreach ($this->getHeaders() as $k => $v) $send_headers[] = "{$k}: {$v}";

        // Request Data
        switch ($this->getMethod()) {
            case 'GET':
            case 'DELETE':
                foreach ($this->getDataParams() as $name => $value) $request_uri = URL::setGetVar($name, $value, $request_uri);
                break;

            case 'POST':
                $post_data = ($this->isFileUpload()) ? $this->getDataParams() : http_build_query($this->getDataParams());
                break;
            case 'PUT':
                $post_data = http_build_query($this->getDataParams());
                break;
        }

        if ($this->_enable_cache && $this->getMethod() === 'GET') {
            $cache_key = urlencode($request_uri) . implode(',', $send_headers);
            if (array_key_exists($cache_key, self::$_cache)) {
                return self::$_cache[$cache_key];
            }
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request_uri);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->getMethod());
        curl_setopt($ch, CURLOPT_USERAGENT, self::USER_AGENT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);

        if (!empty($send_headers))  curl_setopt($ch, CURLOPT_HTTPHEADER, $send_headers);
        if (!is_null($post_data))   {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }

        $result     = curl_exec($ch);
        $http_code  = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $response = new CurlResponse((int) $http_code, (string) $result);
        if ($this->_enable_cache && isset($cache_key)) self::$_cache[$cache_key] = $response;

        return $response;
    }

    /**
     * @see Curl::curl()
     *
     * @return CurlResponse
     */
    public function curl_json(): CurlResponse
    {
        $this->addHeader('Accept', 'application/json');
        return $this->curl();
    }
}
