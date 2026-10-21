<?php
declare(strict_types=1);
session_start();
if (empty($_SESSION['user_id'])) { header('Location: ../frontend/login.html'); exit; }
function e(string $s): string { return htmlspecialchars($s,ENT_QUOTES,'UTF-8'); }
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>My Profile</title><link rel="stylesheet" href="../frontend/css/style.css"></head><body><div class="app-layout">
<aside class="sidebar"><a class="sidebar-brand" href="dashboard.php"><span class="brand-mark">EM</span><span>Event Management</span></a><nav class="sidebar-nav"><a class="nav-link" href="dashboard.php">Dashboard</a><a class="nav-link active" href="profile.php">My Profile</a><a class="nav-link" href="my-registrations.php">My Registered Events</a></nav><div class="sidebar-bottom"><a class="nav-link" href="logout.php">Log out</a></div></aside>
<main class="main-content"><header class="topbar"><div><h1>My Profile</h1><p class="muted">Informasi akun yang sedang masuk.</p></div></header><section class="page-card"><div class="avatar"><?= e(strtoupper(substr((string)$_SESSION['user_name'],0,1))) ?></div><h2 style="margin-top:16px"><?= e((string)$_SESSION['user_name']) ?></h2><p><?= e((string)$_SESSION['user_email']) ?></p><a class="btn btn-primary" href="logout.php">Log out</a></section></main></div></body></html>
