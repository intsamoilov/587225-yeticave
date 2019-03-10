<?php
require_once 'functions.php';
require_once 'data.php';

$db = getDBConnection($db_config);
$title = 'Мои ставки';
$categories = getAllCategories($db);
$user_id = empty($_SESSION['user_id']) ? 0 : $_SESSION['user_id'];
$rates = getRatesByUserId($db, $user_id);
for ($i = 0; $i < count($rates); $i++) {
    $rates[$i]['finishing'] = strtotime($rates[$i]['date_end']) - strtotime('now') < 86400;
    $rates[$i]['winner'] = $user_id == $rates[$i]['winner_id'];
    $rates[$i]['end'] = $rates[$i]['winner_id'] !== NULL && $user_id != $rates[$i]['winner_id'];
}
$main_content = includeTemplate('my-rates.php', [
    'categories' => $categories,
    'rates' => $rates
]);

$layout = includeTemplate('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'main_content' => $main_content
]);

echo $layout;