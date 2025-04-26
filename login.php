<?php
session_start();
include 'db.php';

// Ако вече е логнат, пращаме към index.php
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Ако е пратен формуляр
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($email) || empty($password)) {
        die("Моля, попълнете всички полета.");
    }

    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        header('Location: index.php');
        exit;
    } else {
        echo "Невалиден имейл или парола.<br><a href='login.php'>Опитай отново</a>";
    }
} else {
    // Показваме форма за вход
    echo "<h2>Вход</h2>
    <form method='POST' action='login.php'>
        Имейл: <input name='email' type='email' required><br><br>
        Парола: <input type='password' name='password' required><br><br>
        <button type='submit'>Вход</button>
    </form>
    <br>
    <a href='register.php'>Регистрация</a>";
}
?>
