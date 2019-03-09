<?php
require_once 'functions.php';
require_once 'data.php';
require_once 'winner.php';

$lots = [];
$categories = [];
$title = 'Главная';
$db = getDBConnection($db_config);

$categories = getAllCategories($db);
$lots = getNewestLots($db);

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

echo $layout;
