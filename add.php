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

if (!isset($_SESSION['id'])) {
    $page_content = include_template("403.php", [
    ]);
    $layout_content = include_template("layout.php", [
        "cats_arr" => $cats_arr,
        "content" => $page_content,
        "user_name" => $user_name,
        "title" => "Доступ запрещен"
    ]);
    print($layout_content);
    die();
}


$categories_id = array_column($cats_arr, "id");

$page_content = include_template("add.php", [
    "header" => $header,
    "cats_arr" => $cats_arr
]);



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // валидация
    $required_fields = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
    $errors = [];
    $rules = [
        "category" => function($value) use ($categories_id) {
            return validate_category($value, $categories_id);
        },
        "lot-rate" => function($value) {
            return validate_number ($value);
        },
        "lot-step" => function($value) {
            return validate_number ($value);
        },
        "lot-date" => function($value) {
            return validate_date ($value);
        }
    ];

    $lot = filter_input_array(INPUT_POST,
        [
        "lot-name"=>FILTER_DEFAULT,
        "category"=>FILTER_DEFAULT,
        "message"=>FILTER_DEFAULT,
        "lot-rate"=>FILTER_DEFAULT,
        "lot-step"=>FILTER_DEFAULT,
        "lot-date"=>FILTER_DEFAULT
    ], true);

    foreach ($lot as $field => $value) {
        if (isset($rules[$field])) {
            $rule = $rules[$field];
            $errors[$field] = $rule($value);
        }
        if (in_array($field, $required_fields) && empty($value)) {
            $errors[$field] = "Заполните поле";
        }
    }
    
    $errors = array_filter($errors);

    if (!empty($_FILES['image']['name'])) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_tmp_name = $_FILES['image']['tmp_name'];
        $file_size = $_FILES['image']['size'];

        $file_type = finfo_file($finfo, $file_tmp_name);
      
        $file_error = "";
        if ($file_size > 2000000) {
            $file_error = "Максимальный размер файла: 200кб";
            $errors['image'] = 'Слишком большой размер файла';
        }

        if ($file_type === "image/jpeg") {
            $ext = ".jpg";
        } else if ($file_type === "image/png") {
            $ext = ".png";
        };
        if (isset($ext)) {
            $filename = uniqid() . $ext;
            $lot['path'] = "uploads/". $filename;
            move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/". $filename);
        } else {
            $errors["image"] = "Допустимые форматы файлов: jpg, jpeg, png";
            $file_error = "Загрузите картинку в форматах jpg, jpeg, png";
            
        }

    } else {
        $errors['image'] = 'Файл не загружен';
        $file_error = 'Загрузите изображение';
    }
    
    if (count($errors)) {
        $page_content = include_template("add.php", [
            "header" => $header,
            "cats_arr" => $cats_arr,
            "errors" => $errors,
            "file_error" => $file_error
        ]);
    } else {
    // sql запрос
    $sql = create_lot($_SESSION['id']);
    $stmt = db_get_prepare_stmt_version($sql_con, $sql, $lot);
    $res = mysqli_stmt_execute($stmt);
    if ($res) {
        $lot_id = mysqli_insert_id($sql_con);
        header("Location: /lot.php?id=$lot_id");
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