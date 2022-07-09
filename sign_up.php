<?php

require_once("helpers.php");
require_once("data.php");
require_once("init.php");
require_once("sql_functions.php");
require_once("functions.php");

$header = include_template("header.php", [
    "cats_arr" => $cats_arr
]);

$page_content = include_template("sign_up.php", [
    "header" => $header
]);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $required_fields = ['email', 'password', 'name', 'message'];
    $errors = [];

    $user = filter_input_array(INPUT_POST,
    [
    "email"=>FILTER_DEFAULT,
    "password"=>FILTER_DEFAULT,
    "name"=>FILTER_DEFAULT,
    "message"=>FILTER_DEFAULT
], true);

foreach ($user as $field => $value) {
    if ($field == "email") {
        $email_error = "";
        if (empty($value)) {
            $email_error = 'Введите e-mail';
        } elseif (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors[$field] = 'Email должен быть корректным';
            $email_error = 'Email должен быть корректным';
        } else {
            $email = mysqli_real_escape_string($sql_con, $user['email']);
            $sql = check_login($email);
            $check_email = mysqli_query($sql_con, $sql);
            $email_array = mysqli_fetch_assoc($check_email);
            if (!empty($email_array['email'])) {
                $errors[$field] = 'Такой email уже занят';
                $email_error = 'Такой email уже занят';
            }
        }
    }
    if (in_array($field, $required_fields) && empty($value)) {
        $errors[$field] = "Поле $field нужно заполнить";
    }
}
print_r($errors);
echo $email_error;
$errors = array_filter($errors);
if (count($errors)) {
    $page_content = include_template("sign_up.php", [
        "header" => $header,
        "errors" => $errors,
        "email_error" => $email_error
    ]);
} else {
    $password = $user['password'];
    $user['password'] = password_hash($password, PASSWORD_DEFAULT);

    $sql = create_user();
    $stmt = db_get_prepare_stmt_version($sql_con, $sql, $user);
    $res = mysqli_stmt_execute($stmt);
    if ($res) {
        header("Location: /login.php");
    } else {
        mysqli_error();
    }
}

}


$layout_content = include_template("layout.php", [
    "cats_arr" => $cats_arr,
    "content" => $page_content,
    "user_name" => $user_name,
    "title" => "Главная",
    "is_auth" => $is_auth
]);


print($layout_content);