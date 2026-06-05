<?php
require_once __DIR__ . '/../config/database.php';
requireRole(['resepsionis', 'admin']);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirectTo('resepsionis', 'obat');
try {
    if (($_POST['mode'] ?? 'new') === 'restok') {
        $id = (int)$_POST['id_obat'];
        $jumlah = max(1, (int)$_POST['jumlah']);
        runTransactionWithDeadlockRetry(function (PDO $pdo) use ($id, $jumlah) {
            $stmt = $pdo->prepare('SELECT stok FROM obat WHERE id_obat = ? FOR UPDATE');
            $stmt->execute([$id]);
            $row = $stmt->fetch();
            if (!$row) throw new RuntimeException('Obat tidak ditemukan.');
            $stokLama = (int)$row['stok'];
            $stokBaru = $stokLama + $jumlah;
            $pdo->prepare('UPDATE obat SET stok = ? WHERE id_obat = ?')->execute([$stokBaru, $id]);
            $pdo->prepare('INSERT INTO log_stok_obat (id_obat, jenis_perubahan, jumlah, stok_sebelum, stok_sesudah, keterangan) VALUES (?, "masuk", ?, ?, ?, "Restok manual")')->execute([$id, $jumlah, $stokLama, $stokBaru]);
        }
        );
        flash('success', 'Stok obat berhasil ditambahkan.');
    }
else {
    $stmt = db()->prepare('INSERT INTO obat (kode_obat, nama_obat, jenis, satuan, stok, stok_minimum, harga) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([trim($_POST['kode_obat']), trim($_POST['nama_obat']), trim($_POST['jenis']), trim($_POST['satuan']), (int)$_POST['stok'], (int)$_POST['stok_minimum'], (float)$_POST['harga']]);
    flash('success', 'Data obat berhasil ditambahkan.');
}
}
catch (Throwable $e) {
    flash('error', 'Gagal menyimpan obat: ' . $e->getMessage());
}
redirectTo('resepsionis', 'obat');
