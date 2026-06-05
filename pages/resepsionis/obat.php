<?php
$rows = queryAll('SELECT *, cek_stok_obat(id_obat) AS status_stok FROM obat ORDER BY nama_obat');
?>
<div class="page-head">
<div>
<h2>Data Obat</h2>
<p>Kelola stok obat dan restok dengan pencatatan log otomatis.</p>
</div>
</div>
<section class="grid cols-2">
<div class="card">
<h3>Tambah Obat</h3>
<form method="post" action="actions/save_obat.php" class="form-grid">
<input type="hidden" name="mode" value="new">
<div class="form-row">
<label>Kode</label>
<input name="kode_obat" required>
</div>
<div class="form-row">
<label>Nama Obat</label>
<input name="nama_obat" required>
</div>
<div class="form-row">
<label>Jenis</label>
<input name="jenis" required>
</div>
<div class="form-row">
<label>Satuan</label>
<input name="satuan" required>
</div>
<div class="form-row">
<label>Stok</label>
<input type="number" name="stok" required>
</div>
<div class="form-row">
<label>Stok Minimum</label>
<input type="number" name="stok_minimum" value="5" required>
</div>
<div class="form-row full">
<label>Harga</label>
<input type="number" name="harga" required>
</div>
<div class="form-row full">
<button type="submit">Simpan Obat</button>
</div>
</form>
</div>
<div class="card">
<h3>Restok Obat</h3>
<form method="post" action="actions/save_obat.php" class="form-grid">
<input type="hidden" name="mode" value="restok">
<div class="form-row full">
<label>Obat</label>
<select name="id_obat">
<?php
foreach ($rows as $r):
?>
<option value="
<?= e($r['id_obat']) ?>
">
<?= e($r['nama_obat']) ?>
- stok
<?= e($r['stok']) ?>
</option>
<?php
endforeach;
?>
</select>
</div>
<div class="form-row full">
<label>Jumlah Restok</label>
<input type="number" name="jumlah" min="1" required>
</div>
<div class="form-row full">
<button type="submit" class="light">Tambah Stok</button>
</div>
</form>
</div>
</section>
<section class="card">
<h3>Daftar Obat</h3>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>Kode</th>
<th>Nama</th>
<th>Jenis</th>
<th>Stok</th>
<th>Harga</th>
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
<?= e($r['kode_obat']) ?>
</b>
</td>
<td>
<?= e($r['nama_obat']) ?>
</td>
<td>
<?= e($r['jenis']) ?>
/
<?= e($r['satuan']) ?>
</td>
<td>
<?= e($r['stok']) ?>
</td>
<td>
<?= rupiah($r['harga']) ?>
</td>
<td>
<span class="badge
<?= $r['status_stok']==='Aman'?'success':($r['status_stok']==='Menipis'?'warning':'danger') ?>
">
<?= e($r['status_stok']) ?>
</span>
</td>
</tr>
<?php
endforeach;
?>
</tbody>
</table>
</div>
</section>
