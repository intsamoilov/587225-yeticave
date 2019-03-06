<?php
require_once 'functions.php';
require_once 'data.php';

$db = getDBConnection($db_config);
$title = 'Результаты поиска';
$lots = [];
$search_str = '';
$total_lots = 0;
$categories = [];
$message = '';
$page_number = 0;
$total_pages = 0;
$pages = [];

$categories = getAllCategories($db);

if (!empty($_GET['search'])) {
    $search_str = trim($_GET['search']);
    $total_lots = getTotalLotsBySearch($db, $search_str);
    $total_lots = $total_lots[0]['count(*)'];

    if(!$total_lots) {
        $message = 'Ничего не найдено по вашему запросу';
    } else {
        $page_number = (empty($_GET['page'])) ? 1 : intval($_GET['page']);
        $total_pages = intval(ceil($total_lots / $lots_by_page));
        $offset = ($page_number - 1) * $lots_by_page;
        $pages = range(1, $total_pages);
        $lots = getLotsBySearch($db, $search_str, $lots_by_page, $offset);
    }
} else {
    $message = 'Ничего не найдено по вашему запросу';
}

$main_content = includeTemplate('search.php', [
    'categories' => $categories,
    'search_str' => $search_str,
    'lots' => $lots,
    'message' => $message,
    'page_number' => $page_number,
    'total_pages' => $total_pages,
    'pages' => $pages
]);

$layout = includeTemplate('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'main_content' => $main_content
]);

echo($layout);