<?php

namespace Lifeboat;

use Lifeboat\Exceptions\BadMethodException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Models\Model;
use Lifeboat\Services\ObjectFactory;
use Lifeboat\Utils\Curl;

/**
 * Class Connector
 * @package Lifeboat
 *
 * @property string $_auth_domain
 * @property string|null $_access_token
 * @property string $_site_key
 * @property Model $address
 */
abstract class Connector {

    const AUTH_DOMAIN   = 'https://accounts.lifeboat.app';
    const TOKEN_URL     = '/oauth/token';
    const SITES_URL     = '/oauth/sites';

    protected string $_auth_domain = 'https://accounts.lifeboat.app';
    protected string $_access_token;
    protected string $_site_key;
    protected string $_host;

    /**
     * @return string
     *
     * @throws OAuthException
     */
    abstract public function getAccessToken(): string;

    /**
     * @param string $service
     * @return Model
     * @throws BadMethodException
     */
    public function __get(string $service): Model
    {
        $obj = ObjectFactory::create($this, $service);
        if (!$obj) throw new BadMethodException("Service for `{$service}` does not exist");

        return $obj;
    }

    /**
     * @return array
     * @throws OAuthException
     */
    public function getSites(): array
    {
        $curl = new Curl($this->auth_url(self::SITES_URL), [
            'access_token' => $this->getAccessToken()
        ]);

        $curl->setMethod('POST');
        $response = $curl->curl();

        if (!$response->isValid()) {
            throw new OAuthException($response->getRaw());
        }

        return $response->getJSON();
    }

    /**
     * Makes a request to the API to refresh the current access token
     * @see Client::getAccessToken()
     *
     * @return $this
     * @throws OAuthException
     */
    public function refreshAccessToken(): Connector
    {
        $this->_access_token = null;
        $this->getAccessToken();
        return $this;
    }

    /**
     * @param string $host
     * @param string $site_key
     * @return $this
     */
    public function setActiveSite(string $host, string $site_key): Connector
    {
        $this->_host        = $host;
        $this->_site_key    = $site_key;

        return $this;
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $data
     * @param array $headers
     * @return CurlResponse
     * @throws OAuthException
     */
    public function curl_api(string $url, string $method = 'GET', array $data = [], array $headers = []): CurlResponse
    {
        $curl = new Curl($url, $data, $headers);

        $curl->setMethod($method);
        $curl->addHeader('access-token', $this->getAccessToken());
        $curl->addHeader('site-key', $this->_site_key);
        $curl->addHeader('Host', $this->_host);
        $curl->addHeader('Accept', 'application/json');

        return $curl->curl_json();
    }

    /**
     * @param string $path
     * @return string
     */
    protected function auth_url(string $path): string
    {
        return $this->_auth_domain . '/' . ltrim($path, '/');
    }
}
