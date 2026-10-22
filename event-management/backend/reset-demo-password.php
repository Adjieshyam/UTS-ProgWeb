
<?php

require __DIR__ . '/config.php';

$email = 'demo@event.test';
$newPassword = 'password';
$passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare(
        'SELECT id FROM users WHERE email = :email LIMIT 1'
    );
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
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

} catch (PDOException $e) {
    http_response_code(500);
    echo 'Terjadi kesalahan database. Periksa konfigurasi dan struktur tabel.';
}
