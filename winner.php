<?php
require_once 'functions.php';
require_once 'data.php';

$db = getDBConnection($db_config);
$ended_lots = getEndedLots($db);
$message = '';
foreach ($ended_lots as $lot) {
    if (strtotime($lot['date_end']) < strtotime('now')) {
        $bets = getBetsByLotId($db, $lot['id']);
        $winner_id = !empty($bets) ? $bets[0]['user_id'] : 0;
        $sql = "update lots set winner_id = " . $winner_id . " where id = " . $lot['id'];
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_execute($stmt);

        $page = includeTemplate('email.php', [
            'user_name' => $bets[0]['name'],
            'lot_name' => $lot['name'],
            'lot_id' => $lot['id']
        ]);

        $transport = new Swift_SmtpTransport('phpdemo.ru', 25);
        $transport ->setUsername('keks@phpdemo.ru');
        $transport ->setPassword('htmlacademy');

        $message = new Swift_Message('Ваша ставка победила');
        $message -> setFrom('keks@phpdemo.ru');
        $message -> setTo($bets[0]['email']);
        $message -> addPart($page, 'text/html');

        $mailer = new Swift_Mailer($transport);
        $mailer -> send($message);
    }
}

