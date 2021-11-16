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
 * @property string $model
 */
abstract class Model extends ObjectResource {

    abstract protected function getSaveURL(): string;

    public function __construct(Connector $client, array $_object_data = [])
    {
        parent::__construct($client, $_object_data);

        if (!ArrayLib::is_associative($_object_data)) {
            throw new InvalidArgumentException("Model::__construct() expects an associative array");
        }

        if (array_key_exists('model', $_object_data)) $this->model = $_object_data['model'];
        $model = $this->model;

        foreach ($_object_data as $field => $value) {
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

                    // Setup the reverse relationship unless it's already setup
                    if (!property_exists($this->$field, $model)) {
                        $this->$field->$model = $this;
                    }
                } else {
                    // HasMany / ManyMany Relation
                    $list = [];
                    foreach ($value as $item) {
                        if (ArrayLib::is_associative($item)) {
                            $list = ObjectFactory::make($client, $item);
                        } else {
                            $list = $item;
                        }
                    }

                    $this->$field = $list;
                }
            } else {
                $this->$field = $value;
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
     * @return Model|null
     * @throws ApiException
     * @throws OAuthException
     */
    public function save(): ?Model
    {
        $curl = $this->getClient()->curl_api($this->getSaveURL(), 'POST', $this->toArray());

        if ($curl->isValid() && $curl->isJSON()) {
            /** @var Model|null $model */
            $model = ObjectFactory::make($this->getClient(), $curl->getJSON());
            return $model;
        }

        throw new ApiException($curl->getError());
    }
}
