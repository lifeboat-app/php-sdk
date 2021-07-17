<?php

namespace Lifeboat\SDK;

use GuzzleHttp\Promise\Promise;
use Lifeboat\SDK\Exceptions\OAuthException;
use Lifeboat\SDK\Services\Curl;
use Lifeboat\SDK\Utils\URL;
use Lifeboat\SDK\Utils\Utils;

/**
 * Class App
 * @package Lifeboat\SDK
 */
class App {

    const AUTH_DOMAIN   = 'https://accounts.lifeboat.app';
    const CODE_URL      = '/oauth/code';
    const TOKEN_URL     = '/oauth/token';
    const SITES_URL     = '/oauth/sites';

    private $_auth_domain = '';
    private $_app_id = '';
    private $_api_challenge = '';

    public function __construct(string $_app_id, $_auth_domain = self::AUTH_DOMAIN)
    {
        $this->_app_id = $_app_id;
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
     * @param Promise $promise
     * @return $this
     */
    public function getAccessToken(string $secret, string $code, Promise $promise): App
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
            $promise->reject($response->getRaw());
        } else {
            $promise->resolve($json['access_token']);
        }

        return $this;
    }

    /**
     * @param string $access_token
     * @return array
     * @throws OAuthException
     */
    public function getSites(string $access_token): array
    {
        $curl = new Curl($this->auth_url(self::SITES_URL), [
            'access_token' => $access_token
        ]);

        $curl->setMethod('POST');
        $response = $curl->curl();

        if (!$response->isValid()) {
            throw new OAuthException($response->getRaw());
        }

        return $response->getJSON();
    }

    /**
     * @return string
     */
    public function getAppID(): string
    {
        return $this->_app_id;
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
