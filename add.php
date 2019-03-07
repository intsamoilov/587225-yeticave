<?php
require_once 'functions.php';
require_once 'data.php';

$title = 'Добавление лота';
$categories = [];
$required_fields = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
$dict = [
    'lot-name' => 'Название',
    'category' => 'Категория',
    'message' => 'Описание',
    'lot-img' => 'Изображение',
    'lot-rate' => 'Начальная цена',
    'lot-step' => 'Шаг ставки',
    'lot-date' => 'Дата окончания'
];
$errors = [];
$lot['category'] = 0;
$db = getDBConnection($db_config);
if (!$is_auth) {
    http_response_code('403');
    echo('Ошибка доступа: Требуется войти в свою учетную запись!');
    die();
}

$categories = getAllCategories($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lot = $_POST;
    foreach ($required_fields as $field) {
        if (empty($lot[$field])) {
            $errors[$field] = 'Поле не заполнено!';
        }
    }
    if (!empty($lot['lot-rate']) && !filter_var($lot['lot-rate'], FILTER_VALIDATE_FLOAT)) {
        $errors['lot-rate'] = 'Цена должна быть числом';
    } else {
        ($lot['lot-rate'] <= 0) ? $errors['lot-rate'] = 'Цена должна быть выше нуля' : '';
    }
    if (!empty($lot['lot-step']) && !filter_var($lot['lot-step'], FILTER_VALIDATE_FLOAT)) {
        $errors['lot-step'] = 'Ставка должна быть числом';
    } else {
        ($lot['lot-step'] <= 0) ? $errors['lot-step'] = 'Ставка должна быть выше нуля' : '';
    }
    if (isset($_FILES) && ($_FILES['lot-img']['error']) == 0) {
        $tmp_name = $_FILES['lot-img']['tmp_name'];
        $path = $_FILES['lot-img']['name'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);

        if ($file_type !== "image/jpg" && $file_type !== "image/jpeg" && $file_type !== "image/png") {
            $errors['lot-img'] = 'Изображение должно быть в формате .jpg/jpeg или .png';
        } else {
            move_uploaded_file($tmp_name, 'img/' .$path);
            $lot['path'] = 'img/' .$path;
        }
    } else {
        $errors['lot-img'] = 'Вы не загрузили файл!';
    }
    $lot['category'] = (int)$lot['category'];
    if ($lot['category'] === 0) {
        $errors['category'] = 'Категория не выбрана!';
    }
    if (!empty($lot['lot-date'])) {
        if(!check_date_format($lot['lot-date'])) {
            $errors['lot-date'] = 'Укажите дату в формате ДД.ММ.ГГГГ или ГГГГ-ММ-ДД';
        }
        $lot['lot-date'] = date("Y-m-d", strtotime($lot['lot-date']));
        if (strtotime($lot['lot-date']) - strtotime('now') < 86400) {
            $errors['lot-date'] = 'Укажите дату больше текущей, хотя бы на один день';
        }
    }
    if (count($errors)) {
        $main_content = includeTemplate('add-lot.php', [
            'categories' => $categories,
            'lot' => $lot,
            'dict' => $dict,
            'errors' => $errors
        ]);
    } else {
        $sql = "insert into lots(name, description, image, price, date_end, bet_step, user_id, category_id)"
            . " values (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = db_get_prepare_stmt($db, $sql, [
            $lot['lot-name'],
            $lot['message'],
            $lot['path'],
            $lot['lot-rate'],
            $lot['lot-date'],
            $lot['lot-step'],
            $_SESSION['user_id'],
            $lot['category']
        ]);
        $result = mysqli_stmt_execute($stmt);
        if ($result) {
            $lot_id = mysqli_insert_id($db);
            header("Location: /lot.php?id=". $lot_id);
            die();
        }
    }
} else {
    $main_content = includeTemplate('add-lot.php', [
        'categories' => $categories,
        'errors' => $errors,
        'lot' => $lot
    ]);
}

$layout = includeTemplate('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'main_content' => $main_content
]);

echo($layout);