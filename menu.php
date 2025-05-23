<?php
require_once "db_connect.php";

// Получение параметров фильтра и сортировки
$filter = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? '';

// Базовый SQL-запрос
$query = "SELECT * FROM блюда";

// Фильтрация по названию
if (!empty($filter)) {
    $filter = mysqli_real_escape_string($conn, $filter);
    $query .= " WHERE Наименование_блюда LIKE '%$filter%'";
}

// Сортировка по цене
if ($sort === 'price_asc') {
    $query .= " ORDER BY Стоимость ASC";
} elseif ($sort === 'price_desc') {
    $query .= " ORDER BY Стоимость DESC";
}

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Меню ресторана</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
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
            margin: 10px 20px;
            padding: 10px 15px;
            background-color: #2980b9;
            border-radius: 5px;
            transition: 0.3s;
        }

        nav a:hover {
            background-color: #3498db;
        }

        .filters {
            max-width: 1000px;
            margin: 20px auto;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
        }

        .filters form {
            display: flex;
            gap: 10px;
        }

        .filters input, .filters select {
            padding: 6px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .filters button {
            padding: 6px 12px;
            background: #2e86de;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .menu-grid {
            max-width: 1000px;
            margin: 20px auto 40px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 25px;
            padding: 20px;
        }

        .dish-card {
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            text-align: center;
        }

        .dish-card:hover {
            transform: translateY(-3px);
        }

        .dish-card a {
            text-decoration: none;
            color: inherit;
        }

        .dish-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .dish-content {
            padding: 15px;
        }

        .dish-content h3 {
            margin: 10px 0;
            font-size: 18px;
            color: #2e86de;
        }

        .dish-content p {
            margin: 0;
            color: #333;
        }

        footer {
            text-align: center;
            padding: 15px;
            background: #2e86de;
            color: white;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<header>
    <h1>Меню ресторана</h1>
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

<div class="filters">
    <form method="get">
        <input type="text" name="search" placeholder="Поиск по названию..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <select name="sort">
            <option value="">Без сортировки</option>
            <option value="price_asc" <?= ($sort === 'price_asc') ? 'selected' : '' ?>>Сначала дешёвые</option>
            <option value="price_desc" <?= ($sort === 'price_desc') ? 'selected' : '' ?>>Сначала дорогие</option>
        </select>
        <button type="submit">Применить</button>
    </form>
</div>

<div class="menu-grid">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="dish-card">
            <a href="dish.php?id=<?= $row['Код_блюда'] ?>">
                <img src="uploads/<?= htmlspecialchars($row['Фото']) ?>" alt="Блюдо">
                <div class="dish-content">
                    <h3><?= htmlspecialchars($row['Наименование_блюда']) ?></h3>
                    <p><strong>Цена:</strong> <?= htmlspecialchars($row['Стоимость']) ?> ₽</p>
                </div>
            </a>
        </div>
    <?php endwhile; ?>
</div>

<footer>
    &copy; <?= date('Y') ?> Ресторан. Все права защищены.
</footer>

</body>
</html>
