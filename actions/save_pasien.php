<?php
require_once __DIR__ . '/../config/database.php';
requireRole(['resepsionis', 'admin']);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirectTo('resepsionis', 'pasien');
try {
    $stmt = db()->prepare('INSERT INTO pasien (nik, no_rekam_medis, nama_pasien, jenis_kelamin, tanggal_lahir, no_hp, no_telepon, alamat, wilayah, golongan_darah, alergi) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([
    trim($_POST['nik'] ?? '') ?: null,
    trim($_POST['no_rekam_medis'] ?? '') ?: null,
    trim($_POST['nama_pasien'] ?? ''),
    $_POST['jenis_kelamin'] ?? 'P',
    $_POST['tanggal_lahir'] ?? date('Y-m-d'),
    trim($_POST['no_hp'] ?? ''),
    trim($_POST['no_hp'] ?? ''),
    trim($_POST['alamat'] ?? ''),
    trim($_POST['wilayah'] ?? ''),
    trim($_POST['golongan_darah'] ?? ''),
    trim($_POST['alergi'] ?? '')
    ]);
    flash('success', 'Data pasien berhasil ditambahkan.');
}
catch (Throwable $e) {
    flash('error', 'Gagal menyimpan pasien: ' . $e->getMessage());
}
redirectTo('resepsionis', 'pasien');
