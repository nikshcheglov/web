<?php
session_start();
require_once "db_connect.php";
$msg = "";

// Обработчики ошибок
function customErrorHandler($errno, $errstr, $errfile, $errline)
{
    error_log("[ERROR][$errno] $errstr in $errfile on line $errline\n", 3, "error_log.txt");
    echo "<p class='error'>Произошла ошибка. Пожалуйста, повторите позже.</p>";
    return true;
}

function shutdownHandler()
{
    $error = error_get_last();
    if ($error && in_array($error["type"], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
        error_log("[FATAL] {$error['message']} in {$error['file']} on line {$error['line']}\n", 3, "error_log.txt");
        echo "<p class='error'>Фатальная ошибка. Пожалуйста, повторите позже.</p>";
    }
}

set_error_handler("customErrorHandler");
register_shutdown_function("shutdownHandler");

// Капча обновляется при каждом GET-запросе (показе формы)
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION['captcha'] = rand(1000, 9999);
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST['login']);
    $password_raw = $_POST['password'];
    $email = trim($_POST['email']);
    $captcha_input = $_POST['captcha'] ?? '';
    $reg_date = date('Y-m-d');
    $photo = "";

    $errors = [];

    if (strlen($login) < 3) $errors[] = "Логин должен быть не менее 3 символов.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Некорректный email.";
    if (strlen($password_raw) < 6) $errors[] = "Пароль должен содержать не менее 6 символов.";
    if ($captcha_input != $_SESSION['captcha']) $errors[] = "Неверный код.";

    if (!empty($_FILES['photo']['name'])) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $photoName = uniqid() . "_" . basename($_FILES["photo"]["name"]);
        $targetPath = $uploadDir . $photoName;
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetPath)) {
            $photo = $photoName;
        }
    }

    if (empty($errors)) {
        $password = password_hash($password_raw, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (login, password, email, photo, reg_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $login, $password, $email, $photo, $reg_date);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit;
        } else {
            $msg = "Ошибка: возможно, такой логин уже существует.";
            trigger_error("Ошибка MySQL: " . $conn->error, E_USER_WARNING);
        }
        $stmt->close();
    } else {
        foreach ($errors as $error) {
            echo "<p class='error' style='color:red;text-align:center;'>$error</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
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

        .reg-form {
            max-width: 500px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .reg-form h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2e86de;
        }

        .reg-form label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        .reg-form input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .reg-form button {
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

        .reg-form button:hover {
            background: #1b65c3;
        }

        .message {
            margin-top: 20px;
            text-align: center;
            color: green;
        }
    </style>
</head>
<body>

<header>
    <h1>Регистрация</h1>
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

<div class="reg-form">
    <h2>Зарегистрироваться</h2>
    <form method="post" enctype="multipart/form-data">
        <label for="login">Логин:</label>
        <input type="text" name="login" id="login" required>

        <label for="password">Пароль:</label>
        <input type="password" name="password" id="password" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="photo">Фото (аватар):</label>
        <input type="file" name="photo" id="photo" accept="image/*">

        <label for="captcha">Введите код: <strong><?= $_SESSION['captcha'] ?></strong></label>
        <input type="text" name="captcha" id="captcha" required>

        <button type="submit">Зарегистрироваться</button>
    </form>

    <?php if ($msg): ?>
        <div class="message"> <?= $msg ?> </div>
    <?php endif; ?>
</div>

</body>
</html>
