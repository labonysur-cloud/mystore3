<?php
$host = 'localhost';  // XAMPP MySQL server host
$db   = 'mystore';    // Your local database name
$user = 'root';       // Default XAMPP MySQL username
$pass = getenv('DB_PASSWORD') ?: '';   // Use environment variable or empty password for local setups
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    exit('Database connection failed: ' . $e->getMessage());
}
?>
