<?php
session_start();
require_once "db_connect.php";
if (!isset($_SESSION['admin_logged_in'])) header("Location: login.php");

$id = (int)$_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM блюда WHERE Код_блюда = $id");
$dish = mysqli_fetch_assoc($result);
if (!$dish) {
    echo "Блюдо не найдено.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $photo = $dish['Фото']; // старое фото

    // Загрузка нового фото (если загружено)
    if (!empty($_FILES['photo']['name'])) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $photoName = uniqid() . "_" . basename($_FILES["photo"]["name"]);
        $targetPath = $uploadDir . $photoName;
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetPath)) {
            $photo = $photoName;
        }
    }

    $stmt = $conn->prepare("UPDATE блюда SET Наименование_блюда = ?, Стоимость = ?, Фото = ? WHERE Код_блюда = ?");
    $stmt->bind_param("sdsi", $name, $price, $photo, $id);
    $stmt->execute();
    header("Location: dishes.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Редактировать блюдо</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .thumb {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
        }
    </style>
</head>
<body>
<header><h1>Редактирование блюда</h1></header>
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
        <label>Название:<br>
            <input type="text" name="name" value="<?= htmlspecialchars($dish['Наименование_блюда']) ?>" required>
        </label><br><br>

        <label>Цена (₽):<br>
            <input type="number" step="0.01" name="price" value="<?= $dish['Стоимость'] ?>" required>
        </label><br><br>

        <label>Текущее фото:<br>
            <?php if ($dish['Фото']): ?>
                <img class="thumb" src="uploads/<?= htmlspecialchars($dish['Фото']) ?>" alt="Фото">
            <?php else: ?>
                <span>Нет изображения</span>
            <?php endif; ?>
        </label><br><br>

        <label>Новое фото (если нужно заменить):<br>
            <input type="file" name="photo" accept="image/*">
        </label><br><br>

        <button type="submit">Сохранить изменения</button>
    </form>
</div>
</body>
</html>
