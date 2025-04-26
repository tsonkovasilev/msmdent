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
$status = isset($_POST['status']) ? trim($_POST['status']) : '';

if (!$order_id || empty($status)) {
    die("Невалидни данни.");
}

// Проверяваме дали потребителят е админ
$stmt = $db->prepare("SELECT role FROM users WHERE id = :id");
$stmt->bindValue(':id', $_SESSION['user_id'], SQLITE3_INTEGER);
$result = $stmt->execute();
$user = $result->fetchArray(SQLITE3_ASSOC);

if ($user['role'] !== 'admin') {
    die("Само админ може да сменя статус на поръчки.");
}

// Обновяваме статуса
$stmt = $db->prepare("UPDATE orders SET status = :status WHERE id = :id");
$stmt->bindValue(':status', $status, SQLITE3_TEXT);
$stmt->bindValue(':id', $order_id, SQLITE3_INTEGER);
$stmt->execute();

header('Location: view_order.php?id=' . $order_id);
exit;
?>
