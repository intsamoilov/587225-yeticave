<?php
require_once 'functions.php';
require_once 'data.php';

$db = getDBConnection($db_config);
$template_page = '';

if($db) {
    $categories = getCategoriesFromDB($db);
    if (isset($_GET['id'])) {
        $lot = getLotByIdFromDB($db, [$_GET['id']]);
    } else {
        http_response_code(404);
        exit();
    }
} else {
    exit("Ошибка подключения: " . mysqli_connect_error());
}

$template_page = (isset($lot[0]['id'])) ? 'lot.php' : '404.php';

$main_content = includeTemplate($template_page, [
    'categories' => $categories,
    'lot' => $lot
]);

$layout = includeTemplate('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'main_content' => $main_content
]);

print($layout);