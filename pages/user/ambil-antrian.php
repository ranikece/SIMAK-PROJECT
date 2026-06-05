<?php
$layanan = queryAll('SELECT id_layanan, kode_layanan, nama_layanan, kapasitas_harian FROM layanan WHERE aktif = 1 ORDER BY nama_layanan');
?>
<div class="page-head">
<div>
<h2>Ambil Antrian</h2>
<p>Isi data pasien dan pilih layanan yang dituju. Sistem akan membuat nomor antrian otomatis sesuai poli.</p>
</div>
</div>
<section class="card">
<form method="post" action="actions/ambil_antrian.php" class="form-grid">
<div class="form-row">
<label>NIK</label>
<input name="nik" maxlength="16" required placeholder="1871xxxxxxxxxxxx">
</div>
<div class="form-row">
<label>Nama Pasien</label>
<input name="nama_pasien" required placeholder="Nama lengkap">
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
<input name="no_hp" required placeholder="08xxxxxxxxxx">
</div>
<div class="form-row">
<label>Wilayah</label>
<input name="wilayah" required placeholder="Rajabasa / Kedaton / Tanjung Karang">
</div>
<div class="form-row">
<label>Layanan</label>
<select name="id_layanan" required>
<?php
foreach ($layanan as $l):
?>
<option value="
<?= e($l['id_layanan']) ?>
">
<?= e($l['kode_layanan'] . ' - ' . $l['nama_layanan']) ?>
| kuota
<?= e($l['kapasitas_harian']) ?>
</option>
<?php
endforeach;
?>
</select>
</div>
<div class="form-row">
<label>Prioritas</label>
<select name="prioritas">
<option value="NORMAL">Normal</option>
<option value="DARURAT">Darurat</option>
</select>
</div>
<div class="form-row full">
<label>Keluhan</label>
<textarea name="keluhan" placeholder="Keluhan singkat pasien">
</textarea>
</div>
<div class="form-row full">
<label>Alamat</label>
<textarea name="alamat" required placeholder="Alamat lengkap pasien">
</textarea>
</div>
<div class="form-row full">
<button type="submit">Buat Nomor Antrian</button>
</div>
</form>
</section>
