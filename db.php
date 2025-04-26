<?php
$db = new SQLite3('database.sqlite');

// Таблица за потребителите
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT UNIQUE,
    password_hash TEXT,
    role TEXT DEFAULT 'user'
)");

// Таблица за поръчките
$db->exec("CREATE TABLE IF NOT EXISTS orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    date TEXT DEFAULT CURRENT_TIMESTAMP,
    quantity VARCHAR(255),
    modeling VARCHAR(255),
    material VARCHAR(255),
    color VARCHAR(255),
    delivery VARCHAR(255),
    comment TEXT,
    recipient_name VARCHAR(255),
    recipient_phone VARCHAR(255),
    address TEXT,
    status TEXT DEFAULT 'Нова',
    FOREIGN KEY(user_id) REFERENCES users(id)
)");
?>
