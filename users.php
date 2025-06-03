<?php
session_start();
require_once "db_connect.php";
if (!isset($_SESSION['admin_logged_in'])) header("Location: login.php");

// Поиск
$search = $_GET['search'] ?? '';
$query = "SELECT * FROM users";
if ($search) {
    $s = mysqli_real_escape_string($conn, $search);
    $query .= " WHERE login LIKE '%$s%' OR email LIKE '%$s%'";
}
$users = mysqli_query($conn, $query);

// Обработка сохранения изменений
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['edit_user_id'])) {
    $user_id = (int)$_POST['edit_user_id'];
    $login = trim($_POST['login']);
    $email = trim($_POST['email']);

   $stmt = $conn->prepare("UPDATE users SET login = ?, email = ? WHERE id = ?");
$stmt->bind_param("ssi", $login, $email, $user_id);

    $stmt->execute();
    header("Location: users.php");
    exit;
}

$edit_id = $_GET['edit_user_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Пользователи</title>
    <link rel="stylesheet" href="style.css">
    <style>
        input[type="text"], input[type="email"] {
            width: 90%;
        }
        label {
            font-size: 0.9em;
        }
    </style>
</head>
<body>
<header><h1>Пользователи</h1></header>
<nav>
    <a href="index.php">Главная</a>
    <a href="dishes.php">Блюда</a>
    <a href="users.php">Пользователи</a>
    <a href="orders.php">Заказы</a>
    <a href="http://lr-4/" target="_blank">Клиентская часть</a>
    <a href="logout.php">Выход</a>
</nav>

<div class="container">
    <form method="get" style="margin-bottom:15px;">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Поиск по логину/email">
        <button type="submit">Поиск</button>
    </form>

    <table>
        <tr><th>ID</th><th>Логин</th><th>Email</th><th>Дата регистрации</th><th>Админ</th><th>Действия</th></tr>
        <?php while ($user = mysqli_fetch_assoc($users)): ?>
        <tr>
            <?php if ($edit_id == $user['id']): ?>
				<form method="post">
					<td><?= $user['id'] ?></td>
					<td><input type="text" name="login" value="<?= htmlspecialchars($user['login']) ?>" required></td>
					<td><input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required></td>
					<td><?= date("d.m.Y", strtotime($user['reg_date'])) ?></td>
					<td><?= $user['is_admin'] ? '✔️' : '—' ?></td>
					<td>
						<input type="hidden" name="edit_user_id" value="<?= $user['id'] ?>">
						<button type="submit">💾</button>
						<a href="users.php">❌</a>
					</td>
				</form>
			<?php else: ?>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['login']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= date("d.m.Y", strtotime($user['reg_date'])) ?></td>
                <td><?= $user['is_admin'] ? '✔️' : '—' ?></td>
                <td>
                    <a href="?edit_user_id=<?= $user['id'] ?>">✏️</a> |
                    <a href="delete_user.php?id=<?= $user['id'] ?>" onclick="return confirm('Удалить пользователя?')">🗑️</a>
                </td>
            <?php endif; ?>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
