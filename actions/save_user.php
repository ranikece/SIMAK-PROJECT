<?php
require_once __DIR__ . '/../config/database.php';
requireRole('admin');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectTo('admin', 'users');
}
$nama = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = (string)($_POST['password'] ?? '');
$role = $_POST['role'] ?? 'user';
$status = $_POST['status'] ?? 'aktif';
if ($nama === '' || $username === '' || $password === '') {
    flash('error', 'Nama, username, dan password wajib diisi.');
    redirectTo('admin', 'users');
}
if (!in_array($role, ['user', 'resepsionis', 'admin'], true)) {
    flash('error', 'Role user tidak valid.');
    redirectTo('admin', 'users');
}
if (!in_array($status, ['aktif', 'nonaktif'], true)) {
    $status = 'aktif';
}
try {
    db()->prepare('INSERT INTO users (nama, username, password, role, status) VALUES (?, ?, ?, ?, ?)')
    ->execute([$nama, $username, password_hash($password, PASSWORD_DEFAULT), $role, $status]);
    flash('success', 'User sistem berhasil ditambahkan.');
}
catch (Throwable $e) {
    flash('error', 'Gagal menyimpan user: ' . $e->getMessage());
}
redirectTo('admin', 'users');
