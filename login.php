<?php
session_start();

require_once("helpers.php");
require_once("data.php");
require_once("init.php");
require_once("sql_functions.php");
require_once("functions.php");

if (isset($_SESSION['id'])) {
    header("Location: /index.php");
    exit();

} else {
    $header = include_template("header.php", [
        "cats_arr" => $cats_arr
    ]);
    
    $page_content = include_template("login.php", [
        "header" => $header
    ]);
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $required_fields = ['email', 'password'];
        $errors = [];
    
        $user = filter_input_array(INPUT_POST,
        [
        "email"=>FILTER_DEFAULT,
        "password"=>FILTER_DEFAULT
        ], true);
    
        $mail_error = "";
        $password_error = "";
        $auth_error = "";
    
        foreach ($user as $field => $value) {
            if ($field == "email") {
                if (empty($value)) {
                    $errors[$field] = "Введите e-mail";
                    $mail_error = "Введите e-mail";
                } elseif (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = "Email должен быть корректным";
                    $mail_error = "Email должен быть корректным";
                }
            }
            if ($field == "password") {
                if (empty($value)) {
                    $errors[$field] = "Введите пароль";
                    $password_error = "Введите пароль";
                }
            }
        }
        if (empty($errors)) {
            $email = mysqli_real_escape_string($sql_con, $user['email']);
            $sql = check_login($email);
            $check_login = mysqli_query($sql_con, $sql);
            $login_array = mysqli_fetch_assoc($check_login);
            if (empty($login_array["email"])) {
                $auth_error = "Неправильный email или пароль";
                $errors["auth"] = "Неправильный email или пароль";
            } else {
                $password = mysqli_real_escape_string($sql_con, $user['password']);
                if (!password_verify($password, $login_array["user_password"])) {
                    $auth_error = 'Неправильный email или пароль';
                    $errors["auth"] = "Неправильный email или пароль";
                }
            }
        }
    
    if (count($errors)) {
        $page_content = include_template("login.php", [
            "header" => $header,
            "errors" => $errors,
            "auth_error" => $auth_error,
            "mail_error" => $mail_error,
            "password_error" => $password_error
        ]);
    } else {
        $sql = user_data($email);
        $check_data = mysqli_query($sql_con, $sql);
        $data_array = mysqli_fetch_assoc($check_data);
        $user_id = $data_array["id"];
        $name = $data_array["user_name"];
        
        $issession = session_start();
    
        $_SESSION['name'] = $data_array["user_name"];
        $_SESSION['id'] = $data_array["id"];
        
        header("Location: /index.php");
        exit();
    }

    }

}




$layout_content = include_template("layout.php", [
    "cats_arr" => $cats_arr,
    "content" => $page_content,
    "user_name" => $user_name,
    "title" => "Главная"
]);


print($layout_content);