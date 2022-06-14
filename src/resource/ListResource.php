<?php

namespace Lifeboat\Resource;

use Lifeboat\Connector;
use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Models\Model;
use Lifeboat\Factory\ObjectFactory;
use IteratorAggregate;
use Generator;
use ArrayAccess;
use Countable;

/**
 * Class ResourceList
 *
 * This class performs some serious magic.
 *
 * It calls the api in a paginated fashion however, it will keep
 * calling the next page automatically until it meets the end of the list,
 * or if the search index is found.
 *
 * Similar to how an infinite scroll would work, if a REST API version of it would exist.
 *
 * This is done so that we don't overwhelm one API server with humongous requests
 * if the developer needs to access a full list of objects.
 *
 * @package Lifeboat\Resource
 * @property Connector $_client
 */
class ListResource implements IteratorAggregate, ArrayAccess, Countable {

    const PAGE_PARAM    = 'page';
    const LIMIT_PARAM   = 'limit';

    private $_url = '';
    private $_params = [];
    protected $_items = [];
    protected $_max_items = 0;

    private $_page_length;
    private $_client;

    /**
     * ListResource constructor.
     * @param Connector $client
     * @param string $url
     * @param array $params
     * @param int $page_length
     */
    public function __construct(Connector $client, string $url, array $params = [], int $page_length = 20)
    {
        $this->setClient($client);
        $this->setURL($url);
        $this->setParams($params);

        $this->_page_length = $page_length;
    }

    /**
     * @param Connector $client
     * @return $this
     */
    public function setClient(Connector $client): ListResource
    {
        $this->_client = $client;
        return $this;
    }

    /**
     * @return Connector
     */
    public function getClient(): Connector
    {
        return $this->_client;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params = []): ListResource
    {
        $this->_params = $params;
        return $this;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->_params;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setURL(string $url): ListResource
    {
        $this->_url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getURL(): string
    {
        return $this->_url;
    }

    /**
     * @param int $page
     * @return array
     * @throws OAuthException
     * @throws ApiException
     */
    public function getItems(int $page = 1): array
    {
        if (!array_key_exists($page, $this->_items)) {
            $data = $this->getParams();

            $data[self::PAGE_PARAM]     = $page;
            $data[self::LIMIT_PARAM]    = $this->_page_length;

            $response   = $this->getClient()->curl_api($this->getURL(), 'GET', $data);
            $data       = ($response->isValid() && $response->isJSON()) ? $response->getJSON() : [];

            if (empty($data)) throw new ApiException($response->getError());

            $this->_max_items = (int) $data['available_items'];

            if (empty($data['items'])) return $this->_items[$page] = [];

            foreach ($data['items'] as $item) {
                $obj = ObjectFactory::make($this->getClient(), $item);
                if (!$obj) continue;

                $this->_items[$page][] = $obj;
            }
        }

        return $this->_items[$page];
    }

    /**
     * @param mixed $offset
     * @return ObjectResource|null
     */
    public function offsetGet($offset): ?ObjectResource
    {
        foreach ($this as $i => $obj) if ($i === $offset) return $obj;
        return null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        // Do nothing, this is only a reflection object
    }

    /**
     * @return int
     * @throws OAuthException
     */
    public function count(): int
    {
        // Make sure to load the objects first
        $this->getItems(1);

        return $this->_max_items;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        foreach ($this as $i => $obj) if ($offset === $i) return true;
        return false;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        // Do nothing, we cannot modify a reflection object
    }

    /**
     * Traverse the objects in chunks as not to overwhelm the API
     *
     * IMPORTANT:
     * If you're reading this and have no clue what on earth is going on...
     * DON'T TOUCH IT!
     *
     * The person who wrote this enjoys PHP in a really sadistic way,
     * so much so that he even used 1 letter variable names.
     *
     * This was done to ensure you don't touch this function.
     * Just enjoy using it and trust in the magic.
     *
     * @return Generator
     * @throws OAuthException If client has a problem connecting to the API
     */
    public function getIterator(): Generator
    {
        $t = $this->getItems(1);
        $c = $this->count();

        return (function () use ($t, $c) {
            $i = 0;
            $x = 0;

            while ($i < $c) {
                if ($x === 0) {
                    $x = $this->_page_length;
                    $t = $this->getItems((int) floor($i / $this->_page_length) + 1);
                }

                yield $i => $t[$this->_page_length - $x];

                $x -= 1;
                $i ++;
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

    /**
     * @return array
     * @throws OAuthException
     */
    public function toArray(): array
    {
        $arr = [];
        foreach (self::getIterator() as $item) $arr[] = $item;
        return $arr;
    }
}
