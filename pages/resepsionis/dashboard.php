<?php
$today = date('Y-m-d');
$stats = [
'pasien' => scalar('SELECT COUNT(*) FROM pasien'),
'dokter' => scalar('SELECT COUNT(*) FROM dokter'),
'antrian' => scalar('SELECT COUNT(*) FROM antrian WHERE tanggal=?', [$today]),
'tagihan' => scalar('SELECT COALESCE(SUM(total_tagihan),0) FROM tagihan WHERE status_bayar="lunas" AND DATE(created_at)=?', [$today]),
];
$latest = queryAll('SELECT * FROM v_antrian_aktif ORDER BY waktu_daftar DESC LIMIT 6');
?>
<section class="hero">
<h1>Dashboard Resepsionis</h1>
<div class="hero-actions">
<a class="btn" href="index.php?page=antrian">Kelola Antrian</a>
<a class="btn light" href="index.php?page=pasien">Tambah Pasien</a>
</div>
</section>
<section class="grid cols-4">
<div class="stat">
<span>Total Pasien</span>
<strong>
<?= e($stats['pasien']) ?>
</strong>
</div>
<div class="stat">
<span>Total Dokter</span>
<strong>
<?= e($stats['dokter']) ?>
</strong>
</div>
<div class="stat">
<span>Antrian Hari Ini</span>
<strong>
<?= e($stats['antrian']) ?>
</strong>
</div>
<div class="stat">
<span>Pendapatan Hari Ini</span>
<strong>
<?= rupiah($stats['tagihan']) ?>
</strong>
</div>
</section>
<section class="card">
<h3>Antrian Terbaru</h3>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>No</th>
<th>Pasien</th>
<th>Layanan</th>
<th>Dokter</th>
<th>Status</th>
<th>Waktu</th>
</tr>
</thead>
<tbody>
<?php
foreach ($latest as $r):
?>
<tr>
<td>
<b>
<?= e($r['no_antrian']) ?>
</b>
</td>
<td>
<?= e($r['nama_pasien']) ?>
</td>
<td>
<?= e($r['nama_layanan']) ?>
</td>
<td>
<?= e($r['nama_dokter'] ?? '-') ?>
</td>
<td>
<span class="badge
<?= $r['status']==='DIPANGGIL'?'warning':'success' ?>
">
<?= e($r['label_status']) ?>
</span>
</td>
<td>
<?= e($r['waktu_daftar']) ?>
</td>
</tr>
<?php
endforeach;
?>
<?php
if (!$latest):
?>
<tr>
<td colspan="6">Belum ada antrian aktif.</td>
</tr>
<?php
endif;
?>
</tbody>
</table>
</div>
</section>
