<?php
$today = date('Y-m-d');
$rows = queryAll('SELECT * FROM v_antrian_aktif ORDER BY prioritas="DARURAT" DESC, waktu_daftar ASC');
$riwayat = queryAll('SELECT a.*, fn_label_status(a.status) AS label_status, p.nama_pasien, l.nama_layanan, d.nama_dokter FROM antrian a JOIN pasien p ON a.id_pasien=p.id_pasien JOIN layanan l ON a.id_layanan=l.id_layanan LEFT JOIN dokter d ON a.id_dokter=d.id_dokter WHERE a.tanggal=? AND a.status IN ("SELESAI","BATAL") ORDER BY a.waktu_daftar DESC LIMIT 10', [$today]);
?>
<div class="page-head">
<div>
<h2>Kelola Antrian</h2>
<p>Fitur resepsionis untuk mengubah status antrian. Pasien tidak punya akses tombol ini.</p>
</div>
</div>
<section class="card">
<h3>Antrian Aktif</h3>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>No</th>
<th>Pasien</th>
<th>Layanan</th>
<th>Dokter</th>
<th>Status</th>
<th>Keluhan</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>
<?php
foreach ($rows as $r):
?>
<tr>
<td>
<b>
<?= e($r['no_antrian']) ?>
</b>
<?php
if ($r['prioritas']==='DARURAT'):
?>
<br>
<span class="badge danger">Darurat</span>
<?php
endif;
?>
</td>
<td>
<?= e($r['nama_pasien']) ?>
<br>
<span class="small note">
<?= e($r['no_rekam_medis']) ?>
</span>
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
<?= e($r['keluhan'] ?? '-') ?>
</td>
<td>
<div class="actions">
<?php
if ($r['status']==='MENUNGGU'):
?>
<form method="post" action="actions/update_status.php">
<input type="hidden" name="id_antrian" value="
<?= e($r['id_antrian']) ?>
">
<input type="hidden" name="aksi" value="panggil">
<button type="submit">Panggil</button>
</form>
<?php
endif;
?>
<?php
if ($r['status']==='DIPANGGIL'):
?>
<form method="post" action="actions/update_status.php">
<input type="hidden" name="id_antrian" value="
<?= e($r['id_antrian']) ?>
">
<input type="hidden" name="aksi" value="selesai">
<button type="submit" class="light">Selesaikan</button>
</form>
<?php
endif;
?>
<form method="post" action="actions/update_status.php" onsubmit="return confirm('Batalkan antrian ini?')">
<input type="hidden" name="id_antrian" value="
<?= e($r['id_antrian']) ?>
">
<input type="hidden" name="aksi" value="batal">
<button type="submit" class="danger">Batal</button>
</form>
</div>
</td>
</tr>
<?php
endforeach;
?>
<?php
if (!$rows):
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
<section class="card">
<h3>Riwayat Hari Ini</h3>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>No</th>
<th>Pasien</th>
<th>Layanan</th>
<th>Status</th>
<th>Waktu Selesai</th>
</tr>
</thead>
<tbody>
<?php
foreach ($riwayat as $r):
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
<span class="badge
<?= $r['status']==='BATAL'?'danger':'success' ?>
">
<?= e($r['label_status']) ?>
</span>
</td>
<td>
<?= e($r['waktu_selesai'] ?? '-') ?>
</td>
</tr>
<?php
endforeach;
?>
<?php
if (!$riwayat):
?>
<tr>
<td colspan="5">Belum ada riwayat.</td>
</tr>
<?php
endif;
?>
</tbody>
</table>
</div>
</section>
