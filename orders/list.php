<?php
session_start();
include __DIR__ . '/../db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$stmt = $db->prepare("SELECT role FROM users WHERE id = :id");
$stmt->bindValue(':id', $_SESSION['user_id'], SQLITE3_INTEGER);
$result = $stmt->execute();
$user = $result->fetchArray(SQLITE3_ASSOC);

if (!$user) {
    // Ако потребителят не е намерен
    session_destroy();
    header('Location: ../login.php');
    exit;
}

$is_admin = ($user['role'] === 'admin');

include __DIR__ . '/../partials/header.php';

echo "<h2>Поръчки</h2>";
echo "<a href='../logout.php'>Изход</a> | <a href='../index.php'>Начало</a> | <a href='add.php'>Нова поръчка</a><br><br>";

if ($is_admin) {
    $query = "SELECT orders.*, users.email FROM orders JOIN users ON orders.user_id = users.id ORDER BY date DESC";
    $stmt = $db->prepare($query);
} else {
    $query = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY date DESC";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':user_id', $_SESSION['user_id'], SQLITE3_INTEGER);
}

$results = $stmt->execute();

echo "<table border='1' cellpadding='10' cellspacing='0'>
<tr>
    <th>ID</th>
    <th>Дата</th>
    <th>Име на получател</th>
    <th>Телефон</th>
    <th>Материал</th>
    <th>Цвят</th>
    <th>Статус</th>";

if ($is_admin) {
    echo "<th>Потребител</th>";
}

echo "<th>Детайли</th></tr>";

while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
    $status_color = match($row['status']) {
        'Нова' => 'blue',
        'Започната' => 'orange',
        'Готова' => 'green',
        default => 'black'
    };
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
    echo "<td>" . htmlspecialchars($row['date']) . "</td>";
    echo "<td>" . htmlspecialchars($row['recipient_name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['recipient_phone']) . "</td>";
    echo "<td>" . htmlspecialchars($row['material']) . "</td>";
    echo "<td>" . htmlspecialchars($row['color']) . "</td>";
    echo "<td style='color: $status_color; font-weight: bold;'>" . htmlspecialchars($row['status']) . "</td>";

    if ($is_admin) {
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
    }

    echo "<td><a href='view.php?id=" . $row['id'] . "'>Виж</a></td>";
    echo "</tr>";
}

echo "</table>";

include __DIR__ . '/../partials/footer.php';
?>
