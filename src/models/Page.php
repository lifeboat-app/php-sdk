<?php

namespace Lifeboat\Models;

use Lifeboat\Interfaces\CustomFieldSupport;
use Lifeboat\Services\Pages;

/**
 * Class Page
 * @package Lifeboat\Models
 *
 * @property string $Title
 * @property string $URLSegment
 * @property string $Path
 * @property string $FullURL
 * @property string|null $MetaTitle
 * @property string|null $MetaDescription
 * @property string|null $ExtraMeta
 * @property bool $ExcludeFromSiteMap
 * @property array $CustomFields
 */
class Page extends Model implements CustomFieldSupport {

    protected static array $casting = [
        'ExcludeFromSiteMap' => 'boolval'
    ];

    /**
     * @return string
     */
    public function model(): string
    {
        return 'Page';
    }

    /**
     * @return Pages
     */
    public function getService(): Pages
    {
        return new Pages($this->getClient());
    }
}
