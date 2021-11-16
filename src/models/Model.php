<?php

namespace Lifeboat\Models;

use Lifeboat\Connector;
use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\InvalidArgumentException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Resource\ObjectResource;
use Lifeboat\Factory\ObjectFactory;
use Lifeboat\Utils\ArrayLib;

/**
 * Class Model
 * @package Lifeboat\Models
 *
 * @property int $ID
 */
abstract class Model extends ObjectResource {

    abstract public function save(): ?Model;
    abstract public function model(): string;

    public function __construct(Connector $client, array $_object_data = [])
    {
        if (!ArrayLib::is_associative($_object_data)) {
            throw new InvalidArgumentException("Model::__construct() expects an associative array");
        }

        parent::__construct($client, $_object_data);

        $model = $this->model();

        // Mutate objects if needs be
        foreach ($this->toArray() as $field => $value) {
            if (is_array($value)) {
                if (ArrayLib::is_associative($value)) {
                    // Model or Object
                    if (!array_key_exists('field_name', $value)) {
                        $this->$field = ObjectFactory::make($client, $value);
                    } else {
                        // Has One Relation - Deprecated
                        $name = $value['field_name'];
                        $this->$name    = $value['field_value'];
                        $this->$field   = ObjectFactory::make($client, $value['object_data']);
                    }
                } else {
                    // HasMany / ManyMany Relation
                    $list = [];
                    foreach ($value as $item) {
                        if (ArrayLib::is_associative($item)) {
                            $list[] = ObjectFactory::make($client, $item);
                        } else {
                            $list[] = $item;
                        }
                    }

                    $this->$field = $list;
                }
            }
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return iterator_to_array($this->getIterator());
    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
        return $this->ID > 0;
    }

    /**
     * @param string $url
     * @return Model|null
     * @throws ApiException If request/response is invalid
     * @throws OAuthException If client has a problem connecting to API
     */
    protected function write(string $url): ?Model
    {
        $curl = $this->getClient()->curl_api($url, 'POST', $this->toArray());

        if ($curl->isValid() && $curl->isJSON()) {
            /** @var Model|null $model */
            $model = ObjectFactory::make($this->getClient(), $curl->getJSON());
            return $model;
        }

        throw new ApiException($curl->getError());
    }
}
