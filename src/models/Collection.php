<?php

namespace Lifeboat\Models;

use Lifeboat\Connector;
use Lifeboat\Services\Collections;
use Lifeboat\Traits\TagSupport;

/**
 * Class Customer
 * @package Lifeboat\Models
 *
 * @property string|null $Description
 * @property int $Priority
 * @property bool $isAuto
 * @property bool $MatchAny
 * @property array|null $Rules
 * @property array|null $Products
 * @property array $Tags
 * @property string|null $Thumbnail
 * @property Image|null $Image
 */
class Collection extends Model {

    use TagSupport;

    protected static $casting = [
        'ExcludeFromSitemap'    => 'boolval',
        'isAuto'                => 'boolval',
        'MatchAny'              => 'boolval'
    ];

    public function __construct(Connector $client, array $_object_data = [])
    {
        if (array_key_exists('Products', $_object_data) && is_array($_object_data['Products'])) {
            $products = [];
            foreach ($_object_data['Products'] as $product) $products[] = $product['ID'];
            $_object_data['Products'] = $products;
        }

        parent::__construct($client, $_object_data);
        if (array_key_exists('Rules', $_object_data)) {
            $this->Rules = json_decode($_object_data['Rules'], true);
        }
    }
}
