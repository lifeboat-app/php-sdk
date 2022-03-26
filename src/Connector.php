<?php

namespace Lifeboat;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'functions.php';

use Lifeboat\Exceptions\BadMethodException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Factory\ServiceFactory;
use Lifeboat\Services\ApiService;
use Lifeboat\Utils\Curl;
use Lifeboat\Utils\URL;

/**
 * Class Connector
 * @package Lifeboat
 *
 * @property string $_auth_domain
 * @property string|null $_access_token
 * @property string $_site_key
 *
 * // Services
 * @property \Lifeboat\Services\Orders $orders
 * @property \Lifeboat\Services\Addresses $addresses
 * @property \Lifeboat\Services\Customers $customers
 * @property \Lifeboat\Services\Collections $collections
 * @property \Lifeboat\Services\Pages $pages
 * @property \Lifeboat\Services\CustomPages $custom_pages
 * @property \Lifeboat\Services\DeliveryZones $delivery_zones
 * @property \Lifeboat\Services\TaxCodes $tax_codes
 * @property \Lifeboat\Services\Locations $locations
 * @property \Lifeboat\Services\Media $media
 * @property \Lifeboat\Services\Products $products
 */
abstract class Connector {

    const AUTH_DOMAIN   = 'https://accounts.lifeboat.app';
    const SITES_URL     = '/oauth/sites';

    const ACTIVE_HOST_PARAM = 'lb_active_host';
    const ACTIVE_KEY_PARAM  = 'lb_active_site_key';

    protected $_auth_domain = 'https://accounts.lifeboat.app';
    protected $_access_token = '';
    protected $_site_key = '';
    protected $_host = '';

    /**
     * @return string
     *
     * @throws OAuthException
     */
    abstract public function getAccessToken(): string;

    /**
     * @param string $service
     * @return ApiService|null
     * @throws BadMethodException
     */
    public function __get(string $service): ?ApiService
    {
        $obj = ServiceFactory::inst($this, $service);
        if (!$obj) throw new BadMethodException("Service for `{$service}` does not exist");

        return $obj;
    }

    /**
     * @return string
     */
    public function getAuthDomain(): string
    {
        return $this->_auth_domain;
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

        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION[self::ACTIVE_HOST_PARAM]  = $this->_host;
            $_SESSION[self::ACTIVE_KEY_PARAM]   = $this->_site_key;
        }

        return $this;
    }

    /**
     * @param bool $check_session
     * @return array|null
     */
    public function getActiveSite(bool $check_session = true): ?array
    {
        if ($this->_host && $this->_site_key) {
            return ['host' => $this->_host, 'site_key' => $this->_site_key];
        }

        if ($check_session && session_status() === PHP_SESSION_ACTIVE) {
            $this->setActiveSite(
                $_SESSION[self::ACTIVE_HOST_PARAM] ?? '',
                $_SESSION[self::ACTIVE_KEY_PARAM] ?? ''
            );

            return $this->getActiveSite(false);
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getHost(): ?string
    {
        if (!$this->getActiveSite(true)) return null;
        return $this->_host;
    }

    /**
     * @return string|null
     */
    public function getSiteKey(): ?string
    {
        if (!$this->getActiveSite(true)) return null;
        return $this->_site_key;
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
        $url = URL::is_absolute_url($url) ? $url
            : 'https://' . rtrim($this->getHost(), '/') . '/' . ltrim($url, '/');

        $curl = new Curl($url, $data, $headers);

        $curl->setMethod($method);
        $curl->addHeader('Accept', 'application/json');
        $curl->addHeader('Host', $this->getHost());

        foreach ($this->getAuthHeaders() as $header => $value) {
            if ($value) $curl->addHeader($header, $value);
        }

        return $curl->curl_json();
    }

    /**
     * @return array
     * @throws OAuthException
     */
    public function getAuthHeaders(): array
    {
        return [
            'access-token'  => $this->getAccessToken(),
            'site-key'      => $this->getSiteKey()
        ];
    }

    /**
     * @param string $path
     * @return string
     */
    protected function auth_url(string $path): string
    {
        return rtrim($this->_auth_domain, '/') . '/' . ltrim($path, '/');
    }
}
