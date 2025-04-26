<?php
session_start();
include 'db.php';


if (!isset($_SESSION['user_id'])) {
    include __DIR__ . '/home.php';
} else {
    header('Location: orders/list.php');
}



?>
