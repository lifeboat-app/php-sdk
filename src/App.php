<?php

namespace Lifeboat;

use Lifeboat\Utils\Curl;
use Lifeboat\Utils\URL;
use Lifeboat\Utils\Utils;

/**
 * Class App
 * @package Lifeboat
 *
 * @property string $_app_id
 * @property string $_app_secret
 * @property string $_app_challenge
 * @property string $_code
 */
class App extends Connector {

    const CODE_URL = '/oauth/code';

    private $_app_id        = '';
    private $_app_secret    = '';
    private $_api_challenge = '';
    private $_code          = '';

    public function __construct(string $_app_id, string $_app_secret, $_auth_domain = self::AUTH_DOMAIN)
    {
        $this->_app_id      = $_app_id;
        $this->_app_secret  = $_app_secret;
        $this->_auth_domain = rtrim($_auth_domain, '/');
    }

    /**
     * @param string $challenge
     * @return $this
     */
    public function setAPIChallenge(string $challenge): App
    {
        $this->_api_challenge = $challenge;
        return $this;
    }

    /**
     * @return string
     */
    public function getAPIChallenge(): string
    {
        if (!$this->_api_challenge) $this->_api_challenge = Utils::create_random_string(128);
        return $this->_api_challenge;
    }

    /**
     * @param string $process_url
     * @param string $error_url
     * @return string
     */
    public function getAuthURL(string $process_url, string $error_url): string
    {
        $url    = URL::setGetVar('app_id', $this->getAppID(), $this->auth_url(self::CODE_URL));
        $url    = URL::setGetVar('process_url', urlencode($process_url), $url);
        $url    = URL::setGetVar('error_url', urlencode($error_url), $url);

        return URL::setGetVar('challenge', Utils::pack($this->getAPIChallenge()), $url);
    }

    /**
     * @param string $secret
     * @param string $code
     * @return string
     */
    public function fetchAccessToken(string $secret, string $code): string
    {
        $curl = new Curl($this->auth_url(self::TOKEN_URL), [
            'challenge'     => $this->getAPIChallenge(),
            'code'          => $code,
            'app_secret'    => $secret
        ]);

        $curl->setMethod('POST');
        $response = $curl->curl();
        $json = $response->getJSON();

        if (!$response->isValid() || !$json || !array_key_exists('access_token', $json)) {
            return $json['access_token'];
        } else {
            return '';
        }
    }

    /**
     * @return string
     */
    public function getAppID(): string
    {
        return $this->_app_id;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        if (!$this->_access_token) {
            $this->_access_token = $this->fetchAccessToken($this->_app_secret, $this->_code);
        }

        return $this->_access_token;
    }
}
