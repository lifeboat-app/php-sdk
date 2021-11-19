<?php

namespace Lifeboat\Models;

use Lifeboat\Connector;
use Lifeboat\Services\Collections;

/**
 * Class Customer
 * @package Lifeboat\Models
 *
 * @property string|null $Description
 * @property int $Priority
 * @property bool $isAuto
 * @property bool $MatchAny
 * @property array|null $Rules
 * @property array $Products
 * @property array $Tags
 * @property string|null $Thumbnail
 */
class Collection extends Model {

    protected static array $casting = [
        'ExcludeFromSitemap'    => 'boolval',
        'isAuto'                => 'boolval',
        'MatchAny'              => 'boolval'
    ];

    public function __construct(Connector $client, array $_object_data = [])
    {
        parent::__construct($client, $_object_data);
        if (array_key_exists('Rules', $_object_data)) {
            $this->Rules = json_decode($_object_data['Rules'], true);
        }
    }
}
