<?php
session_start();
include __DIR__ . '/../db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Невалиден идентификатор на поръчка.");
}

$order_id = (int)$_GET['id'];

// Проверка дали потребителят е админ
$stmt = $db->prepare("SELECT role FROM users WHERE id = :id");
$stmt->bindValue(':id', $_SESSION['user_id'], SQLITE3_INTEGER);
$result = $stmt->execute();
$user = $result->fetchArray(SQLITE3_ASSOC);

$is_admin = ($user['role'] === 'admin');

// Зареждане на поръчката
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

include __DIR__ . '/../partials/header.php';
?>


<h2>Поръчка №<?php echo htmlspecialchars($order['id']); ?></h2>
<p><strong>Тип на поръчката:</strong> <?php echo htmlspecialchars($order['order_type']); ?></p>
<p><strong>Дата:</strong> <?php echo htmlspecialchars($order['date']); ?></p>
<p><strong>Брой елементи:</strong> <?php echo htmlspecialchars($order['quantity']); ?></p>
<p><strong>Моделиране:</strong> <?php echo htmlspecialchars($order['modeling']); ?></p>
<p><strong>Материал:</strong> <?php echo htmlspecialchars($order['material']); ?></p>
<p><strong>Цвят:</strong> <?php echo htmlspecialchars($order['color']); ?></p>
<p><strong>Доставка:</strong> <?php echo htmlspecialchars($order['delivery']); ?></p>
<p><strong>Име на получател:</strong> <?php echo htmlspecialchars($order['recipient_name']); ?></p>
<p><strong>Телефон на получател:</strong> <?php echo htmlspecialchars($order['recipient_phone']); ?></p>
<p><strong>Адрес:</strong><br><?php echo nl2br(htmlspecialchars($order['address'])); ?></p>
<p><strong>Коментар:</strong><br><?php echo nl2br(htmlspecialchars($order['comment'])); ?></p>
<p><strong>Статус:</strong> <?php echo htmlspecialchars($order['status']); ?></p>

<h3>Качени файлове:</h3>

<?php
$upload_dir = __DIR__ . '/../uploads/' . $order['id'] . '/';

if (is_dir($upload_dir)) {
    $files = array_diff(scandir($upload_dir), array('.', '..'));

    if (!empty($files)) {
        echo "<ul>";
        foreach ($files as $file) {
            $url = '../uploads/' . $order['id'] . '/' . urlencode($file);
            echo "<li><a href='$url' target='_blank'>" . htmlspecialchars($file) . "</a></li>";
        }
        echo "</ul>";
    } else {
        echo "Няма качени файлове.";
    }
} else {
    echo "Няма качени файлове.";
}
?>
<hr>

<?php if ($order['status'] === 'Нова' && ($is_admin || $_SESSION['user_id'] == $order['user_id'])): ?>
    <form method="POST" action="delete.php" onsubmit="return confirm('Наистина ли искате да изтриете тази поръчка?');">
        <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
        <button type="submit">Изтрий поръчката</button>
    </form>
<?php endif; ?>

<?php if ($is_admin): ?>
    <form method="POST" action="update.php">
        <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
        Смени статус:
        <select name="status">
            <option value="Нова" <?php if ($order['status'] == 'Нова') echo 'selected'; ?>>Нова</option>
            <option value="Започната" <?php if ($order['status'] == 'Започната') echo 'selected'; ?>>Започната</option>
            <option value="Готова" <?php if ($order['status'] == 'Готова') echo 'selected'; ?>>Готова</option>
        </select>
        <button type="submit">Обнови статус</button>
    </form>
<?php endif; ?>

<br>
<a href="list.php">⬅ Назад към списъка</a>

<?php
include __DIR__ . '/../partials/footer.php';
?>