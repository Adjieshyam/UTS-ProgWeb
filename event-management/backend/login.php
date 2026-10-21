<?php
declare(strict_types=1);
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ../frontend/login.html'); exit; }
require __DIR__ . '/config.php';
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$plainPassword = (string)($_POST['password'] ?? '');
if (!$email || $plainPassword === '') { header('Location: ../frontend/login.html?error=invalid'); exit; }
$stmt = $pdo->prepare('SELECT id, name, email, password_hash FROM users WHERE email = :email LIMIT 1');
$stmt->execute(['email' => $email]);
$user = $stmt->fetch();
if (!$user || !password_verify($plainPassword, $user['password_hash'])) {
    header('Location: ../frontend/login.html?error=login'); exit;
}
session_regenerate_id(true);
$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['user_email'] = $user['email'];
header('Location: dashboard.php'); exit;
