<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>MSM Dent</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap&subset=cyrillic" rel="stylesheet">
    <link rel="stylesheet" href="/style.css">
    
</head>
<body>

<header>
    <?php $logo = 'https://play-lh.googleusercontent.com/cvEngLJvLd3PWYCxtHEtqFlz-0D8m2Ro4jU4EBJulSFUQ10_MxkGrtofWOvtxQk1pA'; ?>
    <a href="/"><img src="<?php echo $logo?>" alt="MSM Dent Лого" style="height: 200px; width:auto; vertical-align: middle;"></a>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <nav>
            <a href="/orders/list.php">Поръчки</a>
            <a href="/orders/add.php">Нова поръчка</a>
            <a href="/logout.php">Изход</a>
        </nav>
    <?php else: ?>
        <nav>
        <a href="/index.php">За нас</a>
        <a href="/orders/list.php">Поръчки</a>
        </nav>
    <?php endif; ?>
</header>

<main>
