<?php
require_once __DIR__ . '/../config/database.php';
requireRole(['resepsionis', 'admin']);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirectTo('resepsionis', 'rekam-medis');
try {
    $idAntrian = (int)($_POST['id_antrian'] ?? 0) ?: null;
    $idPasien = (int)($_POST['id_pasien'] ?? 0);
    $idDokter = (int)($_POST['id_dokter'] ?? 0) ?: null;
    if ($idAntrian && (!$idPasien || !$idDokter)) {
        $row = queryOne('SELECT id_pasien, id_dokter FROM antrian WHERE id_antrian = ?', [$idAntrian]);
        $idPasien = (int)$row['id_pasien'];
        $idDokter = (int)$row['id_dokter'];
    }
    $stmt = db()->prepare('INSERT INTO rekam_medis (id_antrian, id_pasien, id_dokter, tanggal_periksa, anamnesis, pemeriksaan_fisik, diagnosis, tindakan, resep_obat, catatan, tekanan_darah, suhu, nadi, berat_badan, tinggi_badan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$idAntrian, $idPasien, $idDokter, date('Y-m-d'), trim($_POST['anamnesis']), trim($_POST['pemeriksaan_fisik']), trim($_POST['diagnosis']), trim($_POST['tindakan']), trim($_POST['resep_obat']), trim($_POST['catatan']), trim($_POST['tekanan_darah']), $_POST['suhu'] ?: null, $_POST['nadi'] ?: null, $_POST['berat_badan'] ?: null, $_POST['tinggi_badan'] ?: null]);
    if ($idAntrian) db()->prepare('UPDATE antrian SET status = "SELESAI", waktu_selesai = COALESCE(waktu_selesai, NOW()), versi_data = versi_data + 1 WHERE id_antrian = ?')->execute([$idAntrian]);
    flash('success', 'Rekam medis berhasil disimpan.');
}
catch (Throwable $e) {
    flash('error', 'Gagal menyimpan rekam medis: ' . $e->getMessage());
}
redirectTo('resepsionis', 'rekam-medis');
