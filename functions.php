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
 * @param $config
 * @return mysqli
 */
function setDBConnection($config) {
    $con = mysqli_init();
    mysqli_options($con, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
    mysqli_real_connect($con,
        $config['host'],
        $config['user'],
        $config['password'],
        $config['db_name']
    );
    mysqli_set_charset($con, "utf8");
    if (!$con) {
        print("Ошибка подключения: " . mysqli_connect_error());
    }
    return $con;
}

/**
 * @param $date_end
 * @return false|string
 */
function getRemainingTime($date_end) {
    $current_time = strtotime('now');
    $time_left = strtotime($date_end) - $current_time;
    return gmdate('d H:i', $time_left);
}

/**
 * @param $db
 * @param $categories
 * @return array|null
 */
function getCategoriesFromDB($db, $categories) {
    $sql = 'select name'
        . ' from categories'
        . ' order by id';
    $result = mysqli_query($db, $sql);
    if($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        print("Ошибка MySQL: " . mysqli_error($db));
    }
    return $categories;
}

/**
 * @param $db
 * @param $ads
 * @return array|null
 */
function getAdsFromDB($db, $ads) {
    $sql = 'select l.name as title, l.image as url, l.price, l.date_end, g.name as category'
        . ' from lots l'
        . ' left join categories g on l.category_id = g.id'
        . ' where l.winner_id is null'
        . ' order by l.date desc';
    $result = mysqli_query($db, $sql);
    if($result) {
        $ads = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        print("Ошибка MySQL: " . mysqli_error($db));
    }
    return $ads;
}