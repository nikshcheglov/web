<?php
session_start();
require_once "db_connect.php";
if (!isset($_SESSION['admin_logged_in'])) header("Location: login.php");

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM блюда WHERE Код_блюда = $id");
header("Location: dishes.php");
exit;
?>