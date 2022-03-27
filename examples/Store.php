<?php

namespace Examples\Models;

/**
 * Class Store
 * @package Examples\Models
 *
 * This class represents who you may store the lifeboat store information
 * that is returned from the auth service.
 *
 * This information will allow you to make additional requests to the
 * Lifeboat API without the need to ask for user interaction.
 *
 * This model assumes that it exists in a database table
 * containing the following columns:
 * - CHAR(16) SiteKey (16 alphanumeric characters)
 * - VARCHAR SiteHost
 */
class Store extends Object {

    // These parameters should reflect table columns in a database
    private $SiteKey    = '';
    private $SiteHost   = '';

    public function getSiteKey(): string
    {
        return $this->SiteKey;
    }

    public function getSiteHost(): string
    {
        return $this->SiteHost;
    }

    public static function find_or_make(string $site_key, string $host = ''): Store
    {
        // Perform whatever logic is necessary to find the object in your DB
        $find = self::get()->find('SiteKey', $site_key);
        if (!$find) {
            $find = new self();
            $find->SiteKey = $site_key;
        }

        // Always check if the host has changed.
        // Users may change their master domain and thus changing the host
        if ($host && $host !== $find->SiteHost) {
            $find->SiteHost = $host;
        }

        // Write any changes to the database
        $find->save();

        // Return this object
        return $find;
    }
}
