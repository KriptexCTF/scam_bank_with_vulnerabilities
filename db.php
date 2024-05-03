<?php
$host = 'localhost';
$port = '5432';
$dbname = 'postgres';
$user = 'postgres';
$password = '44236';

$connect_data = "host=$host port=$port dbname=$dbname user=$user password=$password";
$db_connect = pg_connect($connect_data);

$query_users = "CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL
)";
$result = pg_query($db_connect, $query_users);
if (!$result) {
    echo "Не удалось создать таблицу users";
}
$query_accounts = "CREATE TABLE IF NOT EXISTS accounts (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    wallet VARCHAR(50) NOT NULL,
    money INTEGER NOT NULL
)";
$result = pg_query($db_connect, $query_accounts);
if (!$result) {
    echo "Не удалось создать таблицу accounts";
}
?>
