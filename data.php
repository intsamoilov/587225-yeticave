<?php
$is_auth = rand(0, 1);
$current_time = strtotime('now');
$time_left = gmdate('H:i', getSecondsForMidnight($current_time));
$title = 'Главная';
$user_name = 'Александр Самойлов'; // укажите здесь ваше имя
$categories = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];
$ads = [
    [
        'title' => '2014 Rossignol District Snowboard',
        'category' => $categories[0],
        'price' => 10999,
        'url' => 'img/lot-1.jpg'
    ],
    [
        'title' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => $categories[0],
        'price' => 159999,
        'url' => 'img/lot-2.jpg'
    ],
    [
        'title' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => $categories[1],
        'price' => 8000,
        'url' => 'img/lot-3.jpg'
    ],
    [
        'title' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => $categories[2],
        'price' => 10999,
        'url' => 'img/lot-4.jpg'
    ],
    [
        'title' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => $categories[3],
        'price' => 7500,
        'url' => 'img/lot-5.jpg'
    ],
    [
        'title' => 'Маска Oakley Canopy',
        'category' => $categories[5],
        'price' => 5400,
        'url' => 'img/lot-6.jpg'
    ],
];