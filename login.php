<?php
session_start();
require_once "db_connect.php";
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: personal_account.php");
        exit;
    } else {
        $msg = "Неверный логин или пароль.";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
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

        .login-form {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .login-form h2 {
            text-align: center;
            color: #2e86de;
            margin-bottom: 20px;
        }

        .login-form label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        .login-form input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .login-form button {
            margin-top: 25px;
            width: 100%;
            padding: 12px;
            background: #2e86de;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        .login-form button:hover {
            background: #1b65c3;
        }

        .message {
            text-align: center;
            margin-top: 15px;
            color: red;
        }
    </style>
</head>
<body>

<header>
    <h1>Вход на сайт</h1>
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

<div class="login-form">
    <h2>Вход</h2>
    <form method="post">
        <label for="login">Логин:</label>
        <input type="text" name="login" id="login" required>

        <label for="password">Пароль:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Войти</button>
    </form>

    <?php if ($msg): ?>
        <div class="message"><?= $msg ?></div>
    <?php endif; ?>
</div>

</body>
</html>
