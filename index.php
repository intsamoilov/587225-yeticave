<?php
require_once 'functions.php';
require_once 'data.php';

$db = setDBConnection($db_config);
if($db) {
    $categories = getCategoriesFromDB($db);
    $ads = getAdsFromDB($db);
}

$main_content = includeTemplate('index.php', [
    'categories' => $categories,
    'ads' => $ads
]);

$layout = includeTemplate('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'main_content' => $main_content
]);

print($layout);
