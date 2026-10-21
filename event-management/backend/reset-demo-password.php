
<?php
declare(strict_types=1);

require __DIR__ . '/config.php';

$email = 'demo@event.test';
$newPassword = 'password';
$passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

// Periksa apakah akun demo sudah ada.
$stmt = $pdo->prepare(
    'SELECT id FROM users WHERE email = :email LIMIT 1'
);
$stmt->execute(['email' => $email]);
$user = $stmt->fetch();

if ($user) {
    // Perbarui password akun yang sudah ada.
    $stmt = $pdo->prepare(
        'UPDATE users
         SET password_hash = :password_hash
         WHERE email = :email'
    );

    $stmt->execute([
        'password_hash' => $passwordHash,
        'email' => $email
    ]);
} else {
    // Buat akun demo jika belum ada.
    $stmt = $pdo->prepare(
        'INSERT INTO users (name, email, password_hash)
         VALUES (:name, :email, :password_hash)'
    );

    $stmt->execute([
        'name' => 'Demo User',
        'email' => $email,
        'password_hash' => $passwordHash
    ]);
}

echo 'Password akun demo berhasil diperbarui.';
echo '<br>Email: demo@event.test';
echo '<br>Password: password';
?>
