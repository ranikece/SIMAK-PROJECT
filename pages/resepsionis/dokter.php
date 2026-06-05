<?php
$layanan = queryAll('SELECT id_layanan, nama_layanan FROM layanan ORDER BY nama_layanan');
$rows = queryAll('SELECT d.*, l.nama_layanan, (SELECT COUNT(*) FROM antrian a WHERE a.id_dokter=d.id_dokter AND a.tanggal=CURDATE()) AS antrian_hari_ini FROM dokter d LEFT JOIN layanan l ON d.id_layanan=l.id_layanan ORDER BY d.id_dokter DESC');
?>
<div class="page-head">
<div>
<h2>Data Dokter</h2>
<p>Kelola dokter dan hubungkan ke layanan klinik.</p>
</div>
</div>
<section class="card">
<h3>Tambah Dokter</h3>
<form method="post" action="actions/save_dokter.php" class="form-grid">
<div class="form-row">
<label>Kode Dokter</label>
<input name="kode_dokter" placeholder="D-005">
</div>
<div class="form-row">
<label>Nama Dokter</label>
<input name="nama_dokter" required>
</div>
<div class="form-row">
<label>Spesialisasi</label>
<input name="spesialisasi" required>
</div>
<div class="form-row">
<label>Layanan</label>
<select name="id_layanan">
<option value="">-</option>
<?php
foreach ($layanan as $l):
?>
<option value="
<?= e($l['id_layanan']) ?>
">
<?= e($l['nama_layanan']) ?>
</option>
<?php
endforeach;
?>
</select>
</div>
<div class="form-row">
<label>No Telepon</label>
<input name="no_telepon">
</div>
<div class="form-row">
<label>Email</label>
<input type="email" name="email">
</div>
<div class="form-row">
<label>Status</label>
<select name="status">
<option value="aktif">Aktif</option>
<option value="nonaktif">Nonaktif</option>
</select>
</div>
<div class="form-row full">
<label>Jadwal</label>
<textarea name="jadwal">
</textarea>
</div>
<div class="form-row full">
<button type="submit">Simpan Dokter</button>
</div>
</form>
</section>
<section class="card">
<h3>Daftar Dokter</h3>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>Kode</th>
<th>Nama</th>
<th>Spesialisasi</th>
<th>Layanan</th>
<th>Status</th>
<th>Antrian Hari Ini</th>
</tr>
</thead>
<tbody>
<?php
foreach ($rows as $r):
?>
<tr>
<td>
<b>
<?= e($r['kode_dokter']) ?>
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
<span class="badge
<?= $r['status']==='aktif'?'success':'danger' ?>
">
<?= e($r['status']) ?>
</span>
</td>
<td>
<?= e($r['antrian_hari_ini']) ?>
</td>
</tr>
<?php
endforeach;
?>
</tbody>
</table>
</div>
</section>
