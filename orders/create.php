<?php
session_start();
include __DIR__ . '/../db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

// Взимаме данните от формата
$quantity = trim($_POST['quantity'] ?? '');
$modeling = trim($_POST['modeling'] ?? '');
$material = trim($_POST['material'] ?? '');
$color = trim($_POST['color'] ?? '');
$delivery = trim($_POST['delivery'] ?? '');
$recipient_name = trim($_POST['recipient_name'] ?? '');
$recipient_phone = trim($_POST['recipient_phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$comment = trim($_POST['comment'] ?? '');

// Създаваме нова поръчка
$stmt = $db->prepare("INSERT INTO orders (user_id, quantity, modeling, material, color, delivery, recipient_name, recipient_phone, address, comment)
VALUES (:user_id, :quantity, :modeling, :material, :color, :delivery, :recipient_name, :recipient_phone, :address, :comment)");

$stmt->bindValue(':user_id', $_SESSION['user_id'], SQLITE3_INTEGER);
$stmt->bindValue(':quantity', $quantity, SQLITE3_TEXT);
$stmt->bindValue(':modeling', $modeling, SQLITE3_TEXT);
$stmt->bindValue(':material', $material, SQLITE3_TEXT);
$stmt->bindValue(':color', $color, SQLITE3_TEXT);
$stmt->bindValue(':delivery', $delivery, SQLITE3_TEXT);
$stmt->bindValue(':recipient_name', $recipient_name, SQLITE3_TEXT);
$stmt->bindValue(':recipient_phone', $recipient_phone, SQLITE3_TEXT);
$stmt->bindValue(':address', $address, SQLITE3_TEXT);
$stmt->bindValue(':comment', $comment, SQLITE3_TEXT);

$stmt->execute();

// Взимаме ID на новата поръчка
$order_id = $db->lastInsertRowID();

// Ако е качен файл
if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    $allowed_extensions = ['stl', 'ply', 'dcm'];
    $file_ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

    if (in_array($file_ext, $allowed_extensions)) {
        $upload_dir = __DIR__ . '/../uploads/' . $order_id . '/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $destination = $upload_dir . basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $destination);
    }
}


echo 'asdas';
exit();

// Пренасочваме обратно към списъка
header('Location: list.php');
exit;
?>
