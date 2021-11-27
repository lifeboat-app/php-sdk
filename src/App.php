<?php

namespace Lifeboat;

use Lifeboat\Exceptions\InvalidArgumentException;
use Lifeboat\Utils\Curl;
use Lifeboat\Utils\URL;
use Lifeboat\Utils\Utils;

/**
 * Class App
 * @package Lifeboat
 */
class App extends Connector {

    const CODE_URL = '/oauth/code';

    const ACCESS_TOKEN_PARAM    = 'lb_app_access_token';
    const API_CHALLENGE_PARAM   = 'lb_app_api_challenge';

    private string $_app_id;
    private string $_app_secret;
    private string $_api_challenge = '';

    public function __construct(string $app_id, string $app_secret, $auth_domain = self::AUTH_DOMAIN)
    {
        if (!$app_id || !$app_secret) {
            throw new InvalidArgumentException(static::class . "expects an app_id and app_secret");
        }

        $this->setAppID($app_id);
        $this->setAppSecret($app_secret);
        $this->_auth_domain = rtrim($auth_domain, '/');

        if (session_status() === PHP_SESSION_ACTIVE) {
            $this->setAccessToken($_SESSION[self::ACCESS_TOKEN_PARAM] ?? '');
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
     * @param string $id
     * @return $this
     */
    public function setAppID(string $id): App
    {
        $this->_app_id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getAppSecret(): string
    {
        return $this->_app_secret;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setAccessToken(string $token): App
    {
        $this->_access_token = $token;

        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION[self::ACCESS_TOKEN_PARAM] = $this->_access_token;
        }

        return $this;
    }

    /**
     * @param string $secret
     * @return $this
     */
    public function setAppSecret(string $secret): App
    {
        $this->_app_secret = $secret;
        return $this;
    }

    /**
     * @param string $challenge
     * @return $this
     */
    public function setAPIChallenge(string $challenge): App
    {
        $this->_api_challenge = $challenge;

        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION[self::API_CHALLENGE_PARAM] = $this->_api_challenge;
        }

        return $this;
    }

    /**
     * @param bool $check_session
     * @return string
     */
    public function getAPIChallenge(bool $check_session = true): string
    {
        if ($this->_api_challenge) return $this->_api_challenge;

        if ($check_session && session_status() === PHP_SESSION_ACTIVE) {
            $this->setAPIChallenge($_SESSION[self::API_CHALLENGE_PARAM] ?? '');
            return $this->getAPIChallenge(false);
        }

        if (!$this->_api_challenge) {
            $this->_api_challenge = Utils::create_random_string(128);
            $this->setAPIChallenge($this->_api_challenge);
        }

        return $this->_api_challenge;
    }

    /**
     * @param string $process_url
     * @param string $error_url
     * @param string $challenge
     * @return string
     */
    public function getAuthURL(string $process_url, string $error_url, string $challenge): string
    {
        $url    = URL::setGetVar('app_id', $this->getAppID(), $this->auth_url(self::CODE_URL));
        $url    = URL::setGetVar('process_url', urlencode($process_url), $url);
        $url    = URL::setGetVar('error_url', urlencode($error_url), $url);

        return URL::setGetVar('challenge', Utils::pack($challenge), $url);
    }

    /**
     * @param string $code
     * @return string
     */
    public function fetchAccessToken(string $code): string
    {
        $curl = new Curl($this->auth_url('/oauth/token'), [
            'challenge'     => $this->getAPIChallenge(),
            'code'          => $code,
            'app_secret'    => $this->getAppSecret()
        ]);

        $curl->setMethod('POST');
        $response = $curl->curl();
        $json = $response->getJSON();

        if (!$response->isValid() || !$json || !array_key_exists('access_token', $json)) {
            return '';
        } else {
            $this->setAccessToken($json['access_token']);
            return $this->getAccessToken(false);
        }
    }

    /**
     * @param bool $check_session
     * @return string
     */
    public function getAccessToken(bool $check_session = true): string
    {
        if ($this->_access_token) return $this->_access_token;

        if ($check_session && session_status() === PHP_SESSION_ACTIVE) {
            $this->setAccessToken($_SESSION[self::ACCESS_TOKEN_PARAM] ?? '');
            return $this->getAccessToken(false);
        }

        return '';
    }
}
