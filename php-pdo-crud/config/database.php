<?php
/**
 * Koneksi PDO ke MySQL.
 * Kredensial dapat diatur lewat environment variable (DB_HOST, DB_NAME, DB_USER, DB_PASS);
 * jika tidak ada, dipakai nilai default XAMPP/Laragon.
 */

$host = getenv('DB_HOST') ?: 'localhost';
$db   = getenv('DB_NAME') ?: 'db_latihan';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // error dilempar sebagai exception
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // hasil berupa array asosiatif
    PDO::ATTR_EMULATE_PREPARES   => false,                  // pakai prepared statement asli
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log($e->getMessage());               // detail error hanya ke log server
    http_response_code(500);
    exit('Database tidak dapat dihubungkan.'); // pesan umum untuk pengguna
}
