<?php
require_once 'functions.php';
require_once 'data.php';

$lots = [];
$categories = [];
$db = getDBConnection($db_config);
if(!$db) {
    exit("Ошибка подключения: " . mysqli_connect_error());
} else {
    $result_categories = getCategoriesFromDB($db);
    $result_lots = getLotsFromDB($db);
    if ($result_categories && $result_lots) {
        $categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
        $lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);
    }
    else {
        print("Ошибка MySQL: " . mysqli_error($db));
    }
}

$main_content = includeTemplate('index.php', [
    'categories' => $categories,
    'lots' => $lots
]);

$layout = includeTemplate('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'main_content' => $main_content
]);

print($layout);
