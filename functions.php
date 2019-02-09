<?php
date_default_timezone_set("Europe/Moscow");

/**
 * @param $price
 * @return string
 */
function formatPrice ($price) {
    $number = ceil($price);
    $number = number_format($number, 0, ',', ' ');
    return $number . ' ₽';
}

/**
 * @param $name
 * @param $data
 * @return false|string
 */
function includeTemplate($name, $data) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }
    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * @param $date
 * @return false|int
 */
function getSecondsForMidnight($date) {
    $midnight = strtotime('tomorrow midnight');
    $time_left = $midnight - $date;
    return $time_left;
}