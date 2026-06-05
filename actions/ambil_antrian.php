<?php
require_once __DIR__ . '/../config/database.php';
requireRole('user');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectTo('user', 'ambil-antrian');
}
$nik = preg_replace('/\s+/', '', trim($_POST['nik'] ?? ''));
$nama = trim($_POST['nama_pasien'] ?? '');
$jk = $_POST['jenis_kelamin'] ?? 'P';
$tgl = $_POST['tanggal_lahir'] ?? '';
$hp = trim($_POST['no_hp'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$wilayah = trim($_POST['wilayah'] ?? '');
$idLayanan = (int)($_POST['id_layanan'] ?? 0);
$prioritas = $_POST['prioritas'] ?? 'NORMAL';
$keluhan = trim($_POST['keluhan'] ?? '');
if (!in_array($jk, ['L', 'P'], true)) $jk = 'P';
if (!in_array($prioritas, ['NORMAL', 'DARURAT'], true)) $prioritas = 'NORMAL';
if ($nik === '' || $nama === '' || $tgl === '' || $hp === '' || $alamat === '' || $wilayah === '' || $idLayanan <= 0) {
    flash('error', 'Data belum lengkap. Isi semua field wajib.');
    redirectTo('user', 'ambil-antrian');
}
if (!preg_match('/^[0-9]{16}$/', $nik)) {
    flash('error', 'NIK harus berisi 16 digit angka.');
    redirectTo('user', 'ambil-antrian');
}
try {
    $result = runTransactionWithDeadlockRetry(function (PDO $pdo) use ($nik, $nama, $jk, $tgl, $hp, $alamat, $wilayah, $idLayanan, $prioritas, $keluhan) {
        $stmt = $pdo->prepare('SELECT * FROM layanan WHERE id_layanan = ? AND aktif = 1 FOR UPDATE');
        $stmt->execute([$idLayanan]);
        $layanan = $stmt->fetch();
        if (!$layanan) throw new RuntimeException('Layanan tidak ditemukan atau tidak aktif.');
        $stmt = $pdo->prepare('SELECT * FROM pasien WHERE nik = ? FOR UPDATE');
        $stmt->execute([$nik]);
        $pasien = $stmt->fetch();
        if ($pasien) {
            $dataBerbeda = normalizeText($pasien['nama_pasien']) !== normalizeText($nama)
            || $pasien['jenis_kelamin'] !== $jk
            || $pasien['tanggal_lahir'] !== $tgl
            || normalizePhone($pasien['no_hp'] ?? $pasien['no_telepon']) !== normalizePhone($hp)
            || normalizeText($pasien['alamat']) !== normalizeText($alamat)
            || normalizeText($pasien['wilayah']) !== normalizeText($wilayah);
            if ($dataBerbeda) {
                throw new RuntimeException('NIK ' . $nik . ' sudah terdaftar atas nama ' . $pasien['nama_pasien'] . '. Data tidak boleh diganti otomatis.');
            }
            $idPasien = (int)$pasien['id_pasien'];
        }
    else {
        $stmt = $pdo->prepare('INSERT INTO pasien (nik, nama_pasien, jenis_kelamin, tanggal_lahir, no_hp, no_telepon, alamat, wilayah) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$nik, $nama, $jk, $tgl, $hp, $hp, $alamat, $wilayah]);
        $idPasien = (int)$pdo->lastInsertId();
    }
    $today = date('Y-m-d');
    $stmt = $pdo->prepare('SELECT no_antrian FROM antrian WHERE tanggal = ? AND id_pasien = ? AND id_layanan = ? AND status IN ("MENUNGGU", "DIPANGGIL") LIMIT 1 FOR UPDATE');
    $stmt->execute([$today, $idPasien, $idLayanan]);
    $aktif = $stmt->fetch();
    if ($aktif) throw new RuntimeException('Pasien ini sudah punya antrian aktif pada layanan yang sama: ' . $aktif['no_antrian']);
    $stmt = $pdo->prepare('SELECT id_antrian FROM antrian WHERE tanggal = ? AND id_layanan = ? ORDER BY id_antrian FOR UPDATE');
    $stmt->execute([$today, $idLayanan]);
    $urutan = count($stmt->fetchAll()) + 1;
    if ($urutan > (int)$layanan['kapasitas_harian']) throw new RuntimeException('Kuota layanan hari ini sudah penuh.');
    $noAntrian = $layanan['kode_layanan'] . '-' . str_pad((string)$urutan, 3, '0', STR_PAD_LEFT);
    $stmt = $pdo->prepare('SELECT id_dokter FROM dokter WHERE id_layanan = ? AND status = "aktif" ORDER BY id_dokter LIMIT 1');
    $stmt->execute([$idLayanan]);
    $dokter = $stmt->fetch();
    $idDokter = $dokter['id_dokter'] ?? null;
    $stmt = $pdo->prepare('INSERT INTO antrian (no_antrian, id_pasien, id_layanan, id_dokter, id_site, tanggal, prioritas, status, keluhan, source_node) VALUES (?, ?, ?, ?, ?, ?, ?, "MENUNGGU", ?, "MASTER-1")');
    $stmt->execute([$noAntrian, $idPasien, $idLayanan, $idDokter, $layanan['id_site'], $today, $prioritas, $keluhan]);
    return ['no' => $noAntrian, 'layanan' => $layanan['nama_layanan'], 'estimasi' => ceil($urutan * 7.5)];
}
);
flash('success', 'Ambil antrian berhasil disimpan. Nomor antrian Anda: ' . $result['no'] . ' - ' . $result['layanan'] . '. Estimasi tunggu sekitar ' . $result['estimasi'] . ' menit.');
redirectTo('user', 'ambil-antrian');
}
catch (Throwable $e) {
    flash('error', 'Gagal membuat antrian: ' . $e->getMessage());
    redirectTo('user', 'ambil-antrian');
}
