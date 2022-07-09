<?php

/**
 * Формирует SQL-запрос для получения списка новых лотов от определенной даты, с сортировкой
 * @param string $date Дата в виде строки, в формате 'YYYY-MM-DD'
 * @return string SQL-запрос
 */
function get_lots_promo($sql_con, $date) {
    $sql = "SELECT lots.id, lots.title, lots.start_price, lots.img, date_finish, categories.cat_name FROM lots 
    JOIN categories ON lots.category_id = categories.id
    WHERE lots.date_creation > '$date' AND lots.status = 'open'
    ORDER BY lots.date_finish DESC
    LIMIT 6;";

    $res = mysqli_query($sql_con, $sql);
    if ($res) {
        $lots_arr = mysqli_fetch_all($res, MYSQLI_ASSOC);
        return $lots_arr;
    } else {
        $error = mysqli_error($sql_con);
        return $error;
    }
}


function get_all_lots($sql_con, $category_id) {
    $sql = "SELECT lots.*, categories.cat_name FROM lots
    JOIN categories ON lots.category_id = categories.id
    WHERE category_id = '$category_id' AND status = 'open';";

    $res = mysqli_query($sql_con, $sql);
    if ($res) {
        $all_lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
        return $all_lots;
    } else {
        $error = mysqli_error($sql_con);
        return $error;
    }
}


/**
 * Формирует SQL-запрос для получения категорий
 * @param string none
 * @return string SQL-запрос
 */
function get_categories() {
    return "SELECT id, cat_code, cat_name FROM categories;";
}

function get_cur_category($sql_con) {
    $id = htmlspecialchars($_GET["category"]);
    $sql = "SELECT id, cat_code, cat_name FROM categories
    WHERE id = '$id';";

    $res = mysqli_query($sql_con, $sql);
    if ($res) {
        $category = mysqli_fetch_assoc($res);
        return $category;
    } else {
        $error = mysqli_error($sql_con);
        return $error;
    }
}

$get_cat = mysqli_query($sql_con, get_categories());
if ($get_cat) {
    $cats_arr = mysqli_fetch_all($get_cat, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($sql_con);
}

/**
 * Формирует SQL-запрос для внесения данных формы
 * @param string поля формы
 * @return string SQL-запрос
 */
function create_lot($author_id) {
    return "INSERT INTO lots (title, category_id, lot_description, start_price, bet_step, date_finish, img, author_id) VALUES (?, ?, ?, ?, ?, ?, ?, $author_id);";
}


function check_login($email) {
    return "SELECT email, user_password FROM user WHERE email = '$email';";
}

function user_data($email) {
    return "SELECT * FROM user WHERE email = '$email';";
}

function create_user() {
    return "INSERT INTO user (email, user_password, user_name, contacts) VALUES (?, ?, ?, ?);";
}

function fulltext_search($con, $text, $limit, $offset) {
    $sql = "SELECT * FROM lots l JOIN categories c ON l.category_id = c.id WHERE MATCH(title,lot_description) AGAINST(?) AND status = 'open' ORDER BY date_creation DESC LIMIT $limit OFFSET $offset;";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $text);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        $lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
        return $lots;
    }
    $error = mysqli_error($con);
    return $error;
}

/**
 * Возвращает количество лотов соответствующих поисковым словам
 * @param $link mysqli Ресурс соединения
 * @param string $words ключевые слова введенные ползователем в форму поиска
 * @return [int | String] $count Количество лотов, в названии или описании которых есть такие слова
 * или описание последней ошибки подключения
 */
function get_count_lots($link, $words) {
    $sql = "SELECT COUNT(*) as cnt FROM lots
    WHERE MATCH(title, lot_description) AGAINST(?) AND status = 'open'";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 's', $words);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        $count = mysqli_fetch_assoc($res)["cnt"];
        return $count;
    }
    $error = mysqli_error($con);
    return $error;
}


function add_bet() {
    return "INSERT INTO bets (price_bet, user_id, lot_id) VALUES (?, ?, ?);";
}


function get_history_bets($sql_con, $lot_id) {
    $sql = "SELECT DATE_FORMAT(bets.date_bet, '%d.%m.%y %H:%i') AS date_bet, bets.price_bet, user.user_name FROM bets JOIN user ON bets.user_id = user.id WHERE bets.lot_id = $lot_id ORDER BY bets.date_bet DESC LIMIT 10;";

    $res = mysqli_query($sql_con, $sql);
    if ($res) {
        $bets = mysqli_fetch_all($res, MYSQLI_ASSOC);;
        return $bets;
    }
    $error = mysqli_error($sql_con);
    return $error;
}

function get_user_bets($sql_con, $user_id) {
    $sql = "SELECT lots.id, lots.title, lots.lot_description, lots.date_finish, lots.img, MAX(bets.price_bet) AS price_bet, winner_id, DATE_FORMAT(MAX(bets.date_bet), '%d.%m.%y %H:%i') AS date_bet, categories.cat_name FROM bets
    JOIN lots ON bets.lot_id = lots.id
    JOIN categories ON lots.category_id = categories.id
    WHERE bets.user_id = '45'
    GROUP BY lots.id
    ORDER BY date_bet DESC;";

    $res = mysqli_query($sql_con, $sql);
    if ($res) {
        $user_bets = mysqli_fetch_all($res, MYSQLI_ASSOC);;
        return $user_bets;
    }
    $error = mysqli_error($sql_con);
    return $error;
}

function get_finished_lots($sql_con) {
    $sql = "SELECT id, title FROM lots
    WHERE date_finish < NOW() AND status = 'open'
    GROUP BY id;";

    $res = mysqli_query($sql_con, $sql);
    if ($res) {
        $finished_lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
        return $finished_lots;
    }
    $error = mysqli_error($sql_con);
    return $error;
}

function get_finished_bets($sql_con, $id) {
    $sql = "SELECT date_bet, MAX(price_bet) AS price_bet, user_id, lot_id FROM bets
    WHERE lot_id = '$id';";

    $res = mysqli_query($sql_con, $sql);
    if ($res) {
        $finished_bets = mysqli_fetch_all($res, MYSQLI_ASSOC);
        return $finished_bets;
    }
    $error = mysqli_error($sql_con);
    return $error;
}

function get_winner($sql_con, $id) {
    $sql = "SELECT user_id, MAX(price_bet) AS price_bet FROM bets
    WHERE lot_id = '$id'
    GROUP BY id;";

    $res = mysqli_query($sql_con, $sql);
    if ($res) {
        $winner = mysqli_fetch_all($res, MYSQLI_ASSOC);
        return $winner;
    }
    $error = mysqli_error($sql_con);
    return $error;
}

function close_lots($sql_con, $id) {
    $sql = "UPDATE lots
    SET status = 'closed'
    WHERE id = '$id';";

    $res = mysqli_query($sql_con, $sql);
}

function set_winner($sql_con, $user_id, $id) {
    $sql = "UPDATE lots
    SET winner_id = '$user_id'
    WHERE id = '$id';";

    $res = mysqli_query($sql_con, $sql);
}