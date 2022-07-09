<?php
session_start();

require_once("helpers.php");
require_once("data.php");
require_once("init.php");
require_once("sql_functions.php");
require_once("functions.php");


$user_bets = get_user_bets($sql_con, $_SESSION['id']);

$page_content = include_template("my_bets.php", [
    "user_bets" => $user_bets
]);


$layout_content = include_template("layout.php", [
    "cats_arr" => $cats_arr,
    "content" => $page_content,
    "title" => "Главная"
]);



print($layout_content);


?>