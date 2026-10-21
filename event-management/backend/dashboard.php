<?php
declare(strict_types=1);
session_start();
if (empty($_SESSION['user_id'])) { header('Location: ../frontend/login.html'); exit; }
require __DIR__ . '/config.php';
$events = $pdo->query('SELECT id,title,description,event_date,location,capacity,registered_count,category FROM events ORDER BY event_date ASC')->fetchAll();
$stmt = $pdo->prepare('SELECT COUNT(*) FROM registrations WHERE user_id = :uid');
$stmt->execute(['uid' => $_SESSION['user_id']]);
$registeredCount = (int)$stmt->fetchColumn();
function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Dashboard | Event Management 2026</title><link rel="stylesheet" href="../frontend/css/style.css"></head>
<body><div class="app-layout"><aside class="sidebar">
<a class="sidebar-brand" href="dashboard.php"><span class="brand-mark">EM</span><span>Event Management</span></a>
<nav class="sidebar-nav"><p class="nav-group-title">MAIN MENU</p><a class="nav-link active" href="dashboard.php">Dashboard</a><a class="nav-link" href="#events">Browse Events</a><p class="nav-group-title">MY ACTIVITIES</p><a class="nav-link" href="my-registrations.php">My Registered Events</a><a class="nav-link" href="my-registrations.php">Event History</a><p class="nav-group-title">ACCOUNT &amp; SUPPORT</p><a class="nav-link" href="profile.php">My Profile</a></nav><div class="sidebar-bottom"><a class="nav-link" href="logout.php">Log out</a></div></aside>
<main class="main-content"><header class="topbar"><div><h1>Dashboard</h1><p class="muted">Discover and register for upcoming events.</p></div><div class="user-chip"><span class="avatar"><?= e(strtoupper(substr((string)$_SESSION['user_name'],0,1))) ?></span><span class="user-name"><?= e((string)$_SESSION['user_name']) ?></span></div></header>
<div class="section-heading"><div><h2>My Activities</h2><p>Your event participation at a glance.</p></div></div>
<section class="stat-grid"><article class="stat-card"><span>My Registered Events</span><strong><?= $registeredCount ?></strong></article><article class="stat-card"><span>Available Events</span><strong><?= count($events) ?></strong></article><article class="stat-card"><span>Event Management</span><strong>2026</strong></article></section>
<section id="events"><div class="section-heading"><div><h2>Available for Registration</h2><p>Choose an event that matches your interests.</p></div></div><div class="event-grid">
<?php foreach ($events as $i => $event): ?><article class="event-card"><div class="event-banner <?= $i%2?'alt':'' ?>"><span class="pill">OPEN</span><span class="event-symbol"><?= $i%2?'✦':'✧' ?></span></div><div class="event-body"><span class="pill"><?= e($event['category']) ?></span><h3><?= e($event['title']) ?></h3><p><?= e($event['description']) ?></p><div class="event-meta"><span>📅 <?= e(date('d M Y, H:i',strtotime($event['event_date']))) ?></span><span>📍 <?= e($event['location']) ?></span></div><div class="event-bottom"><small><?= (int)$event['registered_count'] ?>/<?= (int)$event['capacity'] ?> registered</small><a class="btn btn-primary" href="register-event.php?event_id=<?= (int)$event['id'] ?>">Daftar Sekarang</a></div></div></article><?php endforeach; ?>
</div></section></main></div></body></html>
