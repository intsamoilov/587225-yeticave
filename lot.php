<?php
require_once 'functions.php';
require_once 'data.php';

$db = getDBConnection($db_config);
$template_page = '';
$lots = [];
$is_show = 0;
$bet = [];
$categories = [];
$required_fields = ['cost'];
$dict = [
    'cost' => 'Ваша ставка'
];
$errors = [];
$is_current_user = false;
$is_already_betted = false;

$categories = getAllCategories($db);

if (isset($_GET['id'])) {
    $lot = getLotById($db, $_GET['id']);
} else {
    http_response_code(404);
    die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bet = $_POST;
    foreach($required_fields as $field) {
        if(empty($bet[$field])) {
            $errors[$field] = 'Поле не заполнено!';
        }
    }
    if (!empty($bet['cost']) && $bet['cost'] <= $lot[0]['price'] + $lot[0]['bet_step']) {
        $errors['cost'] = 'Должна быть больше минимальной!';
    }
    if (!count($errors)) {
        $sql = "insert into bets(bid, user_id, lot_id)"
            . " values (?, ?, ?)";
        $stmt = db_get_prepare_stmt($db, $sql, [
            $bet['cost'],
            $_SESSION['user_id'],
            $lot[0]['id']
        ]);
        $result = mysqli_stmt_execute($stmt);
        if ($result) {
            header("Location: /lot.php?id=". $lot[0]['id']);
            die();
        }
    }
}

if (isset($lot[0]['id'] )) {
    $template_page = 'lot.php';
    $title = $lot[0]['title'];
} else {
    $template_page = '404.php';
    $title = 'Страница товара';
}

if (!empty($_SESSION['user_id'])) {
    $is_current_user = ($_SESSION['user_id'] == $lot[0]['user_id']);
    $is_already_betted = getUserBetByLotId($db, $_SESSION['user_id'], $lot[0]['id']);
}

$is_actual_date = strtotime('now') < strtotime($lot[0]['date_end']);
$is_show = ($is_auth && !$is_current_user && $is_actual_date && !$is_already_betted);
$bets_list = getBetsByLotId($db, $lot[0]['id']);

$main_content = includeTemplate($template_page, [
    'categories' => $categories,
    'is_show' => $is_show,
    'lot' => $lot[0],
    'bet' => $bet,
    'bets_list' => $bets_list,
    'errors' => $errors,
    'dict' => $dict
]);

$layout = includeTemplate('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'main_content' => $main_content
]);

echo($layout);