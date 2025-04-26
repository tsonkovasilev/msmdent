<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

// Взимаме данните
$quantity = trim($_POST['quantity']);
$modeling = trim($_POST['modeling']);
$material = trim($_POST['material']);
$color = trim($_POST['color']);
$delivery = trim($_POST['delivery']);
$comment = trim($_POST['comment']);
$recipient_name = trim($_POST['recipient_name']);
$recipient_phone = trim($_POST['recipient_phone']);
$address = trim($_POST['address']);

// Валидация
if (empty($quantity) || empty($material) || empty($color) || empty($delivery) || empty($recipient_name) || empty($recipient_phone) || empty($address)) {
    die("Моля, попълнете всички задължителни полета.");
}

// Създаване на поръчка
$stmt = $db->prepare("INSERT INTO orders (user_id, quantity, modeling, material, color, delivery, comment, recipient_name, recipient_phone, address)
VALUES (:user_id, :quantity, :modeling, :material, :color, :delivery, :comment, :recipient_name, :recipient_phone, :address)");

$stmt->bindValue(':user_id', $_SESSION['user_id'], SQLITE3_INTEGER);
$stmt->bindValue(':quantity', $quantity, SQLITE3_TEXT);
$stmt->bindValue(':modeling', $modeling, SQLITE3_TEXT);
$stmt->bindValue(':material', $material, SQLITE3_TEXT);
$stmt->bindValue(':color', $color, SQLITE3_TEXT);
$stmt->bindValue(':delivery', $delivery, SQLITE3_TEXT);
$stmt->bindValue(':comment', $comment, SQLITE3_TEXT);
$stmt->bindValue(':recipient_name', $recipient_name, SQLITE3_TEXT);
$stmt->bindValue(':recipient_phone', $recipient_phone, SQLITE3_TEXT);
$stmt->bindValue(':address', $address, SQLITE3_TEXT);

$stmt->execute();

header('Location: list.php');
exit;
?>
