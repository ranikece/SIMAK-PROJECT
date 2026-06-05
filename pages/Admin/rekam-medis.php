<?php
$rows = queryAll(
'SELECT *
FROM v_rekam_medis_lengkap
ORDER BY id_rekam_medis DESC
LIMIT 100'
);
?>
<div class="page-head">
<div>
<h2>Riwayat Rekam Medis</h2>
<p>Admin hanya dapat melihat riwayat rekam medis, tanpa menambah atau mengedit data.</p>
</div>
</div>
<section class="card">
<h3>Data Riwayat Rekam Medis</h3>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>Tanggal</th>
<th>Pasien</th>
<th>Dokter</th>
<th>Antrian</th>
<th>Diagnosis</th>
<th>Tindakan</th>
</tr>
</thead>
<tbody>
<?php
foreach ($rows as $r):
?>
<tr>
<td>
<?= e($r['tanggal_periksa']) ?>
</td>
<td>
<?= e($r['nama_pasien']) ?>
<br>
<span class="small note">
<?= e($r['no_rekam_medis']) ?>
</span>
</td>
<td>
<?= e($r['nama_dokter'] ?? '-') ?>
</td>
<td>
<?= e($r['no_antrian'] ?? '-') ?>
</td>
<td>
<?= e($r['diagnosis']) ?>
</td>
<td>
<?= e($r['tindakan']) ?>
</td>
</tr>
<?php
endforeach;
?>
<?php
if (!$rows):
?>
<tr>
<td colspan="6">Belum ada rekam medis.</td>
</tr>
<?php
endif;
?>
</tbody>
</table>
</div>
</section>
