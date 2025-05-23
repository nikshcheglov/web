<?php
require_once "db_connect.php";
$result = mysqli_query($conn, "SELECT * FROM news ORDER BY date_posted DESC");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Новости</title>
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

        .news-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .news-item {
            margin-bottom: 30px;
        }

        .news-item h3 {
            margin-bottom: 5px;
            color: #2e86de;
        }

        .news-item small {
            color: #777;
        }

        .news-item p {
            margin-top: 10px;
        }
    </style>
</head>
<body>

<header>
    <h1>Новости ресторана</h1>
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

<div class="news-container">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="news-item">
            <h3><?= htmlspecialchars($row['title']) ?></h3>
            <small><?= date("d.m.Y H:i", strtotime($row['date_posted'])) ?></small>
            <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
