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
$db = getDBConnection($db_config);

if (!$db) {
    exit("Ошибка подключения: " . mysqli_connect_error());
} else {
    $result_categories = getCategoriesFromDB($db);
    $categories = $result_categories ? mysqli_fetch_all($result_categories, MYSQLI_ASSOC) :
        print("Ошибка MySQL: " . mysqli_error($db));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lot = $_POST;
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено!';
        }
    }
    if (!empty($lot['lot-rate']) && !filter_var($lot['lot-rate'], FILTER_VALIDATE_INT)) {
        $errors['lot-rate'] = 'Цена должна быть числом';
    }
    if (!empty($lot['lot-step']) && !filter_var($lot['lot-step'], FILTER_VALIDATE_INT)) {
        $errors['lot-step'] = 'Ставка должна быть числом';
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
    if ($lot['category'] === 'Выберите категорию') {
        $errors['category'] = 'Категория не выбрана!';
    }
    if (count($errors)) {
        $main_content = includeTemplate('add-lot.php', [
            'categories' => $categories,
            'lot' => $lot,
            'dict' => $dict,
            'errors' => $errors
        ]);
    } else {
        foreach ($categories as $category) {
            if ($category['name'] === $lot['category']) {
                $category_id = $category['id'];
            }
        }
        $sql = "insert into lots(name, description, image, price, date_end, bet_step, user_id, category_id)"
            . " values (?, ?, ?, ?, ?, ?, 1, ?)";
        $stmt = db_get_prepare_stmt($db, $sql, [
            $lot['lot-name'],
            $lot['message'],
            $lot['path'],
            $lot['lot-rate'],
            $lot['lot-date'],
            $lot['lot-step'],
            $category_id
        ]);
        $result = mysqli_stmt_execute($stmt);
        if ($result) {
            $lot_id = mysqli_insert_id($db);
            header("Location: /lot.php?id=". $lot_id);
        }
    }
} else {
    $main_content = includeTemplate('add-lot.php', [
        'categories' => $categories,
        'errors' => $errors
    ]);
}

$layout = includeTemplate('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'main_content' => $main_content
]);

print($layout);