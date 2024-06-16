<?php
    $output_db = "";
    require_once('db.php');
    if (isset($_COOKIE['User'])) {
        	header("Location: profile.php");
    }
    $db_connect = pg_connect("host='localhost' port='5432' dbname='postgres' user='postgres' password='44236'");
    if (isset($_POST['submit'])) {
            $username = $_POST['login'];
            $password = $_POST['password'];
            //Защита sqli
            //$username = pg_escape_string($db_connect, $_POST['login']);
            //$password = pg_escape_string($db_connect, $_POST['password']);
            if (!$username || !$password){
                $output_db = 'Пожалуйста введите все значения!';
            } else {
                $insert_query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
                $insert_result = pg_query($db_connect, $insert_query);
                $login_check = pg_num_rows($insert_result);
                if ($login_check > 0) {
                    setcookie("User", $username, time()+7200);
                    header("Location: profile.php");
                }
                else {
                    $output_db = "Неверное имя или пароль!";
                }
            }
    }
?>
<html lang="ru" class="login">
<head>
    <meta charset="UTF-8">
    <title>Прогресс Банк</title>
    <link rel="stylesheet" href="css/log.css">
    <div class="navigation">
        <nav class="menu">
            <ul>
                <li><img src="image/logo.png" class="logo"></li>
                <li><a href="index.php">Главная</a></li>
                <li style="float:right"><a href="registration.php" class="registration_button">Registration</a></li>
            </ul>
        </nav>
    </div>
</head>
<body>
    <div class="container">
        <div class="reg_info">
            <h2>Вход</h2>
        </div>
        <div class="form">
            <form method="POST" action="login.php">
                <div><input type="login" name="login" class="form-1" placeholder="Login"></div>
                <div><input type="password" name="password" class="form-1" placeholder="Password"></div>
                <button type="submit" class="btn_reg" name="submit">Продолжить</button>
            </form>
            <p><?php echo $output_db?></p>
            <div class="link_to_reg">Если у вас не аккаунта <a href="registration.php">создайте его</a></div>
        </div>
    </div>
</body>
</html>
