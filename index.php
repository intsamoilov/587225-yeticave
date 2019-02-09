<?php
require_once 'functions.php';
require_once 'data.php';

$main_content = includeTemplate('index.php', [
    'categories' => $categories,
    'ads' => $ads,
    'time_left' => $time_left
]);
$layout = includeTemplate('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'main_content' => $main_content
]);
print($layout);
