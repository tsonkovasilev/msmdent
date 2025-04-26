<?php
session_start();
include 'db.php';

// Ако вече е логнат, директно към index.php
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

include __DIR__ . '/partials/header.php';

// Ако е пратен формуляр
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($email) || empty($password)) {
        die("Моля, попълнете всички полета.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Невалиден имейл адрес.");
    }

    if (strlen($password) < 6) {
        die("Паролата трябва да е минимум 6 символа.");
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $db->prepare("INSERT INTO users (email, password_hash) VALUES (:email, :password_hash)");
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':password_hash', $password_hash, SQLITE3_TEXT);

    if ($stmt->execute()) {
        echo "Регистрацията е успешна! <a href='login.php'>Влез</a>";
    } else {
        echo "Имейлът вече съществува.<br><a href='register.php'>Опитай отново</a>";
    }
} else {
    // Показваме форма за регистрация
    echo "<h2>Регистрация</h2>
    <form method='POST' action='register.php'>
        Имейл: <input name='email' type='email' required><br><br>
        Парола (мин. 6 символа): <input type='password' name='password' required><br><br>
        <button type='submit'>Регистрация</button>
    </form>
    <br>
    <a href='login.php'>Вече имам регистрация</a>";
}

include __DIR__ . '/partials/footer.php';
?>
