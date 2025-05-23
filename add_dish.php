<?php
require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $photoName = '';

    if (!empty($_FILES['photo']['name'])) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $photoName = uniqid() . "_" . basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $uploadDir . $photoName);
    }

    $stmt = $conn->prepare("INSERT INTO блюда (Наименование_блюда, Стоимость, Фото) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $name, $price, $photoName);
    $stmt->execute();
    $stmt->close();

    echo "<p>Блюдо добавлено!</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Добавить блюдо</title>
</head>
<body>
    <h2>Добавить новое блюдо</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Наименование:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Стоимость (₽):</label><br>
        <input type="number" step="0.01" name="price" required><br><br>

        <label>Фото:</label><br>
        <input type="file" name="photo" accept="image/*"><br><br>

        <button type="submit">Добавить блюдо</button>
    </form>
</body>
</html>
