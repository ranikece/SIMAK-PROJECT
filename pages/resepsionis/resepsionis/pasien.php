<?php
$q = trim($_GET['q'] ?? '');
$params = [];
$where = '';
if ($q !== '') {
    $where = 'WHERE nama_pasien LIKE ? OR nik LIKE ? OR no_rekam_medis LIKE ?';
    $params = ["%$q%", "%$q%", "%$q%"];
}
$rows = queryAll("SELECT *, hitung_usia(tanggal_lahir) AS usia FROM pasien $where ORDER BY id_pasien DESC LIMIT 80", $params);
?>
<div class="page-head">
<div>
<h2>Data Pasien</h2>
<p>Data pasien dipakai bersama oleh bagian pasien, resepsionis, dan admin dalam satu database.</p>
</div>
</div>
<section class="card">
<h3>Tambah Pasien</h3>
<form method="post" action="actions/save_pasien.php" class="form-grid">
<div class="form-row">
<label>NIK</label>
<input name="nik" maxlength="16">
</div>
<div class="form-row">
<label>No Rekam Medis</label>
<input name="no_rekam_medis" placeholder="kosongkan untuk otomatis">
</div>
<div class="form-row">
<label>Nama</label>
<input name="nama_pasien" required>
</div>
<div class="form-row">
<label>Jenis Kelamin</label>
<select name="jenis_kelamin">
<option value="P">Perempuan</option>
<option value="L">Laki-laki</option>
</select>
</div>
<div class="form-row">
<label>Tanggal Lahir</label>
<input type="date" name="tanggal_lahir" required>
</div>
<div class="form-row">
<label>No HP</label>
<input name="no_hp">
</div>
<div class="form-row">
<label>Wilayah</label>
<input name="wilayah">
</div>
<div class="form-row">
<label>Golongan Darah</label>
<select name="golongan_darah">
<option value="">Pilih golongan darah</option>
<option value="A">A</option>
<option value="B">B</option>
<option value="AB">AB</option>
<option value="O">O</option>
<option value="A+">A+</option>
<option value="A-">A-</option>
<option value="B+">B+</option>
<option value="B-">B-</option>
<option value="AB+">AB+</option>
<option value="AB-">AB-</option>
<option value="O+">O+</option>
<option value="O-">O-</option>
</select>
</div>
<div class="form-row full">
<label>Alamat</label>
<textarea name="alamat" required>
</textarea>
</div>
<div class="form-row full">
<label>Alergi</label>
<textarea name="alergi">
</textarea>
</div>
<div class="form-row full">
<button type="submit">Simpan Pasien</button>
</div>
</form>
</section>
<section class="card">
<div class="page-head">
<div>
<h3>Daftar Pasien</h3>
</div>
<form method="get" class="actions">
<input type="hidden" name="role" value="resepsionis">
<input type="hidden" name="page" value="pasien">
<input name="q" value="
<?= e($q) ?>
" placeholder="Cari pasien">
<button type="submit">Cari</button>
</form>
</div>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>RM</th>
<th>NIK</th>
<th>Nama</th>
<th>Usia</th>
<th>JK</th>
<th>No HP</th>
<th>Alamat</th>
</tr>
</thead>
<tbody>
<?php
foreach ($rows as $r):
?>
<tr>
<td>
<b>
<?= e($r['no_rekam_medis']) ?>
</b>
</td>
<td>
<?= e($r['nik'] ?? '-') ?>
</td>
<td>
<?= e($r['nama_pasien']) ?>
</td>
<td>
<?= e($r['usia']) ?>
th</td>
<td>
<?= e($r['jenis_kelamin']) ?>
</td>
<td>
<?= e($r['no_hp'] ?? $r['no_telepon']) ?>
</td>
<td>
<?= e($r['alamat']) ?>
</td>
</tr>
<?php
endforeach;
?>
<?php
if (!$rows):
?>
<tr>
<td colspan="7">Data tidak ditemukan.</td>
</tr>
<?php
endif;
?>
</tbody>
</table>
</div>
</section>
