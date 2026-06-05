<?php
require_once __DIR__ . '/../config/database.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?page=register');
    exit;
}
$nama = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = (string)($_POST['password'] ?? '');
$passwordConfirm = (string)($_POST['password_confirm'] ?? '');
$role = $_POST['role'] ?? 'user';
$allowedRoles = ['user', 'resepsionis', 'admin'];
if ($nama === '' || $username === '' || $password === '' || $passwordConfirm === '') {
    flash('error', 'Semua data registrasi wajib diisi.');
    header('Location: ../index.php?page=register');
    exit;
}
if (!preg_match('/^[a-zA-Z0-9_\.]{4,60}$/', $username)) {
    flash('error', 'Username minimal 4 karakter dan hanya boleh berisi huruf, angka, titik, atau underscore.');
    header('Location: ../index.php?page=register');
    exit;
}
if (strlen($password) < 5) {
    flash('error', 'Password minimal 5 karakter.');
    header('Location: ../index.php?page=register');
    exit;
}
if ($password !== $passwordConfirm) {
    flash('error', 'Konfirmasi password tidak sama.');
    header('Location: ../index.php?page=register');
    exit;
}
if (!in_array($role, $allowedRoles, true)) {
    flash('error', 'Role akun tidak valid.');
    header('Location: ../index.php?page=register');
    exit;
}
try {
    $exists = scalar('SELECT COUNT(*) FROM users WHERE username = ?', [$username]);
    if ((int)$exists > 0) {
        flash('error', 'Username sudah dipakai. Gunakan username lain.');
        header('Location: ../index.php?page=register');
        exit;
    }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = db()->prepare('INSERT INTO users (nama, username, password, role, status) VALUES (?, ?, ?, ?, "aktif")');
    $stmt->execute([$nama, $username, $hash, $role]);
    flash('success', 'Registrasi berhasil. Silakan login sebagai ' . roleLabel($role) . '.');
    header('Location: ../index.php?page=login');
    exit;
}
catch (Throwable $e) {
    flash('error', 'Gagal registrasi: ' . $e->getMessage());
    header('Location: ../index.php?page=register');
    exit;
}
