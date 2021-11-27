<?php

namespace Lifeboat;

/**
 * Class CurlResponse
 *
 * Wrapper class for responses from the API / Oauth service
 *
 * @package Lifeboat
 */
class CurlResponse
{

    private $http_code;
    private $result;

    public function __construct(int $http_code, string $result)
    {
        $this->http_code    = $http_code;
        $this->result       = $result;
    }

    public function isValid(): bool
    {
        return $this->http_code > 199 && $this->http_code < 300;
    }

    /**
     * @return int
     */
    public function getHTTPCode(): int
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

    /**
     * @return string
     */
    public function getError(): string
    {
        return (count($this->getErrors())) ? $this->getErrors()[0]['error'] : $this->getRaw();
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return ($this->isJSON()) ? $this->getJSON()['errors'] : [['error' => $this->getRaw(), 'field' => '']];
    }

    /**
     * @return string
     */
    public function getRaw(): string
    {
        return $this->result;
    }
}
