<?php

/**
 * @param string $date
 * @return DateTime|string
 */
function lifeboat_date_formatter(string $date)
{
    try {
        $date = new \DateTime($date . ' CET');
        return $date;
    } catch (Exception $e) {
        error_log($e);
    }

    return $date;
}
