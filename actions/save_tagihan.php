<?php
require_once __DIR__ . '/../config/database.php';
requireRole(['resepsionis', 'admin']);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirectTo('resepsionis', 'tagihan');
try {
    if (($_POST['mode'] ?? 'new') === 'bayar') {
        $id = (int)$_POST['id_tagihan'];
        $tagihan = queryOne('SELECT total_tagihan FROM tagihan WHERE id_tagihan = ?', [$id]);
        $bayar = (float)$_POST['jumlah_bayar'];
        $kembalian = $bayar - (float)$tagihan['total_tagihan'];
        db()->prepare('UPDATE tagihan SET status_bayar = "lunas", metode_pembayaran = ?, jumlah_bayar = ?, kembalian = ?, waktu_bayar = NOW() WHERE id_tagihan = ?')->execute([$_POST['metode_pembayaran'], $bayar, $kembalian, $id]);
        flash('success', 'Tagihan berhasil ditandai lunas.');
    }
else {
    $idAntrian = (int)$_POST['id_antrian'];
    $antrian = queryOne('SELECT id_pasien FROM antrian WHERE id_antrian = ?', [$idAntrian]);
    $biayaKonsul = (float)$_POST['biaya_konsultasi'];
    $biayaTindakan = (float)$_POST['biaya_tindakan'];
    $biayaObat = (float)$_POST['biaya_obat'];
    $diskon = (float)$_POST['diskon'];
    $total = max(0, $biayaKonsul + $biayaTindakan + $biayaObat - $diskon);
    db()->prepare('INSERT INTO tagihan (id_antrian, id_pasien, biaya_konsultasi, biaya_tindakan, biaya_obat, diskon, total_tagihan, status_bayar, catatan) VALUES (?, ?, ?, ?, ?, ?, ?, "belum_bayar", ?)')->execute([$idAntrian, $antrian['id_pasien'], $biayaKonsul, $biayaTindakan, $biayaObat, $diskon, $total, trim($_POST['catatan'])]);
    flash('success', 'Tagihan berhasil dibuat.');
}
}
catch (Throwable $e) {
    flash('error', 'Gagal menyimpan tagihan: ' . $e->getMessage());
}
redirectTo('resepsionis', 'tagihan');
