<?php
session_start();
include __DIR__ . '/../db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Нова поръчка</title>
</head>
<body>

<h2>Нова поръчка</h2>

<form method="POST" action="create.php">
    Брой елементи: <input name="quantity" type="number" required><br><br>

    Моделиране:
    <select name="modeling" required>
        <option value="Да">Да</option>
        <option value="Не">Не</option>
    </select><br><br>

    Материал:
    <select name="material" required>
        <option value="ZirCAD Prime">ZirCAD Prime</option>
        <option value="ZirCAD Prime Esthetic">ZirCAD Prime Esthetic</option>
        <option value="PMMA">PMMA</option>
    </select><br><br>

    Цвят:
    <select name="color" required>
        <option value="BL">BL</option>
        <option value="A1">A1</option>
        <option value="A2">A2</option>
    </select><br><br>

    Доставка:
    <select name="delivery" required>
        <option value="Взимане от офис">Взимане от офис</option>
        <option value="Доставка с куриер">Доставка с куриер</option>
        <option value="До офис на куриер">До офис на куриер</option>
    </select><br><br>

    Име на получателя: <input name="recipient_name" type="text" required><br><br>
    Телефон на получателя: <input name="recipient_phone" type="text" required><br><br>

    Адрес:<br>
    <textarea name="address" rows="3" cols="40" required></textarea><br><br>

    Коментар:<br>
    <textarea name="comment" rows="4" cols="40"></textarea><br><br>

    <button type="submit">Създай поръчка</button>
</form>

<br>
<a href="list.php">⬅ Обратно към поръчките</a>

</body>
</html>
