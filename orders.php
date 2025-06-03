<?php
session_start();
require_once "db_connect.php";
if (!isset($_SESSION['admin_logged_in'])) header("Location: login.php");

// Удаление завершённого заказа
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_order'])) {
    $id = (int)$_POST['delete_order'];
    $res = mysqli_query($conn, "SELECT status FROM orders WHERE id = $id");
    $order = mysqli_fetch_assoc($res);
    if ($order && $order['status'] === 'завершён') {
        mysqli_query($conn, "DELETE FROM order_items WHERE order_id = $id");
        mysqli_query($conn, "DELETE FROM orders WHERE id = $id");
    }
    header("Location: orders.php");
    exit;
}

// Поиск
$search = $_GET['search'] ?? '';
$safe = mysqli_real_escape_string($conn, $search);
$query = "
    SELECT o.id, o.order_date, o.status, u.login 
    FROM orders o
    JOIN users u ON o.user_id = u.id
";
if ($search) {
    $query .= " WHERE u.login LIKE '%$safe%'";
}
$query .= " ORDER BY o.order_date DESC";
$orders = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заказы</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header><h1>Список заказов</h1></header>
<nav>
    <a href="index.php">Главная</a>
    <a href="dishes.php">Блюда</a>
    <a href="users.php">Пользователи</a>
    <a href="orders.php">Заказы</a>
	<a href="http://lr-4/" target="_blank">Клиентская часть</a>
    <a href="logout.php">Выход</a>
</nav>
<div class="container">
    <form method="get" style="margin-bottom:15px;">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Поиск по логину">
        <button type="submit">🔍 Найти</button>
        <?php if ($search): ?>
            <a href="orders.php" style="margin-left: 10px;">Очистить</a>
        <?php endif; ?>
    </form>

    <form method="post">
        <table>
            <tr><th>ID</th><th>Клиент</th><th>Дата</th><th>Статус</th><th>Детали</th><th>Удалить</th></tr>
            <?php while ($o = mysqli_fetch_assoc($orders)): ?>
            <tr>
                <td><?= $o['id'] ?></td>
                <td><?= htmlspecialchars($o['login']) ?></td>
                <td><?= date("d.m.Y H:i", strtotime($o['order_date'])) ?></td>
                <td><?= $o['status'] ?></td>
                <td><a href="order_details.php?id=<?= $o['id'] ?>">👁️</a></td>
                <td>
                    <?php if ($o['status'] === 'завершён'): ?>
                        <button type="submit" name="delete_order" value="<?= $o['id'] ?>" onclick="return confirm('Удалить заказ?')">🗑️</button>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </form>
</div>
</body>
</html>
