<?php
session_start();
$is_auth = empty($_SESSION['user']) ? 0 : 1;
$user_name = empty($_SESSION['user']) ? '' : $_SESSION['user'];
$db_config = [
    'host' => '127.0.0.1',
    'user' => 'root',
    'password' => '',
    'db_name' => 'yeticave'
];