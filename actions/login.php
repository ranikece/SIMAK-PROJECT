<?php
require_once __DIR__ . '/../config/database.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?page=login');
    exit;
}
$username = trim($_POST['username'] ?? '');
$password = (string)($_POST['password'] ?? '');
if ($username === '' || $password === '') {
    flash('error', 'Username dan password wajib diisi.');
    header('Location: ../index.php?page=login');
    exit;
}
try {
    $stmt = db()->prepare('SELECT * FROM users WHERE username = ? AND status = "aktif" LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if (!$user || !passwordMatches($password, $user['password'])) {
        flash('error', 'Username atau password salah.');
        header('Location: ../index.php?page=login');
        exit;
    }
    session_regenerate_id(true);
    $_SESSION['user'] = [
    'id_user' => (int)$user['id_user'],
    'nama' => $user['nama'],
    'username' => $user['username'],
    'role' => $user['role'],
    ];
    flash('success', 'Login berhasil sebagai ' . roleLabel($user['role']) . '.');
    header('Location: ../index.php?page=dashboard');
    exit;
}
catch (Throwable $e) {
    flash('error', 'Gagal login: ' . $e->getMessage());
    header('Location: ../index.php?page=login');
    exit;
}
