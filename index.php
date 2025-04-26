<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<a href='login.php'>Вход</a> | <a href='orders.php'>Регистрация</a>";
} else {
    header('Location: orders/list.php');
}
?>
