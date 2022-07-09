<?php
session_start();

require_once("helpers.php");
require_once("data.php");
require_once("init.php");
require_once("sql_functions.php");
require_once("functions.php");
require_once("getwinner.php");

$category_id = htmlspecialchars($_GET["category"]);
$all_lots = get_all_lots($sql_con, $category_id);

$header = include_template("header.php", [
    "cats_arr" => $cats_arr,
]);

$page_content = include_template("all_lots.php", [
    "header" => $header,
    "sql_con" => $sql_con,
    "cats_arr" => $cats_arr,
    "all_lots" => $all_lots
]);


$layout_content = include_template("layout.php", [
    "cats_arr" => $cats_arr,
    "content" => $page_content,
    "title" => "Главная"
]);



print($layout_content);


?>