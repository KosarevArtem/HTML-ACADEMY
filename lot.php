<?php
session_start();

require_once("helpers.php");
require_once("data.php");
require_once("init.php");
require_once("sql_functions.php");
require_once("functions.php");
require_once("getwinner.php");

$lot_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$sql_get_lots = "SELECT lots.status, lots.title, lots.lot_description, lots.start_price, bet_step, lots.img, date_finish, categories.cat_name FROM lots 
JOIN categories ON lots.category_id = categories.id 
WHERE lots.id = $lot_id";
$get_lot = mysqli_query($sql_con, $sql_get_lots);
if ($get_lot) {
    $lot_array = mysqli_fetch_assoc($get_lot);
} else {
    $error = mysqli_error($sql_con);
}

if ($lot_array) {
  $lot = $lot_array;
} else {
  http_response_code(404);
    die();
}

$bets = get_history_bets($sql_con, $lot_id);

if (!empty($bets)) {
  $current_price = max($lot["start_price"], $bets[0]["price_bet"]);
} else {
  $current_price = $lot["start_price"];
}

$min_bet = $current_price + $lot["bet_step"];

$header = include_template("header.php", [
  "cats_arr" => $cats_arr
]);

$page_content = include_template("lot.php", [
  "header" => $header,
  "lot" => $lot,
  "lot_id" => $lot_id,
  "bets" => $bets,
  "min_bet" => $min_bet,
  "current_price" => $current_price
]);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if ($lot['status'] == "open") {
    $errors = [];
    $cost = htmlspecialchars($_POST["cost"]);
  
    if (!is_numeric($cost)) {
      $errors["cost"] = "Неверный формат ставки";
    }
    if ($cost < $min_bet) {
      $errors["cost"] = "Слишком маленькая ставка";
    }
    if (empty($cost)) {
      $errors["cost"] = "Ставку нужно заполнить";
    }
  
    $errors = array_filter($errors);
  
    if (count($errors)) {
      $page_content = include_template("lot.php", [
          "header" => $header,
          "lot" => $lot,
          "lot_id" => $lot_id,
          "bets" => $bets,
          "min_bet" => $min_bet,
          "current_price" => $current_price,
          "errors" => $errors
      ]);
  } else {
    $bet_data = [
      "price_bet" => $cost,
      "user_id" => $_SESSION["id"],
      "lot_id" => $lot_id
    ];
  
    $sql = add_bet();
    $stmt = db_get_prepare_stmt_version($sql_con, $sql, $bet_data);
    $res = mysqli_stmt_execute($stmt);
    if ($res) {
      header("Location: /lot.php?id=" .$lot_id);
    }
  
  }
} else {
  header("Refresh:0");
}
  

}


$layout_content = include_template("layout.php", [
  "cats_arr" => $cats_arr,
  "content" => $page_content,
  "title" => "Главная"
]);


print($layout_content);


?>