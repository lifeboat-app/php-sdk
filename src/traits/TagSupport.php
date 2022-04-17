<?php

namespace Lifeboat\Traits;

use Lifeboat\Models\LifeboatModel;

/**
 * Trait TagSupport
 * @package Lifeboat\Traits
 */
trait TagSupport {

    /**
     * @param string $find
     * @param bool $case_sensitive
     * @return bool
     */
    public function hasTag(string $find, bool $case_sensitive = true): bool
    {
        if (!$case_sensitive) $tag = strtolower($find);

        if (is_array($this->Tags)) {
            /** @var LifeboatModel $tag */
            foreach ($this->Tags as $tag) {
                $check = ($case_sensitive) ? $tag->value : strtolower($tag->value);
                if ($check === $find) return true;
            }
        }

        return false;
    }

}
