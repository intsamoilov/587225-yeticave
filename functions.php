<?php
date_default_timezone_set("Europe/Moscow");

function format_price ($price) {
    $number = ceil($price);
    $number = number_format($number, 0, ',', ' ');
    return $number . ' ₽';
}

function include_template($name, $data) {
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

function time_to_midnight() {
    $midnight = strtotime('tomorrow midnight');
    $current_time = strtotime('now');
    $time_left = $midnight - $current_time;
    return gmdate('H:i', $time_left);
}