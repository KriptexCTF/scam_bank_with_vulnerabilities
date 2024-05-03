<?php
$output_db = "";
require_once('db.php');
$db_connect = pg_connect("host='localhost' port='5432' dbname='postgres' user='postgres' password='44236'");
if (isset($_COOKIE['User'])) {
     header("Location: login.php");
}
if (isset($_POST['submit'])){
        $email = $_POST['email'];
        $username = $_POST['login'];
        $password = $_POST['password'];
        if (!$email || !$username || !$password) {
            $output_db = 'Пожалуйста введите все значения!';
        } else {
            $check_query = "SELECT COUNT(*) FROM users WHERE username = $1";
            $check_result = pg_query_params($db_connect, $check_query, array($username));
            $exists = pg_fetch_result($check_result, 0, 0);
            if ($exists > 0) {
                $output_db = "Пользователь с таким логином уже существует";
            } else {
                // Добавление пользователя
                $insert_query = "INSERT INTO users (username, email, password) VALUES ($1, $2, $3)";
                $insert_result = pg_query_params($db_connect, $insert_query, array($username, $email, $password));
                if (!$insert_result) {
                    $output_db = "Не удалось добавить пользователя!";
                } else {
                    $unique_id = time();
                    $unique_id .= abs(crc32($username));
                    $unique_id .= abs(crc32($email));
                    $unique_id = str_replace("-", "", $unique_id);
                    $unique_id = substr($unique_id, 0, 10);
                    $insert_query = "INSERT INTO accounts (username, email, wallet, money) VALUES ('$username', '$email', '$unique_id', 10000)";
                    $insert_result = pg_query($db_connect, $insert_query);
                    if (!$insert_result) {
                                    $output_db = "Не удалось создать аккаунт в системе";

                    } else {
                        header("Location: login.php");
                    }
                }
            }
        }
}
?>
<!DOCTYPE html>
<html lang="ru" class="registration">
<head>
    <meta charset="UTF-8">
    <title>Прогресс Банк</title>
    <link rel="stylesheet" href="css/reg.css">
    <div class="navigation">
        <nav class="menu">
            <ul>
                <li><img src="image/logo.png" class="logo"></li>
                <li><a href="index.php">Главная</a></li>
                <li style="float:right"><a href="login.php" class="login_button">Login</a></li>
            </ul>
        </nav>
    </div>
</head>
<body>
    <div class="container">
        <div class="reg_info">
            <h2>Регистрация</h2>
        </div>
        <div class="form">
            <form method="POST" action="registration.php">
                <div><input type="email" name="email" class="form-1" placeholder="Email"></div>
                <div><input type="text" name="login" class="form-1" placeholder="Login"></div>
                <div><input type="password" name="password" class="form-1" placeholder="Password"></div>
                <button type="submit" class="btn_reg" name="submit">Продолжить</button>
            </form>
            <p><?php echo $output_db?></p>
        </div>
    </div>
  </body>
</html>
