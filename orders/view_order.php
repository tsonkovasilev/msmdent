<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Не сте влезли в системата.");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Невалиден идентификатор на поръчка.");
}

$order_id = (int)$_GET['id'];

// Извличаме ролята
$stmt = $db->prepare("SELECT role FROM users WHERE id = :id");
$stmt->bindValue(':id', $_SESSION['user_id'], SQLITE3_INTEGER);
$result = $stmt->execute();
$user = $result->fetchArray(SQLITE3_ASSOC);

$is_admin = ($user['role'] === 'admin');

// Зареждаме поръчката
if ($is_admin) {
    $stmt = $db->prepare("SELECT * FROM orders WHERE id = :id");
} else {
    $stmt = $db->prepare("SELECT * FROM orders WHERE id = :id AND user_id = :user_id");
    $stmt->bindValue(':user_id', $_SESSION['user_id'], SQLITE3_INTEGER);
}
$stmt->bindValue(':id', $order_id, SQLITE3_INTEGER);
$result = $stmt->execute();
$order = $result->fetchArray(SQLITE3_ASSOC);

if (!$order) {
    die("Поръчката не съществува или нямате достъп до нея.");
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Поръчка №<?php echo htmlspecialchars($order['id']); ?></title>
</head>
<body>

<h2>Поръчка №<?php echo htmlspecialchars($order['id']); ?></h2>

<p><strong>Дата:</strong> <?php echo htmlspecialchars($order['date']); ?></p>
<p><strong>Брой елементи:</strong> <?php echo htmlspecialchars($order['quantity']); ?></p>
<p><strong>Моделиране:</strong> <?php echo htmlspecialchars($order['modeling']); ?></p>
<p><strong>Материал:</strong> <?php echo htmlspecialchars($order['material']); ?></p>
<p><strong>Цвят:</strong> <?php echo htmlspecialchars($order['color']); ?></p>
<p><strong>Доставка:</strong> <?php echo htmlspecialchars($order['delivery']); ?></p>
<p><strong>Коментар:</strong><br><?php echo nl2br(htmlspecialchars($order['comment'])); ?></p>
<p><strong>Статус:</strong> <?php echo htmlspecialchars($order['status']); ?></p>

<?php if ($order['status'] === 'Нова' && ($is_admin || $_SESSION['user_id'] == $order['user_id'])): ?>
    <form method="POST" action="delete_order.php">
        <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
        <button type="submit" onclick="return confirm('Наистина ли искате да изтриете тази поръчка?')">Изтрий поръчката</button>
    </form>
<?php endif; ?>

<?php if ($is_admin): ?>
    <form method="POST" action="update_status.php">
        <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
        Смени статус:
        <select name="status">
            <option value="Нова" <?php if ($order['status'] == 'Нова') echo 'selected'; ?>>Нова</option>
            <option value="Започната" <?php if ($order['status'] == 'Започната') echo 'selected'; ?>>Започната</option>
            <option value="Готова" <?php if ($order['status'] == 'Готова') echo 'selected'; ?>>Готова</option>
        </select>
        <button type="submit">Обнови</button>
    </form>
<?php endif; ?>

<br>
<a href="index.php">⬅ Обратно към всички поръчки</a>

</body>
</html>
