<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Ресторан Online</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            color: #333;
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

        .content {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        footer {
            text-align: center;
            padding: 15px;
            background: #2e86de;
            color: white;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<header>
    <h1>Добро пожаловать в наш Ресторан Online</h1>
    <p>Вкусная еда, удобный заказ, быстрая доставка!</p>
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

<div class="content">
    <h2>О нашем ресторане</h2>
    <p>Мы предлагаем самые вкусные блюда и качественный сервис. Вы можете ознакомиться с нашими блюдами в галерее, выбрать подходящее в меню, оформить заказ и получить быструю доставку. Следите за новостями и специальными предложениями!</p>
</div>

<footer>
    &copy; <?= date('Y') ?> Ресторан Online. Все права защищены.
</footer>

</body>
</html>
