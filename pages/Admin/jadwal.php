<?php
$rows = queryAll('
SELECT
d.id_dokter,
d.kode_dokter,
d.nama_dokter,
d.spesialisasi,
d.jadwal,
d.status,
l.nama_layanan
FROM dokter d
LEFT JOIN layanan l ON l.id_layanan = d.id_layanan
ORDER BY d.status = "aktif" DESC, d.nama_dokter ASC
');
?>
<div class="page-head">
<div>
<h2>Jadwal Dokter</h2>
<p>Jadwal diambil dari data dokter dan tetap berada dalam satu database SIMAK.</p>
</div>
<a class="btn light" href="index.php?page=dokter">Kelola Data Dokter</a>
</div>
<section class="card">
<h3>Daftar Jadwal</h3>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>Kode</th>
<th>Dokter</th>
<th>Spesialisasi</th>
<th>Layanan</th>
<th>Jadwal</th>
<th>Status</th>
</tr>
</thead>
<tbody>
<?php
foreach ($rows as $r):
?>
<tr>
<td>
<b>
<?= e($r['kode_dokter'] ?? '-') ?>
</b>
</td>
<td>
<?= e($r['nama_dokter']) ?>
</td>
<td>
<?= e($r['spesialisasi']) ?>
</td>
<td>
<?= e($r['nama_layanan'] ?? '-') ?>
</td>
<td>
<?= e($r['jadwal'] ?: '-') ?>
</td>
<td>
<span class="badge
<?= $r['status'] === 'aktif' ? 'success' : 'danger' ?>
">
<?= e($r['status']) ?>
</span>
</td>
</tr>
<?php
endforeach;
?>
<?php
if (!$rows):
?>
<tr>
<td colspan="6">Belum ada jadwal dokter.</td>
</tr>
<?php
endif;
?>
</tbody>
</table>
</div>
</section>
