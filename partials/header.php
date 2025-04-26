<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>MSM Dent</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        header, footer { background: #eee; padding: 10px; text-align: center; }
        nav a { margin: 0 10px; }
    </style>
</head>
<body>

<header>
    <h1>MSM Dent</h1>
    <?php if (isset($_SESSION['user_id'])): ?>
        <nav>
            <a href="/orders/list.php">Поръчки</a>
            <a href="/orders/add.php">Нова поръчка</a>
            <a href="/logout.php">Изход</a>
        </nav>
    <?php else: ?>
        <nav>
        <a href="/index.php">Начало</a>
        <a href="/orders/list.php">Поръчки</a>
        </nav>
    <?php endif; ?>
</header>

<main>
