<?php
require_once 'mysql_helper.php';
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
function getDBConnection($config) {
    $con = mysqli_init();
    mysqli_options($con, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
    mysqli_real_connect($con,
        $config['host'],
        $config['user'],
        $config['password'],
        $config['db_name']
    );
    mysqli_set_charset($con, "utf8");
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
 * @return array|null
 * @throws Exception
 */
function getAllCategories($db) {
    $sql = 'select id, name'
        . ' from categories'
        . ' order by id';
    $result = mysqli_query($db, $sql);
    if(!$result) {
         throw new Exception("Ошибка MySQL: " . mysqli_error($db));
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * @param $db
 * @return array|null
 * @throws Exception
 */
function getNewestLots($db) {
    $sql = 'select l.id, l.name as title, l.image as url, l.price, l.date_end, g.name as category'
        . ' from lots l'
        . ' left join categories g on l.category_id = g.id'
        . ' where l.winner_id is null'
        . ' order by l.date desc';
    $result = mysqli_query($db, $sql);
    if(!$result) {
        throw new Exception("Ошибка MySQL: " . mysqli_error($db));
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * @param $db
 * @param $lot_id
 * @return array|null
 */
function getLotByIdFromDB($db, $lot_id) {
    $sql = 'select l.id, l.name as title, l.description, l.image as url, l.price, l.date_end, g.name as category'
        . ' from lots l'
        . ' left join categories g on l.category_id = g.id'
        . ' where l.id = ?';
    $stmt = db_get_prepare_stmt($db, $sql, $lot_id);
    $result_query = mysqli_stmt_execute($stmt);
    if ($result_query !== false) {
        $result_stmt = mysqli_stmt_get_result($stmt);
        $lot = mysqli_fetch_all($result_stmt, MYSQLI_ASSOC);
    }
    return $lot;
}