<?php
session_start();
require_once "db_connect.php";
if (!isset($_SESSION['admin_logged_in'])) header("Location: login.php");

$user_id = (int)$_GET['id'];
$user_result = mysqli_query($conn, "SELECT login FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($user_result);
if (!$user) { echo "Пользователь не найден."; exit; }

$orders = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = $user_id ORDER BY order_date DESC");
?>
<!DOCTYPE html>
<html lang="ru">
<head><meta charset="UTF-8"><title>Заказы пользователя</title>
<link rel="stylesheet" href="style.css"></head>
<body>
<header><h1>Заказы пользователя <?= htmlspecialchars($user['login']) ?></h1></header>
<nav>
    <a href="index.php">Главная</a>
    <a href="users.php">Пользователи</a>
</nav>
<div class="container">
    <table>
        <tr><th>ID</th><th>Дата</th><th>Детали</th></tr>
        <?php while ($o = mysqli_fetch_assoc($orders)): ?>
        <tr>
            <td><?= $o['id'] ?></td>
            <td><?= $o['order_date'] ?></td>
            <td><a href="order_details.php?id=<?= $o['id'] ?>">👁️</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
