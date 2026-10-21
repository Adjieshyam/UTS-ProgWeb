<?php
declare(strict_types=1);
$host = '127.0.0.1';
$dbname = 'event_management';
$username = 'root';
$password = ''; // Sesuaikan dengan konfigurasi MySQL lokal.
try {
    $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    exit('Koneksi database gagal. Periksa database dan backend/config.php.');
}
