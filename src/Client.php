<?php

namespace Lifeboat\SDK;

use Lifeboat\SDK\Exceptions\OAuthException;
use Lifeboat\SDK\Services\Curl;

/**
 * Class Client
 * @package Lifeboat\SDK
 */
class Client {

    const AUTH_DOMAIN   = 'https://accounts.lifeboat.app';
    const TOKEN_URL     = self::AUTH_DOMAIN . '/oauth/api_token';
    const SITES_URL     = self::AUTH_DOMAIN . '/oauth/sites';

    private $_api_key = '';
    private $_api_secret = '';
    private $_access_token;

    public function __construct(string $_api_key, string $_api_secret)
    {
        $this->_api_key = $_api_key;
        $this->_api_secret = $_api_secret;
    }

    /**
     * Tries to fetch an access token from the API based on
     * the auth parameters available to client
     *
     * @return string
     * @throws OAuthException If any error is encountered during OAuth
     */
    public function getAccessToken(): string
    {
        if (!$this->_access_token) {
            $curl = new Curl(self::TOKEN_URL, [
                'api_key'       => $this->getAPIKey(),
                'api_secret'    => $this->getAPISecret()
            ]);
            
            $curl->setMethod('POST');
            $response = $curl->curl();
            
            if (!$response->isValid()) {
                throw new OAuthException($response->getError());
            }
            
            $json = $response->getJSON();
            if (!array_key_exists('access_token', $json)) {
                throw new OAuthException("Access token was not returned by API");   
            }
            
            $this->_access_token = $json['access_token'];
        }

        return $this->_access_token;
    }

    /**
     * Makes a request to the API to refresh the current access token
     * @see Client::getAccessToken()
     *
     * @return $this
     */
    public function refreshAccessToken(): Client
    {
        $this->_access_token = null;
        $this->getAccessToken();
        return $this;
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
}
