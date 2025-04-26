<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$order_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

// Зареждаме ролята на потребителя
$stmt = $db->prepare("SELECT role FROM users WHERE id = :id");
$stmt->bindValue(':id', $_SESSION['user_id'], SQLITE3_INTEGER);
$result = $stmt->execute();
$user = $result->fetchArray(SQLITE3_ASSOC);

$is_admin = ($user['role'] === 'admin');

// Зареждаме поръчката
$stmt = $db->prepare("SELECT * FROM orders WHERE id = :id");
$stmt->bindValue(':id', $order_id, SQLITE3_INTEGER);
$result = $stmt->execute();
$order = $result->fetchArray(SQLITE3_ASSOC);

if (!$order) {
    die("Поръчката не съществува.");
}

// Може да изтрием само ако:
// - статус е "Нова"
// - и потребителят е админ или собственик
if ($order['status'] === 'Нова' && ($is_admin || $order['user_id'] == $_SESSION['user_id'])) {
    $stmt = $db->prepare("DELETE FROM orders WHERE id = :id");
    $stmt->bindValue(':id', $order_id, SQLITE3_INTEGER);
    $stmt->execute();
}

header('Location: index.php');
exit;
?>
