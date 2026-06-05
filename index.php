<?php
require_once __DIR__ . '/config/database.php';
$page = $_GET['page'] ?? (isLoggedIn() ? 'dashboard' : 'login');
$authPages = ['login', 'register'];
$routes = [
'auth' => [
'login' => 'pages/auth/login.php',
'register' => 'pages/auth/register.php',
],
'user' => [
'dashboard' => 'pages/user/dashboard.php',
'ambil-antrian' => 'pages/user/ambil-antrian.php',
'cek-antrian' => 'pages/user/cek-antrian.php',
'layanan' => 'pages/user/layanan.php',
],
'resepsionis' => [
'dashboard' => 'pages/resepsionis/dashboard.php',
'antrian' => 'pages/resepsionis/antrian.php',
'pasien' => 'pages/resepsionis/pasien.php',
'dokter' => 'pages/resepsionis/dokter.php',
'obat' => 'pages/resepsionis/obat.php',
'rekam-medis' => 'pages/resepsionis/rekam-medis.php',
'tagihan' => 'pages/resepsionis/tagihan.php',
],
'admin' => [
'dashboard' => 'pages/admin/dashboard.php',
'pasien' => 'pages/resepsionis/pasien.php',
'dokter' => 'pages/resepsionis/dokter.php',
'jadwal' => 'pages/admin/jadwal.php',
'antrian' => 'pages/resepsionis/antrian.php',
'rekam-medis' => 'pages/admin/rekam-medis.php',
'users' => 'pages/admin/users.php',
'laporan' => 'pages/admin/laporan.php',
],
];
if (in_array($page, $authPages, true)) {
    if (isLoggedIn()) {
        header('Location: index.php?page=dashboard');
        exit;
    }
    $role = 'auth';
    $file = $routes['auth'][$page];
}
else {
    if (!isLoggedIn()) {
        flash('error', 'Silakan login terlebih dahulu.');
        header('Location: index.php?page=login');
        exit;
    }
    $role = currentRole() ?? 'user';
    if (!isset($routes[$role])) {
        $_SESSION = [];
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
    $file = $routes[$role][$page] ?? $routes[$role]['dashboard'];
    if (!isset($routes[$role][$page])) {
        $page = 'dashboard';
    }
}
require __DIR__ . '/includes/header.php';
try {
    require __DIR__ . '/' . $file;
}
catch (PDOException $e) {
    echo '<section class="card danger"><h2>Database belum siap</h2>';
    echo '<p>Import dulu file <code>scripts/simak_terpadu.sql</code> ke phpMyAdmin. Database yang dipakai: <b>simak_terpadu</b>.</p>';
    echo '<pre>' . e($e->getMessage()) . '</pre></section>';
}
catch (Throwable $e) {
    echo '<section class="card danger"><h2>Terjadi error</h2>';
    echo '<pre>' . e($e->getMessage()) . '</pre></section>';
}
require __DIR__ . '/includes/footer.php';
