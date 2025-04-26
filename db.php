<?php
// Винаги абсолютен път до базата
$db_path = __DIR__ . '/database.sqlite';
$db = new SQLite3($db_path);

// Създаване на таблици ако не съществуват
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT UNIQUE,
    password_hash TEXT,
    role TEXT DEFAULT 'user'
)");

$db->exec("CREATE TABLE IF NOT EXISTS orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    date TEXT DEFAULT CURRENT_TIMESTAMP,
    order_type VARCHAR(255),
    quantity VARCHAR(255),
    modeling VARCHAR(255),
    material VARCHAR(255),
    color VARCHAR(255),
    delivery VARCHAR(255),
    recipient_name VARCHAR(255),
    recipient_phone VARCHAR(255),
    address TEXT,
    comment TEXT,
    status TEXT DEFAULT 'Нова',
    FOREIGN KEY(user_id) REFERENCES users(id)
)");
?>
