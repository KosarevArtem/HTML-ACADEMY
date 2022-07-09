<?php

$sql_con = mysqli_connect("localhost", "root", "", "yeticave");

if ($sql_con == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
 }
 else {
    mysqli_set_charset($sql_con, "utf8");
};