<?php
require_once 'mysql_helper.php';

/**require_once 'mysql_helper.php';
 * @param $date
 * @return bool
 */
function check_date_format($date) {
    $time_stamp = strtotime($date);
    return ($date == date("Y-m-d", $time_stamp) || $date == date("d.m.Y", $time_stamp));
}

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
 * @param $number
 * @param $dictionary
 * @return mixed
 */
function declensionWords($number, $dictionary) {
    $check = $number % 100;
    $check = ($check > 19) ? $check % 10 : $check;
    switch ($check) {
        case 1: return $dictionary[0];
        case 2:
        case 3:
        case 4: return $dictionary[1];
        default: return $dictionary[2];
    }
}

/**
 * @param $date
 * @return false|string
 */
function formatDate ($date) {
    $bet_time = strtotime($date);
    $minutes_result = ceil((strtotime('now') - $bet_time) / 60);
    $hours_result = ceil((strtotime('now') - $bet_time) / 3600);
    switch (true) {
        case $minutes_result < 60:
            return $minutes_result . declensionWords($minutes_result, [' минуту', ' минуты', ' минут'])
                . ' назад';
        case $hours_result < 24:
            return ($hours_result) . declensionWords($hours_result, [' час', ' часа', ' часов'])
                . ' назад';
        default:
            return date("d-m-y \в H:i", $bet_time);
    }
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
 * @throws Exception
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
    if(!$con) {
        throw new Exception("Ошибка MySQL: " . mysqli_error($db));
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
    $hours = floor($time_left / 3600);
    $minutes = floor(($time_left % 3600) / 60);
    return $hours . ':' .$minutes;
}

/**
 * @param $db
 * @param $sql
 * @return array|null
 * @throws Exception
 */
function getQueryResult($db, $sql) {
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
function getAllCategories($db) {
    $sql = 'select id, name'
        . ' from categories'
        . ' order by id';
    return getQueryResult($db, $sql);
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
        . ' order by l.date desc'
        . ' limit 9';
    return getQueryResult($db, $sql);
}

/**
 * @param $db
 * @param $lot_id
 * @return array|null
 * @throws Exception
 */
function getLotById($db, $lot_id) {
    $sql = "select l.id, l.name as title, l.description, l.image as url, l.price, l.bet_step,"
        . " l.user_id, l.date_end, g.name as category"
        . " from lots l"
        . " left join categories g on l.category_id = g.id"
        . " where l.id = '$lot_id'";
    return getQueryResult($db, $sql);
}

/**
 * @param $db
 * @param $search_words
 * @return array|null
 * @throws Exception
 */
function getTotalLotsBySearch($db, $search_words) {
    $sql = "select count(*)"
        . " from lots l"
        . " where match(l.name, l.description) against('$search_words') and l.winner_id is null";
    return getQueryResult($db, $sql);
}

/**
 * @param $db
 * @param $search_words
 * @param $lots_by_page
 * @param $offset
 * @return array|null
 * @throws Exception
 */
function getLotsBySearch($db, $search_words, $lots_by_page, $offset) {
    $sql = "select l.id, l.name as title, l.description, l.image as url, l.price, l.bet_step,"
        . " l.user_id, l.date_end, g.name as category"
        . " from lots l"
        . " left join categories g on l.category_id = g.id"
        . " where match(l.name, l.description) against('$search_words') and l.winner_id is null"
        . " order by l.date desc"
        . " limit " . $lots_by_page . " offset " . $offset . "";
    return getQueryResult($db, $sql);
}

/**
 * @param $db
 * @param $user_email
 * @return array|null
 * @throws Exception
 */
function getUserByEmail($db, $user_email) {
    $email = mysqli_real_escape_string($db, $user_email);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    return getQueryResult($db, $sql);
}

/**
 * @param $db
 * @param $user_id
 * @param $lot_id
 * @return array|null
 * @throws Exception
 */
function getUserBetByLotId($db, $user_id, $lot_id) {
    $sql = "SELECT * FROM bets WHERE user_id = '$user_id' and lot_id = '$lot_id'";
    return getQueryResult($db, $sql);
}

/**
 * @param $db
 * @param $lot_id
 * @return array|null
 * @throws Exception
 */
function getBetsByLotId($db, $lot_id) {
    $sql = "SELECT bets.date, bets.bid, users.name "
        . " FROM bets LEFT JOIN users on bets.user_id = users.id"
        . " WHERE bets.lot_id = '$lot_id' ORDER BY bets.date DESC";
    return getQueryResult($db, $sql);
}