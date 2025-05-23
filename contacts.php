<?php
$msg = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    $to = "admin@yourdomain.ru"; // замени на свою почту
    $subject = "Сообщение с сайта Ресторана";
    $body = "Имя: $name\nEmail: $email\n\nСообщение:\n$message";
    $headers = "From: $email";

    if (mail($to, $subject, $body, $headers)) {
        $msg = "Сообщение отправлено!";
    } else {
        $msg = "Ошибка отправки. Попробуйте позже.";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Контакты</title>
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

        .contact-container {
            max-width: 700px;
            margin: 40px auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .contact-container h2 {
            color: #2e86de;
            margin-bottom: 20px;
        }

        .contact-container p {
            font-size: 16px;
        }

        form label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        form input, form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        form button {
            margin-top: 20px;
            background: #2e86de;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 6px;
            width: 100%;
            font-size: 16px;
        }

        .message {
            margin-top: 15px;
            text-align: center;
            color: green;
        }
    </style>
</head>
<body>

<header>
    <h1>Контакты</h1>
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

<div class="contact-container">
    <h2>Наши контакты</h2>
    <p><strong>Адрес:</strong> г. Санкт-Петербург, пр. Ресторана, 1</p>
    <p><strong>Телефон:</strong> +7 (812) 123-45-67</p>
    <p><strong>Email:</strong> admin@yourdomain.ru</p>

    <h3>Написать нам</h3>
    <form method="post">
        <label for="name">Имя:</label>
        <input type="text" name="name" required>

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="message">Сообщение:</label>
        <textarea name="message" rows="5" required></textarea>

        <button type="submit">Отправить сообщение</button>
    </form>

    <?php if ($msg): ?>
        <p class="message"><?= $msg ?></p>
    <?php endif; ?>
</div>

</body>
</html>
