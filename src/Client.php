<?php

namespace Lifeboat;

use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Utils\Curl;

/**
 * Class Client
 * @package Lifeboat
 *
 * @property string $_api_key
 * @property string $_api_secret
 */
class Client extends Connector {

    private string $_api_key;
    private string $_api_secret;

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
}
