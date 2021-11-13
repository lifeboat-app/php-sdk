<?php

namespace Lifeboat\Resource;

use Lifeboat\Connector;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Services\ObjectFactory;
use IteratorAggregate;
use Generator;

/**
 * Class ResourceList
 * @package Lifeboat\Resource
 */
class ListResource extends ApiResource implements IteratorAggregate {

    const PAGE_LENGTH   = 20;
    const PAGE_PARAM    = 'page';
    const LIMIT_PARAM   = 'limit';

    private string $_url;
    private array $_params;
    private array $_items;
    private int $_max_items;

    /**
     * ListResource constructor.
     * @param Connector $client
     * @param string $url
     * @param array $params
     */
    public function __construct(Connector $client, string $url, array $params = [])
    {
        parent::__construct($client);
        $this->setURL($url);
        $this->setParams($params);
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
     */
    public function getItems(int $page = 1): array
    {
        if (!array_key_exists($page, $this->_items)) {
            $response = $this->getClient()->curl_api($this->getURL(), 'GET', [
                self::PAGE_PARAM    => $page,
                self::LIMIT_PARAM   => self::PAGE_LENGTH
            ]);

            $data = ($response->isValid() && $response->isJSON()) ? $response->getJSON() : [];
            $this->_max_items = $data['available_items'];

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
     * @throws OAuthException
     */
    public function offsetGet($offset): ?ObjectResource
    {
        return $this->getItems()[$offset] ?? null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @throws OAuthException
     */
    public function offsetSet($offset, $value)
    {
        $this->getItems()[$offset] = $value;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->_max_items;
    }

    /**
     * @param mixed $offset
     * @return bool
     * @throws OAuthException
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->getItems());
    }

    /**
     * @param mixed $offset
     * @throws OAuthException
     */
    public function offsetUnset($offset)
    {
        unset($this->getItems()[$offset]);
    }

    /**
     * @return Generator
     */
    public function getIterator(): Generator
    {
        return (function () {
            $i = 0;
            $x = 0;
            $m = $this->count();

            while ($i < $m) {
                if ($x === 0) {
                    $x = self::PAGE_LENGTH;
                    $t = $this->getItems(ceil($i / self::PAGE_LENGTH));
                }

                yield $i => $t[self::PAGE_LENGTH - $x];

                $x -= 1;
            }
        })();
    }
}
