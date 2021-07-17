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
    const TOKEN_URL     = '/oauth/api_token';
    const SITES_URL     = '/oauth/sites';

    private $_auth_domain = '';
    private $_api_key = '';
    private $_api_secret = '';
    private $_access_token;

    public function __construct(string $_api_key, string $_api_secret, $_auth_domain = self::AUTH_DOMAIN)
    {
        $this->_api_key = $_api_key;
        $this->_api_secret = $_api_secret;
        $this->_auth_domain = rtrim($_auth_domain, '/');
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
            $curl = new Curl($this->auth_url(self::TOKEN_URL), [
                'api_key'       => $this->getAPIKey(),
                'api_secret'    => $this->getAPISecret()
            ]);

            $curl->setMethod('POST');
            $response = $curl->curl();

            if (!$response->isValid()) {
                throw new OAuthException($response->getRaw());
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
     * @throws OAuthException
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
        return $this->_api_secret;
    }

    /**
     * @param string $path
     * @return string
     */
    private function auth_url(string $path): string
    {
        return $this->_auth_domain . '/' . ltrim($path, '/');
    }
}
