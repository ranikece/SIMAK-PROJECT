<?php
require_once __DIR__ . '/../config/database.php';
requireRole(['resepsionis', 'admin']);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirectTo('resepsionis', 'dokter');
try {
    $stmt = db()->prepare('INSERT INTO dokter (kode_dokter, nama_dokter, spesialisasi, id_layanan, no_telepon, email, jadwal, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([
    trim($_POST['kode_dokter'] ?? '') ?: null,
    trim($_POST['nama_dokter'] ?? ''),
    trim($_POST['spesialisasi'] ?? ''),
    (int)($_POST['id_layanan'] ?? 0) ?: null,
    trim($_POST['no_telepon'] ?? ''),
    trim($_POST['email'] ?? ''),
    trim($_POST['jadwal'] ?? ''),
    $_POST['status'] ?? 'aktif'
    ]);
    flash('success', 'Data dokter berhasil ditambahkan.');
}
catch (Throwable $e) {
    flash('error', 'Gagal menyimpan dokter: ' . $e->getMessage());
}
redirectTo('resepsionis', 'dokter');
