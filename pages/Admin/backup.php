<?php
$logs = queryAll('SELECT * FROM backup_log ORDER BY id_backup DESC LIMIT 30');
?>
<div class="page-head">
<div>
<h2>Backup Database</h2>
<p>Backup dibuat untuk database tunggal <b>simak_terpadu</b>. Kalau mysqldump tidak bisa dipanggil dari PHP, jalankan file batch di folder scripts/backup.</p>
</div>
<form method="post" action="actions/backup.php">
<button type="submit">Backup Sekarang</button>
</form>
</div>
<section class="card">
<h3>Cara Backup Manual</h3>
<pre>
<code>cd C:\laragon\www\simak-terpadu\scripts\backup
mysqlbackup.bat</code>
</pre>
</section>
<section class="card">
<h3>Riwayat Backup</h3>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>File</th>
<th>Metode</th>
<th>Status</th>
<th>Keterangan</th>
<th>Waktu</th>
</tr>
</thead>
<tbody>
<?php
foreach ($logs as $r):
?>
<tr>
<td>
<b>
<?= e($r['nama_file']) ?>
</b>
<br>
<span class="small note">
<?= e($r['lokasi_file']) ?>
</span>
</td>
<td>
<?= e($r['metode']) ?>
</td>
<td>
<span class="badge
<?= $r['status']==='BERHASIL'?'success':'warning' ?>
">
<?= e($r['status']) ?>
</span>
</td>
<td>
<?= e($r['keterangan'] ?? '-') ?>
</td>
<td>
<?= e($r['created_at']) ?>
</td>
</tr>
<?php
endforeach;
?>
<?php
if (!$logs):
?>
<tr>
<td colspan="5">Belum ada riwayat backup.</td>
</tr>
<?php
endif;
?>
</tbody>
</table>
</div>
</section>
