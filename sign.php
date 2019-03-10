<?php
require_once 'functions.php';
require_once 'data.php';

$title = 'Регистрация';
$categories = [];
$required_fields = ['email', 'password', 'name', 'message'];
$dict = [
    'email' => 'Электронный адрес',
    'password' => 'Пароль',
    'name' => 'Имя',
    'message' => 'Контактные данные',
    'avatar' => 'Аватар'
];
$errors = [];
$db = getDBConnection($db_config);

$categories = getAllCategories($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST;
    foreach ($required_fields as $field) {
        if (empty($user[$field])) {
            $errors[$field] = 'Поле не заполнено!';
        }
    }
    if (isset($_FILES) && ($_FILES['avatar']['error']) == 0) {
        $tmp_name = $_FILES['avatar']['tmp_name'];
        $path = $_FILES['avatar']['name'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);

        if ($file_type !== "image/jpg" && $file_type !== "image/jpeg" && $file_type !== "image/png") {
            $errors['avatar'] = 'Изображение должно быть в формате .jpg/jpeg или .png';
        } else {
            move_uploaded_file($tmp_name, 'img/' .$path);
            $user['path'] = 'img/' .$path;
        }
    }
    if (!empty($user['email'])) {
        if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email должен быть действительным';
        } else {
            if (getUserByEmail($db, $user['email'])) {
                $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
            }
        }
    }
    if (count($errors)) {
        $main_content = includeTemplate('sign-up.php', [
            'categories' => $categories,
            'user' => $user,
            'dict' => $dict,
            'errors' => $errors
        ]);
    } else {
        $password = password_hash($user['password'], PASSWORD_DEFAULT);
        $user['path'] = (isset($user['path']) ? $user['path'] : '');
        $sql = "insert into users(email, name, password, contact, avatar)"
            . " values (?, ?, ?, ?, ?)";
        $stmt = db_get_prepare_stmt($db, $sql, [
            $user['email'],
            $user['name'],
            $password,
            $user['message'],
            $user['path']
        ]);
        $result = mysqli_stmt_execute($stmt);
        if ($result) {
            header("Location: login.php");
            die();
        }
    }
} else {
    $main_content = includeTemplate('sign-up.php', [
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

echo $layout;