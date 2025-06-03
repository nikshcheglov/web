<?php
session_start();
require_once "db_connect.php";
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $id"));

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST['login']);
    $email = trim($_POST['email']);

    mysqli_query($conn, "UPDATE users SET login='$login', email='$email' WHERE id = $id");
    header("Location: users.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать пользователя</title>
    <link rel="stylesheet" href="style.css">
    <style>
        form {
            max-width: 500px;
            margin: 40px auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        form label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        form input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        form button {
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
        form button:hover {
            background: #1b65c3;
        }
    </style>
</head>
<body>
<header><h1>Редактировать пользователя</h1></header>
<nav>
    <a href="index.php">Главная</a>
    <a href="dishes.php">Блюда</a>
    <a href="users.php">Пользователи</a>
    <a href="orders.php">Заказы</a>
    <a href="http://lr-4/" target="_blank">🌐 Клиентская часть</a>
    <a href="logout.php">Выход</a>
</nav>

<div class="container">
    <form method="post">
        <label>Логин:</label>
        <input type="text" name="login" value="<?= htmlspecialchars($user['login']) ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <button type="submit">💾 Сохранить</button>
    </form>
</div>
</body>
</html>
