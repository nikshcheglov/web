<?php
$host = 'localhost';
$db = 'restaurantdb';
$user = 'root';
$pass = '';
$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Ошибка подключения: " . mysqli_connect_error());
}
?>