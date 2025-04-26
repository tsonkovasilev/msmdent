<?php
session_start();
include __DIR__ . '/../db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}
?>

<?php
include __DIR__ . '/../partials/header.php';
?>

<h2>Нова поръчка</h2>

<form method="POST" action="create.php" enctype="multipart/form-data">

    Брой елементи: <input name="quantity" type="number"><br><br>

    Моделиране:
    <select name="modeling">
        <option value="Да">Да</option>
        <option value="Не">Не</option>
    </select><br><br>

    Материал:
    <select name="material">
        <option value="ZirCAD Prime">ZirCAD Prime</option>
        <option value="ZirCAD Prime Esthetic">ZirCAD Prime Esthetic</option>
        <option value="PMMA">PMMA</option>
    </select><br><br>

    Цвят:
    <select name="color">
        <option value="BL">BL</option>
        <option value="A1">A1</option>
        <option value="A2">A2</option>
    </select><br><br>

    Файл към поръчката
    <input type="file" name="file" accept=".stl,.ply,.dcm,.txt"><br><br>

    Доставка:
    <select name="delivery">
        <option value="Взимане от офис">Взимане от офис</option>
        <option value="Доставка с куриер">Доставка с куриер</option>
        <option value="До офис на куриер">До офис на куриер</option>
    </select><br><br>

    Име на получателя: <input name="recipient_name" type="text"><br><br>

    Телефон на получателя: <input name="recipient_phone" type="text"><br><br>

    Адрес:<br>
    <textarea name="address" rows="3" cols="40"></textarea><br><br>

    Коментар:<br>
    <textarea name="comment" rows="4" cols="40"></textarea><br><br>

    

    <button type="submit">Създай поръчка</button>

</form>

<br>
<a href="list.php">⬅ Назад към поръчките</a>

</body>
</html>
