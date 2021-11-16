<?php

/**
 * @param string $date
 * @return DateTime|string|null
 */
function lifeboat_date_formatter(string $date)
{
    if (!$date) return null;

    try {
        $date = new \DateTime($date . ' CET');
        return $date;
    } catch (Exception $e) {
        error_log($e);
    }

    return $date;
}
