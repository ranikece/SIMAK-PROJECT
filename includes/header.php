<?php
$role = $role ?? (currentRole() ?? 'auth');
$page = $page ?? ($_GET['page'] ?? 'dashboard');
$user = currentUser();
$menus = [
'user' => [
'dashboard' => 'Beranda',
'ambil-antrian' => 'Ambil Antrian',
'cek-antrian' => 'Cek Antrian',
'layanan' => 'Layanan Klinik',
],
'resepsionis' => [
'dashboard' => 'Dashboard',
'antrian' => 'Kelola Antrian',
'pasien' => 'Pasien',
'dokter' => 'Dokter',
'obat' => 'Obat',
'rekam-medis' => 'Rekam Medis',
'tagihan' => 'Tagihan',
],
'admin' => [
'dashboard' => 'Dashboard',
'pasien' => 'Pasien',
'dokter' => 'Dokter',
'jadwal' => 'Jadwal',
'antrian' => 'Antrian',
'rekam-medis' => 'Rekam Medis',
'laporan' => 'Laporan',
],
];
$currentMenus = $menus[$role] ?? [];
$bodyClass = $user ? 'role-' . $role : 'auth-page';
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>
<?= e(APP_NAME) ?>
</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css">
</head>
<body class="
<?= e($bodyClass) ?>
">
<header class="topbar">
<div class="topbar-inner">
<a class="brand" href="index.php?page=dashboard" aria-label="Beranda SIMAK">
<span class="brand-logo">
<img src="assets/logo-simak.jpg" alt="Logo SIMAK">
</span>
<span class="brand-copy">
<strong>SIMAK</strong>
<span>Sistem Manajemen Klinik</span>
</span>
</a>
<?php
if ($user && $currentMenus):
?>
<nav class="navbar" aria-label="Navigasi
<?= e(roleLabel($role)) ?>
">
<?php
foreach ($currentMenus as $key => $label):
?>
<a class="
<?= $page === $key ? 'active' : '' ?>
" href="index.php?page=
<?= e($key) ?>
">
<?= e($label) ?>
</a>
<?php
endforeach;
?>
<a class="logout" href="actions/logout.php">Logout</a>
</nav>
<?php
else:
?>
<nav class="auth-tabs" aria-label="Autentikasi">
<a class="
<?= $page === 'login' ? 'active' : '' ?>
" href="index.php?page=login">Login</a>
<a class="
<?= $page === 'register' ? 'active' : '' ?>
" href="index.php?page=register">Registrasi</a>
</nav>
<?php
endif;
?>
</div>
</header>
<main class="content">
<?php
foreach (takeFlash() as $item):
?>
<div class="alert
<?= e($item['type']) ?>
">
<?= e($item['message']) ?>
</div>
<?php
endforeach;
?>
