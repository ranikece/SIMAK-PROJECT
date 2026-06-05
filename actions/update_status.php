<?php
require_once __DIR__ . '/../config/database.php';
requireRole(['resepsionis', 'admin']);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirectTo('resepsionis', 'antrian');
$idAntrian = (int)($_POST['id_antrian'] ?? 0);
$aksi = $_POST['aksi'] ?? '';
if ($idAntrian <= 0 || !in_array($aksi, ['panggil', 'selesai', 'batal'], true)) {
    flash('error', 'Aksi status antrian tidak valid.');
    redirectTo('resepsionis', 'antrian');
}
try {
    $message = runTransactionWithDeadlockRetry(function (PDO $pdo) use ($idAntrian, $aksi) {
        $stmt = $pdo->prepare('SELECT * FROM antrian WHERE id_antrian = ? FOR UPDATE');
        $stmt->execute([$idAntrian]);
        $row = $stmt->fetch();
        if (!$row) throw new RuntimeException('Data antrian tidak ditemukan.');
        if ($aksi === 'panggil') {
            if ($row['status'] !== 'MENUNGGU') throw new RuntimeException('Hanya status MENUNGGU yang bisa dipanggil.');
            $stmt = $pdo->prepare('UPDATE antrian SET status = "DIPANGGIL", waktu_dipanggil = COALESCE(waktu_dipanggil, NOW()), versi_data = versi_data + 1 WHERE id_antrian = ?');
            $stmt->execute([$idAntrian]);
            return 'Nomor ' . $row['no_antrian'] . ' berhasil dipanggil.';
        }
        if ($aksi === 'selesai') {
            if ($row['status'] !== 'DIPANGGIL') throw new RuntimeException('Hanya status DIPANGGIL yang bisa diselesaikan.');
            $stmt = $pdo->prepare('UPDATE antrian SET status = "SELESAI", waktu_selesai = COALESCE(waktu_selesai, NOW()), versi_data = versi_data + 1 WHERE id_antrian = ?');
            $stmt->execute([$idAntrian]);
            return 'Nomor ' . $row['no_antrian'] . ' berhasil diselesaikan.';
        }
        if (!in_array($row['status'], ['MENUNGGU', 'DIPANGGIL'], true)) throw new RuntimeException('Antrian ini sudah tidak bisa dibatalkan.');
        $stmt = $pdo->prepare('UPDATE antrian SET status = "BATAL", versi_data = versi_data + 1 WHERE id_antrian = ?');
        $stmt->execute([$idAntrian]);
        return 'Nomor ' . $row['no_antrian'] . ' berhasil dibatalkan.';
    }
    );
    flash('success', $message);
}
catch (Throwable $e) {
    flash('error', 'Gagal mengubah status: ' . $e->getMessage());
}
redirectTo('resepsionis', 'antrian');
