<?php

require_once("helpers.php");
require_once("data.php");
require_once("init.php");
require_once("sql_functions.php");
require_once("functions.php");
require_once("vendor/autoload.php");

$finished_lots = get_finished_lots($sql_con);

foreach ($finished_lots as $lot) {
    $id = $lot["id"];
    close_lots($sql_con, $id);
    $winner = get_winner($sql_con, $id);
    foreach ($winner as $user) {
        set_winner($sql_con, $user["user_id"], $id);
    }
}


/* Mailer

$recipients =[];
foreach($bets_win as $bet) {
    $id = intval($bet["user_id"]);
    $user_date = get_user_contacts ($con, $id);
    $recipients[$user_date["email"]] = $user_date["user_name"];
}

$transport = new Swift_SmtpTransport("smtp.mailtrap.io", 2525);
$transport -> setUsername("75f3c8c888f4c0");
$transport -> setPassword("d3bf00f9a2376d");

$mailer = new Swift_Mailer($transport);

$message = new Swift_Message();
$message -> setSubject("Ваша ставка победила!");
$message-> setFrom(["keks@phpdemo" => "GifTube"]);
$message-> setTo($recipients);

$msg_content = include_template("email.php", ["win_users" => $win_users]);
$message -> setBody($msg_content, "text/html");

$result = $mailer -> send($message);

if ($result) {
    print ("Рассылка успешно отправленна");
} else {
    print ("Не удалось отправить рассылку");
}
*/