<?php
session_start();

require_once("helpers.php");
require_once("data.php");
require_once("init.php");
require_once("sql_functions.php");
require_once("functions.php");
require_once("getwinner.php");
require_once("vendor/autoload.php");

$lots_arr = get_lots_promo($sql_con, '2022-06-10');

$page_content = include_template("main.php", [
    "cats_arr" => $cats_arr,
    "lots_arr" => $lots_arr
]);


$layout_content = include_template("layout.php", [
    "cats_arr" => $cats_arr,
    "content" => $page_content,
    "title" => "Главная"
]);



print($layout_content);


?>