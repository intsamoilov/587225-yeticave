<?php
require_once 'functions.php';
require_once 'data.php';

$db = getDBConnection($db_config);
if($db) {
    $categories = getCategoriesFromDB($db);
    $lots = getLotsFromDB($db);
} else {
    exit("Ошибка подключения: " . mysqli_connect_error());
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
