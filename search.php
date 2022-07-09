<?php
session_start();

require_once("helpers.php");
require_once("data.php");
require_once("init.php");
require_once("sql_functions.php");
require_once("functions.php");


$header = include_template("header.php", [
    "cats_arr" => $cats_arr
]);

$page_content = include_template("search.php", [
    "header" => $header
]);

$search_text = "";
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

$search_text = mysqli_real_escape_string($sql_con, $_GET["search"]);
if ($search_text) {
    $items_count = get_count_lots($sql_con, $search_text);
    $cur_page = $_GET["page"] ?? 1;
    $page_items = 4;
    $pages_count = ceil($items_count / $page_items);
    $offset = ($cur_page - 1) * $page_items;
    $pages = range(1, $pages_count);

    $lots = fulltext_search($sql_con, $search_text, $page_items, $offset);
}

$page_content = include_template("search.php", [
    "header" => $header,
    "lots" => $lots,
    "search_text" => $search_text,
    //"panagination" => $panagination,
    "pages_count" => $pages_count,
    "pages" => $pages,
    "cur_page" => $cur_page
]);


$layout_content = include_template("layout.php", [
    "cats_arr" => $cats_arr,
    "content" => $page_content,
    "search_text" => $search_text,
    "title" => "Главная"
]);

}

print($layout_content);


?>