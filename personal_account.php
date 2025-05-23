<?php
session_start();
require_once "db_connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f7fb;
            margin: 0;
        }

        header {
            background-color: #2e86de;
            color: white;
            padding: 20px;
            text-align: center;
        }

        nav {
            background-color: #1b4f72;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            padding: 10px 0;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 10px 15px;
            padding: 10px 15px;
            background-color: #2980b9;
            border-radius: 5px;
            transition: 0.3s;
        }

        nav a:hover {
            background-color: #3498db;
        }

        .profile-container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .profile-container h2 {
            color: #2e86de;
        }

        .profile-container img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }

        .profile-container p {
            font-size: 16px;
            margin: 5px 0;
        }

        .order-box {
            margin-top: 30px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        .order-box ul {
            padding-left: 18px;
            margin-top: 10px;
        }

        .order-box li {
            margin-bottom: 6px;
        }

        .logout-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #d9534f;
            color: white;
            border-radius: 6px;
            text-decoration: none;
        }

        .logout-link:hover {
            background: #c9302c;
        }
    </style>
</head>
<body>

<header>
    <h1>Личный кабинет</h1>
</header>

<nav>
    <a href="index.php">Главная</a>
    <a href="menu.php">Меню</a>
    <a href="gallery.php">Галерея</a>
    <a href="contacts.php">Контакты</a>
    <a href="news.php">Новости</a>
    <a href="register.php">Регистрация</a>
    <a href="login.php">Вход</a>
    <a href="personal_account.php">Кабинет</a>
    <a href="cart.php">Корзина</a>
    <a href="logout.php">Выход</a>
</nav>

<div class="profile-container">
    <h2>Здравствуйте, <?= htmlspecialchars($user['login']) ?>!</h2>

    <?php if (!empty($user['photo'])): ?>
        <img src="uploads/<?= htmlspecialchars($user['photo']) ?>" alt="Аватар">
    <?php endif; ?>

    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Дата регистрации:</strong> <?= htmlspecialchars($user['reg_date']) ?></p>

    <hr>
    <h3>Ваши заказы:</h3>

    <?php
    $orders_query = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = $user_id ORDER BY order_date DESC");

    if (mysqli_num_rows($orders_query) > 0):
        while ($order = mysqli_fetch_assoc($orders_query)):
            $order_id = $order['id'];
            $items_query = mysqli_query($conn, "
                SELECT b.Наименование_блюда, b.Стоимость, oi.quantity
                FROM order_items oi
                JOIN блюда b ON oi.dish_id = b.Код_блюда
                WHERE oi.order_id = $order_id
            ");
            $total = 0;
    ?>
        <div class="order-box">
            <p><strong>Заказ №<?= $order_id ?></strong> от <?= $order['order_date'] ?></p>
            <ul>
                <?php while ($item = mysqli_fetch_assoc($items_query)): ?>
                    <li>
                        <?= htmlspecialchars($item['Наименование_блюда']) ?> — 
                        <?= $item['quantity'] ?> шт. × <?= $item['Стоимость'] ?> ₽ = 
                        <strong><?= $item['Стоимость'] * $item['quantity'] ?> ₽</strong>
                    </li>
                    <?php $total += $item['Стоимость'] * $item['quantity']; ?>
                <?php endwhile; ?>
            </ul>
            <p><strong>Итого: <?= $total ?> ₽</strong></p>
        </div>
    <?php endwhile; ?>
    <?php else: ?>
        <p>Вы ещё не оформляли заказы.</p>
    <?php endif; ?>
</div>

</body>
</html>
