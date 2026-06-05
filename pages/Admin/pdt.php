<?php
$alokasi = queryAll('SELECT a.*, s.nama_site FROM alokasi_data a JOIN sites s ON a.id_site=s.id_site ORDER BY a.id_alokasi');
$audit = queryAll('SELECT * FROM audit_antrian ORDER BY id_audit DESC LIMIT 8');
$repl = queryAll('SELECT * FROM repl_event_log ORDER BY id_event DESC LIMIT 8');
?>
<div class="page-head">
<div>
<h2>Implementasi Materi PDT</h2>
<p>Materi PDT tidak dijadikan menu di halaman pasien, tetapi konsepnya dipakai di fitur sistem.</p>
</div>
</div>
<section class="card">
<h3>Mapping Materi ke Fitur</h3>
<div class="impl-grid">
<div class="impl-item">
<b>Views & Joins</b>
<span>Dipakai pada dashboard, laporan, v_antrian_aktif, v_laporan_harian, v_tagihan_lengkap, dan v_rekam_medis_lengkap.</span>
</div>
<div class="impl-item">
<b>Set Operations</b>
<span>Dipakai pada laporan pasien selesai tetapi belum memiliki rekam medis.</span>
</div>
<div class="impl-item">
<b>Transaction</b>
<span>Dipakai saat ambil antrian, update status, dan restok obat.</span>
</div>
<div class="impl-item">
<b>Deadlock Management</b>
<span>Transaksi memakai FOR UPDATE dan retry jika terjadi deadlock/lock timeout.</span>
</div>
<div class="impl-item">
<b>Function</b>
<span>fn_hitung_umur, hitung_usia, fn_label_status, fn_estimasi_tunggu, dan cek_stok_obat.</span>
</div>
<div class="impl-item">
<b>Trigger</b>
<span>Trigger pasien, antrian, obat, dan rekam medis mencatat audit/log otomatis.</span>
</div>
<div class="impl-item">
<b>Backup</b>
<span>Halaman Admin Backup dan script scripts/backup/mysqlbackup.bat.</span>
</div>
<div class="impl-item">
<b>Fragmentasi, Alokasi, Replikasi</b>
<span>Direpresentasikan pada tabel alokasi_data, repl_event_log, dan script pdt/replikasi.</span>
</div>
</div>
</section>
<section class="card">
<h3>Alokasi Data</h3>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>Fragmen</th>
<th>Jenis</th>
<th>Site</th>
<th>Tabel Asal</th>
<th>Aturan</th>
</tr>
</thead>
<tbody>
<?php
foreach ($alokasi as $r):
?>
<tr>
<td>
<b>
<?= e($r['nama_fragmen']) ?>
</b>
</td>
<td>
<?= e($r['jenis_fragmen']) ?>
</td>
<td>
<?= e($r['nama_site']) ?>
</td>
<td>
<?= e($r['tabel_asal']) ?>
</td>
<td>
<?= e($r['aturan_alokasi']) ?>
</td>
</tr>
<?php
endforeach;
?>
</tbody>
</table>
</div>
</section>
<section class="grid cols-2">
<div class="card">
<h3>Audit Trigger</h3>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>Aksi</th>
<th>Status Lama</th>
<th>Status Baru</th>
<th>Waktu</th>
</tr>
</thead>
<tbody>
<?php
foreach ($audit as $r):
?>
<tr>
<td>
<?= e($r['aksi']) ?>
</td>
<td>
<?= e($r['status_lama'] ?? '-') ?>
</td>
<td>
<?= e($r['status_baru'] ?? '-') ?>
</td>
<td>
<?= e($r['waktu_aksi']) ?>
</td>
</tr>
<?php
endforeach;
?>
</tbody>
</table>
</div>
</div>
<div class="card">
<h3>Log Replikasi</h3>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>Node</th>
<th>Tabel</th>
<th>Operasi</th>
<th>PK</th>
</tr>
</thead>
<tbody>
<?php
foreach ($repl as $r):
?>
<tr>
<td>
<?= e($r['node_asal']) ?>
</td>
<td>
<?= e($r['tabel_target']) ?>
</td>
<td>
<?= e($r['operasi']) ?>
</td>
<td>
<?= e($r['primary_key_value']) ?>
</td>
</tr>
<?php
endforeach;
?>
</tbody>
</table>
</div>
</div>
</section>
