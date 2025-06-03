<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header><h1>Панель администратора</h1></header>
<nav>
	<a href="index.php">Главная</a>
    <a href="dishes.php">Блюда</a>
    <a href="users.php">Пользователи</a>
    <a href="orders.php">Заказы</a>
	<a href="http://lr-4/" target="_blank">Клиентская часть</a>
    <a href="logout.php">Выход</a>
</nav>
<div class="container">
    <p>Добро пожаловать, <strong><?= $_SESSION['admin_name'] ?></strong>!</p>
    <p>Выберите раздел для управления содержимым сайта.</p>
</div>
</body>
</html>