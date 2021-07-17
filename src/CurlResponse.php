<?php

namespace Lifeboat\SDK;

/**
 * Class CurlResponse
 * 
 * Wrapper class for responses from the API / Oauth service
 * 
 * @package Lifeboat\SDK
 */
class CurlResponse
{

    private $http_code  = 0;
    private $result     = '';

    public function __construct($http_code, $result)
    {
        $this->http_code    = $http_code;
        $this->result       = $result;
    }

    public function isValid()
    {
        return $this->http_code > 199 && $this->http_code < 300;
    }

    /**
     * @return int
     */
    public function getHTTPCode()
    {
        return $this->http_code;
    }

    /**
     * @return array|null
     */
    public function getJSON(): ?array
    {
        $data = json_decode($this->result, true);
        if (!is_array($data)) return null;
        
        return $data;
    }

    /**
     * @return bool
     */
    public function isJSON(): bool
    {
        return !is_null($this->getJSON());
    }

    public function getError()
    {
        return (count($this->getErrors())) ? $this->getErrors()[0]['error'] : $this->getRaw();
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return ($this->isJSON()) ? $this->getJSON()->errors : [['error' => $this->getRaw(), 'field' => '']];
    }

    public function getRaw()
    {
        return $this->result;
    }
}