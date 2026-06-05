<?php
$q = trim($_GET['q'] ?? $_GET['no'] ?? '');
$hasil = [];
if ($q !== '') {
    $hasil = queryAll('SELECT * FROM v_antrian_aktif WHERE no_antrian LIKE ? OR nik LIKE ? OR no_hp LIKE ? OR no_telepon LIKE ? ORDER BY waktu_daftar DESC', ["%$q%", "%$q%", "%$q%", "%$q%"]);
    if (!$hasil) {
        $hasil = queryAll('SELECT a.*, fn_label_status(a.status) AS label_status, p.nama_pasien, p.nik, p.no_hp, l.nama_layanan, d.nama_dokter, s.nama_site FROM antrian a JOIN pasien p ON a.id_pasien=p.id_pasien JOIN layanan l ON a.id_layanan=l.id_layanan LEFT JOIN dokter d ON a.id_dokter=d.id_dokter JOIN sites s ON a.id_site=s.id_site WHERE a.no_antrian LIKE ? OR p.nik LIKE ? OR p.no_hp LIKE ? ORDER BY a.waktu_daftar DESC LIMIT 10', ["%$q%", "%$q%", "%$q%"]);
    }
}
?>
<div class="page-head">
<div>
<h2>Cek Antrian</h2>
<p>Cari berdasarkan nomor antrian, NIK, atau nomor HP.</p>
</div>
</div>
<section class="card">
<form method="get" class="form-grid">
<input type="hidden" name="role" value="user">
<input type="hidden" name="page" value="cek-antrian">
<div class="form-row full">
<label>Kata Kunci</label>
<input name="q" value="
<?= e($q) ?>
" placeholder="UM-001 / 1871xxxxxxxxxxxx / 0812...">
</div>
<div class="form-row full">
<button type="submit">Cek Status</button>
</div>
</form>
</section>
<?php
if ($q !== ''):
?>
<section class="card">
<h3>Hasil Pencarian</h3>
<?php
if ($hasil):
?>
<div class="grid cols-2">
<?php
foreach ($hasil as $row):
?>
<div class="queue-card">
<span class="badge
<?= ($row['status'] ?? '') === 'DIPANGGIL' ? 'warning' : (($row['status'] ?? '') === 'SELESAI' ? 'success' : 'dark') ?>
">
<?= e($row['label_status'] ?? $row['status']) ?>
</span>
<div class="queue-number">
<?= e($row['no_antrian']) ?>
</div>
<div>
<b>
<?= e($row['nama_pasien']) ?>
</b> •
<?= e($row['nama_layanan']) ?>
</div>
<div class="note small">Dokter:
<?= e($row['nama_dokter'] ?? '-') ?>
• Lokasi:
<?= e($row['nama_site']) ?>
</div>
</div>
<?php
endforeach;
?>
</div>
<?php
else:
?>
<p class="note">Data antrian tidak ditemukan.</p>
<?php
endif;
?>
</section>
<?php
endif;
?>
