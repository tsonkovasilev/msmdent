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

$order_id = (int)($_POST['id'] ?? 0);

if (!$order_id) {
    die("Невалидна заявка.");
}

// Проверка за роля
$stmt = $db->prepare("SELECT role FROM users WHERE id = :id");
$stmt->bindValue(':id', $_SESSION['user_id'], SQLITE3_INTEGER);
$result = $stmt->execute();
$user = $result->fetchArray(SQLITE3_ASSOC);
$is_admin = ($user['role'] === 'admin');

// Зареждане на поръчката
$stmt = $db->prepare("SELECT * FROM orders WHERE id = :id");
$stmt->bindValue(':id', $order_id, SQLITE3_INTEGER);
$result = $stmt->execute();
$order = $result->fetchArray(SQLITE3_ASSOC);

if (!$order) {
    die("Поръчката не съществува.");
}

// Може да се трие само ако статус е "Нова" и е собственик или админ
if ($order['status'] === 'Нова' && ($is_admin || $order['user_id'] == $_SESSION['user_id'])) {
    $stmt = $db->prepare("DELETE FROM orders WHERE id = :id");
    $stmt->bindValue(':id', $order_id, SQLITE3_INTEGER);
    $stmt->execute();

    // Изтриваме файловете ако има папка
    $upload_dir = __DIR__ . '/../uploads/' . $order_id . '/';
    if (is_dir($upload_dir)) {
        $files = array_diff(scandir($upload_dir), array('.', '..'));
        foreach ($files as $file) {
            unlink($upload_dir . $file);
        }
        rmdir($upload_dir);
    }
}

header('Location: list.php');
exit;
?>
