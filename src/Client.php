<?php

namespace Lifeboat\SDK;

/**
 * Class Client
 * @package Lifeboat\SDK
 */
class Client {

    const AUTH_DOMAIN = 'https://accounts.lifeboat.app';

    private $_api_key = '';
    private $_api_secret = '';
    private $_app_id = '';
    private $_app_secret = '';
    private $_site_key = '';

    public function __construct(array $config)
    {
        if (array_key_exists('api_key', $config)) $this->_api_key = $config['api_key'];
        if (array_key_exists('api_secret', $config)) $this->_api_secret = $config['api_secret'];
        if (array_key_exists('app_id', $config)) $this->_app_id = $config['app_id'];
        if (array_key_exists('app_secret', $config)) $this->_app_secret = $config['app_secret'];
        if (array_key_exists('site_key', $config)) $this->setSiteKey($config['site_key']);
    }

    /**
     * @return string
     */
    public function getAPIKey(): string
    {
        return $this->_api_key;
    }

    /**
     * @return string
     */
    public function getAPISecret(): string
    {
        return $this->_api_key;
    }

    /**
     * @return string
     */
    public function getAppID(): string
    {
        return $this->_api_key;
    }

    /**
     * @return string
     */
    public function getAppSecret(): string
    {
        return $this->_api_key;
    }

    /**
     * @return string
     */
    public function getSiteKey(): string
    {
        return $this->_site_key;
    }

    /**
     * @param string $site_key
     * @return $this
     */
    public function setSiteKey(string $site_key): Client
    {
        $this->_site_key = $site_key;
        return $this;
    }
}
