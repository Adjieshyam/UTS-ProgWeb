<?php
declare(strict_types=1);
session_start();
if (empty($_SESSION['user_id'])) { header('Location: ../frontend/login.html'); exit; }
require __DIR__ . '/config.php';
$q=$pdo->prepare('SELECT r.created_at,r.status,e.title,e.event_date,e.location FROM registrations r JOIN events e ON e.id=r.event_id WHERE r.user_id=:uid ORDER BY r.created_at DESC'); $q->execute(['uid'=>$_SESSION['user_id']]); $rows=$q->fetchAll();
function e(string $s): string { return htmlspecialchars($s,ENT_QUOTES,'UTF-8'); }
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>My Registered Events</title><link rel="stylesheet" href="../frontend/css/style.css"></head><body><div class="app-layout">
<aside class="sidebar"><a class="sidebar-brand" href="dashboard.php"><span class="brand-mark">EM</span><span>Event Management</span></a><nav class="sidebar-nav"><a class="nav-link" href="dashboard.php">Dashboard</a><a class="nav-link active" href="my-registrations.php">My Registered Events</a></nav><div class="sidebar-bottom"><a class="nav-link" href="logout.php">Log out</a></div></aside>
<main class="main-content"><header class="topbar"><div><h1>My Registered Events</h1><p class="muted">Daftar event yang sudah kamu ikuti.</p></div><a class="btn btn-primary" href="dashboard.php#events">Browse Events</a></header>
<?php if(isset($_GET['success'])): ?><div class="notice">Pendaftaran berhasil disimpan.</div><?php endif; ?>
<div class="table-wrap"><table><thead><tr><th>EVENT</th><th>JADWAL</th><th>LOKASI</th><th>STATUS</th></tr></thead><tbody><?php if(!$rows): ?><tr><td colspan="4" class="empty-state">Kamu belum mendaftar event.</td></tr><?php else: foreach($rows as $r): ?><tr><td><?= e($r['title']) ?></td><td><?= e(date('d M Y, H:i',strtotime($r['event_date']))) ?></td><td><?= e($r['location']) ?></td><td><span class="pill"><?= e($r['status']) ?></span></td></tr><?php endforeach; endif; ?></tbody></table></div></main></div></body></html>
