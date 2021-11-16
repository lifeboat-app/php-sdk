<?php

namespace Lifeboat\Resource;

use Lifeboat\Connector;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Models\Model;
use Lifeboat\Factory\ObjectFactory;
use Generator;

/**
 * Class SimpleList
 *
 * @package Lifeboat\Resource
 * @property Connector $_client
 */
class SimpleList extends ListResource {

    /**
     * ListResource constructor.
     * @param Connector $client
     * @param string $url
     * @param array $params
     */
    public function __construct(Connector $client, string $url, array $params = [])
    {
        parent::__construct($client, $url, $params, 0);
        $this->_items = ['_fetch_'];
    }

    /**
     * @param int $page
     * @return array
     * @throws OAuthException
     */
    public function getItems(int $page = 1): array
    {
        if ($this->_items[0] === '_fetch_') {
            $this->_items = [];
            $response = $this->getClient()->curl_api($this->getURL(), 'GET', $this->getParams());
            $list = ($response->isValid() && $response->isJSON()) ? $response->getJSON() : [];

            foreach ($list as $item) {
                $obj = ObjectFactory::make($this->getClient(), $item);
                if (!$obj) continue;

                $this->_items[] = $obj;
            }

            $this->_max_items = count($this->_items);
        }

        return $this->_items;
    }

    /**
     * @return Generator
     * @throws OAuthException If client has a problem connecting to the API
     */
    public function getIterator(): Generator
    {
        $t = $this->getItems();
        $c = $this->count();

        return (function () use ($t, $c) {
            $i = 0;

            while ($i < $c) {
                yield $i => $t[$i];
            }
        })();
    }

    /**
     * @return Model|null
     */
    public function first(): ?Model
    {
        foreach ($this as $obj) return $obj;
    }
}
