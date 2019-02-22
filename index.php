<?php
require_once 'functions.php';
require_once 'data.php';

$lots = [];
$categories = [];
$db = getDBConnection($db_config);
if(!$db) {
    exit("Ошибка подключения: " . mysqli_connect_error());
} else {
    try {
        $categories = getAllCategories($db);
        $lots = getNewestLots($db);
    } catch (Exception $e) {
        echo $e->getMessage();
        exit();
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
