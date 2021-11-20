<?php

/**
 * @param string $date
 * @return DateTime|null
 */
function lifeboat_date_formatter(string $date): ?DateTime
{
    if (!$date) return null;

    try {
        $date = new \DateTime($date . ' CET');
        return $date;
    } catch (Exception $e) {
        return null;
    }
}
