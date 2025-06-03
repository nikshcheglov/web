<?php
session_start();
require_once "db_connect.php";

$msg = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE login = ? AND is_admin = 1");
    if ($stmt === false) {
        die("Ошибка подготовки запроса: " . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name'] = $admin['login'];
        header("Location: index.php");
        exit;
    } else {
        $msg = "Неверный логин или пароль администратора.";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход администратора</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header><h1>Панель администратора</h1></header>
<nav>
    <a href="login.php">Вход</a>
</nav>
<div class="reg-form">
    <h2>Авторизация</h2>
    <form method="post">
        <label>Логин:</label>
        <input type="text" name="login" required><br><br>
        <label>Пароль:</label>
        <input type="password" name="password" required><br><br>
        <button type="submit">Войти</button>
    </form>
    <?php if ($msg): ?>
        <p style="color:red"><?= $msg ?></p>
    <?php endif; ?>
</div>
</body>
</html>