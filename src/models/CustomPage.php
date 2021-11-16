<?php

namespace Lifeboat\Models;

use Lifeboat\Services\CustomPages;

/**
 * Class CustomPage
 * @package Lifeboat\Models
 *
 * @property string $Content
 */
class CustomPage extends Page {

    /**
     * @return string
     */
    public function model(): string
    {
        return 'Page';
    }

    /**
     * @return CustomPages
     */
    public function getService(): CustomPages
    {
        return new CustomPages($this->getClient());
    }
}
