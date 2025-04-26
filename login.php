<?php
session_start();
include __DIR__ . '/db.php'; // Коректно включване на базата

if (isset($_SESSION['user_id'])) {
    // Ако вече е логнат
    header('Location: orders/list.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        die("Моля, попълнете всички полета.");
    }

    // Проверяваме дали имейл съществува
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if ($user) {
        // Имейл намерен, проверяваме паролата
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            header('Location: orders/list.php');
            exit;
        } else {
            echo "Грешна парола.<br><a href='login.php'>⬅ Върни се</a>";
        }
    } else {
        echo "Имейлът не е намерен.<br><a href='login.php'>⬅ Върни се</a>";
    }
} else {
    // Показваме форма за вход
    echo "<h2>Вход</h2>
    <form method='POST' action='login.php'>
        Имейл: <input type='email' name='email' required><br><br>
        Парола: <input type='password' name='password' required><br><br>
        <button type='submit'>Вход</button>
    </form>
    <br>
    <a href='index.php'>⬅ Назад</a>";
}
?>
