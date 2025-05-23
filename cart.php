<?php
session_start();
require_once "db_connect.php";

$cart = $_SESSION['cart'] ?? [];
$total = 0;
$items = [];
$order_error = "";


if (!empty($cart)) {
    $ids = implode(",", array_keys($cart));
    $result = mysqli_query($conn, "SELECT * FROM блюда WHERE Код_блюда IN ($ids)");
    while ($row = mysqli_fetch_assoc($result)) {
        $row['quantity'] = $cart[$row['Код_блюда']];
        $row['subtotal'] = $row['Стоимость'] * $row['quantity'];
        $total += $row['subtotal'];
        $items[] = $row;
    }
}

// Оформление заказа
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['place_order'])) {
    if (isset($_SESSION['user_id']) && !empty($items)) {
        $user_id = $_SESSION['user_id'];
        mysqli_query($conn, "INSERT INTO orders (user_id) VALUES ($user_id)");
        $order_id = mysqli_insert_id($conn);

        foreach ($items as $item) {
            $dish_id = $item['Код_блюда'];
            $qty = $item['quantity'];
            mysqli_query($conn, "INSERT INTO order_items (order_id, dish_id, quantity) VALUES ($order_id, $dish_id, $qty)");
        }

        unset($_SESSION['cart']);
        header("Location: personal_account.php?order_success=1");
        exit;
    }else {
        $order_error = "Чтобы оформить заказ, войдите в аккаунт.";
	}
}

// Очистка корзины
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['clear'])) {
    unset($_SESSION['cart']);
    header("Location: cart.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Корзина</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: #f5f7fb;
            font-family: 'Segoe UI', sans-serif;
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

        .cart-container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background: #f0f2f5;
        }

        .total {
            text-align: right;
            font-weight: bold;
            font-size: 18px;
            margin-top: 20px;
        }

        .actions {
            text-align: center;
            margin-top: 25px;
        }

        .actions button {
            padding: 10px 20px;
            background: #2e86de;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin: 5px;
        }

        .actions button:hover {
            background: #1b65c3;
        }
    </style>
</head>
<body>

<header>
    <h1>Ваша корзина</h1>
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
</nav>

<div class="cart-container">
    <?php if (!empty($items)): ?>
        <table>
            <tr>
                <th>Блюдо</th>
                <th>Цена</th>
                <th>Кол-во</th>
                <th>Сумма</th>
            </tr>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['Наименование_блюда']) ?></td>
                    <td><?= $item['Стоимость'] ?> ₽</td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= $item['subtotal'] ?> ₽</td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="total">Итого: <?= $total ?> ₽</div>

        <div class="actions">
			<?php if ($order_error): ?>
				<p style="color: red; margin-bottom: 15px;"><?= $order_error ?></p>
			<?php endif; ?>

			<form method="post">
				<button type="submit" name="place_order">Оформить заказ</button>
				<button type="submit" name="clear">Очистить корзину</button>
			</form>
		</div>

    <?php else: ?>
        <p>Ваша корзина пуста.</p>
    <?php endif; ?>
</div>

</body>
</html>
