<?php
session_start();
require_once "db_connect.php";

// Получаем ID блюда
$dish_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Получаем блюдо
$stmt = $conn->prepare("SELECT * FROM блюда WHERE Код_блюда = ?");
$stmt->bind_param("i", $dish_id);
$stmt->execute();
$dish_result = $stmt->get_result();
$dish = $dish_result->fetch_assoc();

// Получаем инфо о пользователе, если авторизован
$user_info = null;
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $u_res = mysqli_query($conn, "SELECT login, photo FROM users WHERE id = $uid");
    $user_info = mysqli_fetch_assoc($u_res);
}

// Добавление в корзину
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_to_cart'])) {
    $_SESSION['cart'][$dish_id] = ($_SESSION['cart'][$dish_id] ?? 0) + 1;
    $added = true;
}

// Добавление комментария
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['new_comment']) && isset($_SESSION['user_id'])) {
    $comment = htmlspecialchars(trim($_POST['comment']));
    if ($comment) {
        $stmt = $conn->prepare("INSERT INTO dish_comments (dish_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $dish_id, $uid, $comment);
        $stmt->execute();
    }
}

// Получаем комментарии + имя и фото пользователя
$stmt = $conn->prepare("
    SELECT c.comment, c.created_at, u.login, u.photo
    FROM dish_comments c
    JOIN users u ON c.user_id = u.id
    WHERE c.dish_id = ?
    ORDER BY c.created_at DESC
");
$stmt->bind_param("i", $dish_id);
$stmt->execute();
$comments_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($dish['Наименование_блюда']) ?> — Блюдо</title>
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

        .dish-wrapper {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .dish-wrapper img {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .comments {
            margin-top: 30px;
        }

        .comment-block {
            background: #f0f2f5;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 10px;
            display: flex;
            align-items: flex-start;
        }

        .comment-block img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }

        .comment-content {
            flex: 1;
        }

        form {
            margin-top: 20px;
        }

        form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        form button {
            background-color: #2e86de;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .added-note {
            background: #d1f0d1;
            padding: 10px;
            border-radius: 6px;
            margin: 10px 0;
            color: #2e7d32;
        }
    </style>
</head>
<body>

<header>
    <h1>Подробности блюда</h1>
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

<div class="dish-wrapper">
    <h2><?= htmlspecialchars($dish['Наименование_блюда']) ?></h2>
    <img src="uploads/<?= htmlspecialchars($dish['Фото']) ?>" alt="Блюдо">
    <p><strong>Цена:</strong> <?= htmlspecialchars($dish['Стоимость']) ?> ₽</p>

    <form method="post">
        <button type="submit" name="add_to_cart">Добавить в корзину</button>
    </form>

    <?php if (!empty($added)): ?>
        <div class="added-note">Блюдо добавлено в корзину!</div>
    <?php endif; ?>

    <div class="comments">
        <h3>Комментарии</h3>
        <?php if ($comments_result->num_rows > 0): ?>
            <?php while($row = $comments_result->fetch_assoc()): ?>
                <div class="comment-block">
                    <?php if ($row['photo']): ?>
                        <img src="uploads/<?= htmlspecialchars($row['photo']) ?>" alt="Аватар">
                    <?php else: ?>
                        <img src="uploads/default.png" alt="Без фото">
                    <?php endif; ?>
                    <div class="comment-content">
                        <strong><?= htmlspecialchars($row['login']) ?>:</strong>
                        <p><?= nl2br(htmlspecialchars($row['comment'])) ?></p>
                        <small><?= $row['created_at'] ?></small>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Комментариев пока нет.</p>
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION['user_id'])): ?>
        <form method="post">
            <h4>Оставить комментарий</h4>
            <textarea name="comment" rows="4" placeholder="Ваш комментарий" required></textarea>
            <button type="submit" name="new_comment">Отправить</button>
        </form>
    <?php else: ?>
        <p><em>Чтобы оставить комментарий, <a href="login.php">войдите в аккаунт</a>.</em></p>
    <?php endif; ?>
</div>

</body>
</html>
