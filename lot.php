<?php
require_once 'functions.php';
require_once 'data.php';

$db = getDBConnection($db_config);
$template_page = '';
$lots = [];
$categories = [];

if(!$db) {
    exit("Ошибка подключения: " . mysqli_connect_error());
} else {
    $result_categories = getCategoriesFromDB($db);
    $categories = $result_categories ? mysqli_fetch_all($result_categories, MYSQLI_ASSOC) :
        print("Ошибка MySQL: " . mysqli_error($db));
    if (isset($_GET['id'])) {
        $lot = getLotByIdFromDB($db, [$_GET['id']]);
    } else {
        http_response_code(404);
        exit();
    }
}

if (isset($lot[0]['id'] )) {
    $template_page = 'lot.php';
    $title = $lot[0]['title'];
} else {
    $template_page = '404.php';
    $title = 'Страница товара';
}

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