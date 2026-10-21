<?php
declare(strict_types=1);
session_start();
if (empty($_SESSION['user_id'])) { header('Location: ../frontend/login.html'); exit; }
require __DIR__ . '/config.php';
function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
$eventId = filter_input(INPUT_GET, 'event_id', FILTER_VALIDATE_INT);
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $eventId = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);
  $phone = trim((string)($_POST['phone'] ?? ''));
  $notes = trim((string)($_POST['notes'] ?? ''));
  $q = $pdo->prepare('SELECT id,capacity,registered_count FROM events WHERE id=:id'); $q->execute(['id'=>$eventId ?: 0]); $ev = $q->fetch();
  if (!$ev) $error = 'Event tidak ditemukan.';
  else {
    $q = $pdo->prepare('SELECT id FROM registrations WHERE user_id=:uid AND event_id=:eid'); $q->execute(['uid'=>$_SESSION['user_id'],'eid'=>$eventId]);
    if ($q->fetch()) $error = 'Kamu sudah terdaftar pada event ini.';
    elseif ((int)$ev['registered_count'] >= (int)$ev['capacity']) $error = 'Kuota event sudah penuh.';
    else {
      try {
        $pdo->beginTransaction();
        $q = $pdo->prepare('INSERT INTO registrations (user_id,event_id,phone,notes) VALUES (:uid,:eid,:phone,:notes)');
        $q->execute(['uid'=>$_SESSION['user_id'],'eid'=>$eventId,'phone'=>$phone ?: null,'notes'=>$notes ?: null]);
        $q = $pdo->prepare('UPDATE events SET registered_count=registered_count+1 WHERE id=:id'); $q->execute(['id'=>$eventId]);
        $pdo->commit(); header('Location: my-registrations.php?success=1'); exit;
      } catch (Throwable $ex) { if ($pdo->inTransaction()) $pdo->rollBack(); $error = 'Pendaftaran gagal diproses. Silakan coba lagi.'; }
    }
  }
}
$q = $pdo->prepare('SELECT id,title,event_date,location,capacity,registered_count FROM events WHERE id=:id'); $q->execute(['id'=>$eventId ?: 0]); $event = $q->fetch();
if (!$event) { http_response_code(404); exit('Event tidak ditemukan. <a href="dashboard.php">Kembali</a>'); }
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Daftar Event</title><link rel="stylesheet" href="../frontend/css/style.css"></head><body>
<div class="app-layout"><aside class="sidebar"><a class="sidebar-brand" href="dashboard.php"><span class="brand-mark">EM</span><span>Event Management</span></a><nav class="sidebar-nav"><a class="nav-link" href="dashboard.php">Dashboard</a><a class="nav-link active" href="dashboard.php#events">Browse Events</a><a class="nav-link" href="my-registrations.php">My Registered Events</a></nav><div class="sidebar-bottom"><a class="nav-link" href="logout.php">Log out</a></div></aside>
<main class="main-content"><header class="topbar"><div><h1>Daftar Event</h1><p class="muted">Lengkapi data pendaftaran di bawah ini.</p></div><a class="btn" href="dashboard.php">Kembali</a></header><section class="page-card"><h2><?= e($event['title']) ?></h2><p><?= e(date('d M Y, H:i',strtotime($event['event_date']))) ?> · <?= e($event['location']) ?></p>
<?php if ($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>
<form method="post"><input type="hidden" name="event_id" value="<?= (int)$event['id'] ?>"><div class="form-grid">
<div class="field"><label>Nama peserta</label><input value="<?= e((string)$_SESSION['user_name']) ?>" disabled></div><div class="field"><label>Email</label><input value="<?= e((string)$_SESSION['user_email']) ?>" disabled></div>
<div class="field"><label for="phone">Nomor telepon</label><input id="phone" name="phone" type="tel" placeholder="08xxxxxxxxxx"></div><div class="field"><label for="notes">Catatan (opsional)</label><input id="notes" name="notes" type="text" placeholder="Catatan tambahan"></div>
<div class="field full"><button class="btn btn-primary" type="submit">Konfirmasi Pendaftaran</button></div></div></form></section></main></div></body></html>
