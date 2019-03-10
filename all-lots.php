<?php
require_once 'functions.php';
require_once 'data.php';

$db = getDBConnection($db_config);
$title = 'Категория';
$lots = [];
$category_id = 0;
$total_lots = 0;
$categories = [];
$message = '';
$page_number = 0;
$total_pages = 0;
$pages = [];
$category_name = '';
$categories = getAllCategories($db);

if (!empty($_GET['id'])) {
    $category_id = $_GET['id'];
    $total_lots = getTotalLotsByCategory($db, $category_id);
    $total_lots = $total_lots[0]['count(*)'];
    $category_name = getCategoryById($db, $category_id);
    $title = 'Все лоты в категории ' . $category_name[0]['name'];

    if(!$total_lots) {
        $message = 'Ничего не найдено по вашему запросу';
    } else {
        $page_number = (empty($_GET['page'])) ? 1 : intval($_GET['page']);
        $total_pages = intval(ceil($total_lots / $lots_by_page));
        $offset = ($page_number - 1) * $lots_by_page;
        $pages = range(1, $total_pages);
        $lots = getLotsByCategory($db, $category_id, $lots_by_page, $offset);
    }
}

$main_content = includeTemplate('all-lots.php', [
    'categories' => $categories,
    'category_id' => $category_id,
    'lots' => $lots,
    'message' => $message,
    'page_number' => $page_number,
    'total_pages' => $total_pages,
    'pages' => $pages,
    'category_name' => $category_name[0]['name']
]);

$layout = includeTemplate('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'main_content' => $main_content
]);

echo $layout;