<?php
session_start();
require_once "db_connect.php";
if (!isset($_SESSION['admin_logged_in'])) header("Location: login.php");

$photo = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];

    if (!empty($_FILES['photo']['name'])) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $photoName = uniqid() . "_" . basename($_FILES["photo"]["name"]);
        $targetPath = $uploadDir . $photoName;
        move_uploaded_file($_FILES["photo"]["tmp_name"], $targetPath);
        $photo = $photoName;
    }

    $stmt = $conn->prepare("INSERT INTO блюда (Наименование_блюда, Стоимость, Фото) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $name, $price, $photo);
    $stmt->execute();
    header("Location: dishes.php");
    exit;
}
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Добавить блюдо</title><link rel="stylesheet" href="style.css"></head>
<body>
<header><h1>Добавление блюда</h1></header>
<nav>
    <a href="index.php">Главная</a>
    <a href="dishes.php">Блюда</a>
    <a href="users.php">Пользователи</a>
    <a href="orders.php">Заказы</a>
	<a href="http://lr-4/" target="_blank">Клиентская часть</a>
    <a href="logout.php">Выход</a>
</nav>
<div class="container">
    <form method="post" enctype="multipart/form-data">
        <label>Название:<br><input type="text" name="name" required></label><br><br>
        <label>Цена (₽):<br><input type="number" step="0.01" name="price" required></label><br><br>
        <label>Фото блюда:<br><input type="file" name="photo" accept="image/*"></label><br><br>
        <button type="submit">Добавить</button>
    </form>
</div>
</body></html>
