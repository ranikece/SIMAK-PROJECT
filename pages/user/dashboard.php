<?php
$today = date('Y-m-d');
$stats = [
'total' => scalar('SELECT COUNT(*) FROM antrian WHERE tanggal = ?', [$today]),
'menunggu' => scalar('SELECT COUNT(*) FROM antrian WHERE tanggal = ? AND status = "MENUNGGU"', [$today]),
'dipanggil' => scalar('SELECT COUNT(*) FROM antrian WHERE tanggal = ? AND status = "DIPANGGIL"', [$today]),
'selesai' => scalar('SELECT COUNT(*) FROM antrian WHERE tanggal = ? AND status = "SELESAI"', [$today]),
];
$dipanggil = queryAll('SELECT * FROM v_antrian_aktif WHERE status = "DIPANGGIL" ORDER BY waktu_dipanggil DESC LIMIT 4');
$aktif = queryAll('SELECT * FROM v_antrian_aktif ORDER BY prioritas = "DARURAT" DESC, waktu_daftar ASC LIMIT 8');
?>
<section class="hero">
<h1>Antrian klinik jadi lebih rapi dan cepat.</h1>
<div class="hero-actions">
<a class="btn" href="index.php?page=ambil-antrian">Ambil Antrian Sekarang</a>
<a class="btn light" href="index.php?page=cek-antrian">Cek Nomor Antrian</a>
</div>
</section>
<section class="grid cols-4 dashboard-stats">
<div class="stat">
<span>Total Antrian Hari Ini</span>
<strong>
<?= e($stats['total']) ?>
</strong>
</div>
<div class="stat">
<span>Menunggu</span>
<strong>
<?= e($stats['menunggu']) ?>
</strong>
</div>
<div class="stat">
<span>Sedang Dipanggil</span>
<strong>
<?= e($stats['dipanggil']) ?>
</strong>
</div>
<div class="stat">
<span>Selesai Dilayani</span>
<strong>
<?= e($stats['selesai']) ?>
</strong>
</div>
</section>
<section class="card">
<h3>Sedang Dipanggil</h3>
<?php
if ($dipanggil):
?>
<div class="grid cols-2">
<?php
foreach ($dipanggil as $row):
?>
<div class="queue-card">
<span class="badge warning">Dipanggil</span>
<div class="queue-number">
<?= e($row['no_antrian']) ?>
</div>
<div>
<b>
<?= e($row['nama_layanan']) ?>
</b> —
<?= e($row['nama_dokter'] ?? '-') ?>
</div>
<div class="note small">
<?= e($row['nama_site']) ?>
• Pasien:
<?= e($row['nama_pasien']) ?>
</div>
</div>
<?php
endforeach;
?>
</div>
<?php
else:
?>
<p class="note">Belum ada pasien yang sedang dipanggil.</p>
<?php
endif;
?>
</section>
<section class="card">
<h3>Antrian Aktif Hari Ini</h3>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>No</th>
<th>Pasien</th>
<th>Layanan</th>
<th>Dokter</th>
<th>Lokasi</th>
<th>Status</th>
<th>Daftar</th>
</tr>
</thead>
<tbody>
<?php
foreach ($aktif as $row):
?>
<tr>
<td>
<b>
<?= e($row['no_antrian']) ?>
</b>
</td>
<td>
<?= e($row['nama_pasien']) ?>
</td>
<td>
<?= e($row['nama_layanan']) ?>
</td>
<td>
<?= e($row['nama_dokter'] ?? '-') ?>
</td>
<td>
<?= e($row['nama_site']) ?>
</td>
<td>
<span class="badge
<?= $row['status'] === 'DIPANGGIL' ? 'warning' : 'success' ?>
">
<?= e($row['label_status']) ?>
</span>
</td>
<td>
<?= e($row['waktu_daftar']) ?>
</td>
</tr>
<?php
endforeach;
?>
<?php
if (!$aktif):
?>
<tr>
<td colspan="7">Belum ada antrian aktif.</td>
</tr>
<?php
endif;
?>
</tbody>
</table>
</div>
</section>
