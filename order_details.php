<?php
session_start();
require_once "db_connect.php";
if (!isset($_SESSION['admin_logged_in'])) header("Location: login.php");

$order_id = (int)$_GET['id'];

$order_info = mysqli_query($conn, "
    SELECT o.order_date, u.login FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.id = $order_id
");
$info = mysqli_fetch_assoc($order_info);

$items = mysqli_query($conn, "
    SELECT b.Наименование_блюда, b.Стоимость, oi.quantity
    FROM order_items oi
    JOIN блюда b ON oi.dish_id = b.Код_блюда
    WHERE oi.order_id = $order_id
");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Детали заказа</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header><h1>Детали заказа №<?= $order_id ?></h1></header>
<nav>
    <a href="orders.php">← Назад к заказам</a>
</nav>
<div class="container">
    <p><strong>Клиент:</strong> <?= htmlspecialchars($info['login']) ?></p>
    <p><strong>Дата заказа:</strong> <?= $info['order_date'] ?></p>
    <table>
        <tr><th>Блюдо</th><th>Цена</th><th>Кол-во</th><th>Сумма</th></tr>
        <?php $total = 0; while ($row = mysqli_fetch_assoc($items)): ?>
            <tr>
                <td><?= htmlspecialchars($row['Наименование_блюда']) ?></td>
                <td><?= $row['Стоимость'] ?> ₽</td>
                <td><?= $row['quantity'] ?></td>
                <td><?= $row['Стоимость'] * $row['quantity'] ?> ₽</td>
            </tr>
            <?php $total += $row['Стоимость'] * $row['quantity']; ?>
        <?php endwhile; ?>
    </table>
    <p><strong>Итого:</strong> <?= $total ?> ₽</p>
</div>
</body>
</html>
