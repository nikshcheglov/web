<?php
session_start();
require_once "db_connect.php";
if (!isset($_SESSION['admin_logged_in'])) header("Location: login.php");

$search = $_GET['search'] ?? '';
$search_sql = mysqli_real_escape_string($conn, $search);

// Обновление цен
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_prices'])) {
    foreach ($_POST['prices'] as $id => $price) {
        $id = (int)$id;
        $price = floatval($price);
        mysqli_query($conn, "UPDATE блюда SET Стоимость = $price WHERE Код_блюда = $id");
    }
    header("Location: dishes.php?search=" . urlencode($search));
    exit;
}

// Скидка
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['apply_discount'])) {
    $discount = floatval($_POST['discount']);
    if ($discount > 0 && $discount <= 100 && isset($_POST['discount_ids'])) {
        foreach ($_POST['discount_ids'] as $id) {
            $id = (int)$id;
            mysqli_query($conn, "UPDATE блюда SET Стоимость = Стоимость * (1 - $discount / 100) WHERE Код_блюда = $id");
        }
    }
    header("Location: dishes.php?search=" . urlencode($search));
    exit;
}

// Получение блюд
$query = "SELECT * FROM блюда";
if ($search) {
    $query .= " WHERE Наименование_блюда LIKE '%$search_sql%'";
}
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Блюда</title>
    <link rel="stylesheet" href="style.css">
    <style>
        img.thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; }
        input[type='number'] { width: 80px; }
        .add-button {
            background: #2e86de;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<header><h1>Управление блюдами</h1></header>
<nav>
    <a href="index.php">Главная</a>
    <a href="dishes.php">Блюда</a>
    <a href="users.php">Пользователи</a>
    <a href="orders.php">Заказы</a>
	<a href="http://lr-4/" target="_blank">Клиентская часть</a>
    <a href="logout.php">Выход</a>
</nav>
<div class="container">
    <a href="add_dish.php" class="add-button">➕ Добавить блюдо</a>

    <form method="get" style="margin-bottom:15px;">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Поиск по названию">
        <button type="submit">🔍</button>
        <?php if ($search): ?><a href="dishes.php" style="margin-left:10px;">Очистить</a><?php endif; ?>
    </form>

    <form method="post">
        <table>
            <tr><th>Фото</th><th>Название</th><th>Цена (₽)</th><th>Скидка</th><th>Действия</th></tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td>
                        <?php if (!empty($row['Фото'])): ?>
                            <img class="thumb" src="uploads/<?= htmlspecialchars($row['Фото']) ?>" alt="Фото">
                        <?php else: ?>❌<?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['Наименование_блюда']) ?></td>
                    <td>
                        <input type="number" step="0.01" name="prices[<?= $row['Код_блюда'] ?>]" value="<?= $row['Стоимость'] ?>">
                    </td>
                    <td style="text-align:center;">
                        <input type="checkbox" name="discount_ids[]" value="<?= $row['Код_блюда'] ?>">
                    </td>
                    <td>
                        <a href="edit_dish.php?id=<?= $row['Код_блюда'] ?>">✏️</a>
                        <a href="delete_dish.php?id=<?= $row['Код_блюда'] ?>" onclick="return confirm('Удалить блюдо?')">🗑️</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <div style="margin-top:20px;">
            <button type="submit" name="update_prices">💾 Обновить цены</button>
        </div>

        <div style="margin-top:15px;">
            <label>Скидка (%): 
                <input type="number" name="discount" step="1" min="1" max="100" required>
            </label>
            <button type="submit" name="apply_discount">💸 Применить скидку</button>
        </div>
    </form>
</div>
</body>
</html>
