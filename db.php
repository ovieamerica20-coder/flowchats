<?php
$host = 'dpg-dakktf3l550s73fivc0-a.oregon-postgres.render.com'; // from your Render "Connect" button
$port = '5432';
$dbname = 'flowchat';
$user = 'flowchat';      // from Render
$pass = 'PrL5ZusqmlJyqDlMhNoBDVezCX10LZ9q'; // from Render "Connect" button

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$pass sslmode=require");

if (!$conn) {
    die('Database connection failed: ' . pg_last_error());
}

// Auto-create the users table on first run — no manual SQL upload needed
pg_query($conn, "
    CREATE TABLE IF NOT EXISTS users (
        id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");
?>
