<?php
require_once 'functions.php';
require_once 'data.php';

$title = 'Вход на сайт';
$categories = [];
$login_data = [];
$required_fields = ['email', 'password'];
$dict = [
    'email' => 'Электронный адрес',
    'password' => 'Пароль'
];
$errors = [];
$db = getDBConnection($db_config);

if (!$db) {
    exit("Ошибка подключения: " . mysqli_connect_error());
} else {
    try {
        $categories = getAllCategories($db);
    } catch (Exception $e) {
        echo $e->getMessage();
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login_data = $_POST;
    foreach($required_fields as $field) {
        if(empty($login_data[$field])) {
            $errors[$field] = 'Поле не заполнено!';
        }
    }
    if (!empty($login_data['email']) && !filter_var($login_data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email должен быть действительным';
    }
    if (count($errors)) {
        $main_content = includeTemplate('login.php', [
            'categories' => $categories,
            'login_data' => $login_data,
            'dict' => $dict,
            'errors' => $errors
        ]);
    } else {
        try {
            $user = getUserByEmail($db, $login_data['email']);
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }
        if (!$user) {
            $errors['email'] = 'Такого пользователя не существует!';
        } elseif (!password_verify($login_data['password'], $user[0]['password'])) {
            $errors['password'] = 'Вы ввели неверный пароль!';
        } else {
            $_SESSION['user'] = $user[0]['name'];
            $_SESSION['user_id'] = $user[0]['id'];
            header("Location: index.php");
            exit();
        }
    }
}

$main_content = includeTemplate('login.php', [
    'categories' => $categories,
    'login_data' => $login_data,
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

print($layout);