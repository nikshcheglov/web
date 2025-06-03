<?php
session_start();
require_once "db_connect.php";
if (!isset($_SESSION['admin_logged_in'])) header("Location: login.php");

$id = (int)$_GET['id'];
// Проверим статус, удаляем только завершённые
$result = mysqli_query($conn, "SELECT status FROM orders WHERE id = $id");
$order = mysqli_fetch_assoc($result);

if ($order && $order['status'] === 'завершён') {
    mysqli_query($conn, "DELETE FROM order_items WHERE order_id = $id");
    mysqli_query($conn, "DELETE FROM orders WHERE id = $id");
}
header("Location: orders.php");
exit;
?>
