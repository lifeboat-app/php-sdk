<?php

/**
 * @param string $date
 * @return DateTime
 * @throws Exception
 */
function lifeboat_date_formatter(string $date): DateTime
{
    $date = new \DateTime($date);
    $date->setTimezone(new DateTimeZone(DateTimeZone::EUROPE));
    return $date;
}
