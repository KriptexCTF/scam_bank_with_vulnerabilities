<?php
$message = "";
$username = "";
$db_connect = pg_connect("host='localhost' port='5432' dbname='postgres' user='postgres' password='44236'");
if (isset($_COOKIE['User'])) {
    $username = $_COOKIE['User'];
} else{
    header("Location: login.php");
}
$insert_query = "SELECT username, email, wallet, money FROM accounts WHERE username='$username'";
$insert_result = pg_query($db_connect, $insert_query);
if (!$insert_result) {
    $message = "Ошибка выполнения запроса";
} else {
    while ($row = pg_fetch_assoc($insert_result)) {
        $username = $row['username'];
        $email = $row['email'];
        $wallet = $row['wallet'];
        $money = $row['money'];
    }
}
if (isset($_POST['submit'])) {
            $wallet_send = $_POST['wallet_send'];
            $money_send =$_POST['money_send'];
            if (!$wallet_send || !$money_send){
              $message = 'Пожалуйста введите все значения!';
            } else {
                if($money_send > $money){
                    $message = 'Недостаточно средств!';
                } else{
                    $insert_query = "SELECT id FROM accounts WHERE wallet='$wallet_send'";
                    $insert_result = pg_query($db_connect, $insert_query);
                    $login_check = pg_num_rows($insert_result);
                    if ($login_check > 0) {
                        $money -= $money_send;
                        $sql = "UPDATE accounts SET money = $money WHERE wallet = '$wallet'";
                        $insert_result = pg_query($db_connect, $sql);
                        $insert_query = "SELECT money FROM accounts WHERE wallet='$wallet_send'";
                        $insert_result = pg_query($db_connect, $insert_query);
                        while ($row = pg_fetch_assoc($insert_result)) {
                                $money_recipient = $row['money'];
                        }
                        $money_recipient += $money_send;
                        $sql = "UPDATE accounts SET money = $money_recipient WHERE wallet = '$wallet_send'";
                        $insert_result = pg_query($db_connect, $sql);
                    }
                    else {
                        $message = "Такого счета не существует!";
                    }

                }
            }

}
?>
<!DOCTYPE html>
<html lang="ru" class="profile">
<head>
    <meta charset="UTF-8">
    <title>Прогресс Банк</title>
    <link rel="stylesheet" href="css/profile.css">
    <div class="navigation">
      <nav class="menu">
        <ul>
          <li><img src="image/logo.png" class="logo"></li>
          <li><a href="index.php">Главная</a></li>
          <li style="float:right"><a id="exit" class="exit_button">Выйти из аккаунта</a></li>
          <li style="float:right"><a style="background-color: white; font-weight: bold" class="username"><?php echo $username?></a></li>
          <script>
              document.getElementById('exit').addEventListener('click', function(e) {
                e.preventDefault();
                document.cookie = "User=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                window.location.href = 'login.php';
              });
          </script>
        </ul>
      </nav>
    </div>
</head>
<body>
  <div class="container-1">
    <div class="about">
      <img src="image/anonymous-user.png" class="image1">
        <div style="line-height: 10px; margin: 1px" class="info_text">
            <p style="font-size: 24px">Username:  <?php echo $username?></p>
            <p style="font-size: 24px">email:  <?php echo $email?></p>
            <p style="font-size: 24px">Счет:  <?php echo $wallet?></p>
            <p style="font-size: 24px">Баланс:  <?php echo $money?> ₽</p>
        </div>
    </div>
  </div>
  <div class="container-2">
      <div class="post_form">
          <form method="POST" action="profile.php" enctype="multipart/form-data" name="transver">
              <p class="form_text">Перевод средств</p>
              <input type="wallet_send" class="acc_number" name="wallet_send" placeholder="Введите номер счета на который собираетесь перевести">
              <input type="money_send" class="acc_number" name="money_send" placeholder="Введите сумму для перевода">
              <button type="submit" class="button_js" name="submit">Отправить деньги</button>
          </form>
          <p class="message"><?php echo $message?></p>
      </div>
  </div>
</body>
</html>
