<?php
$antrian = queryAll('SELECT a.id_antrian, a.no_antrian, p.nama_pasien, l.nama_layanan, d.nama_dokter FROM antrian a JOIN pasien p ON a.id_pasien=p.id_pasien JOIN layanan l ON a.id_layanan=l.id_layanan LEFT JOIN dokter d ON a.id_dokter=d.id_dokter WHERE a.status IN ("DIPANGGIL","SELESAI") ORDER BY a.waktu_daftar DESC LIMIT 50');
$pasien = queryAll('SELECT id_pasien, no_rekam_medis, nama_pasien FROM pasien ORDER BY nama_pasien');
$dokter = queryAll('SELECT id_dokter, nama_dokter FROM dokter WHERE status="aktif" ORDER BY nama_dokter');
$rows = queryAll('SELECT * FROM v_rekam_medis_lengkap ORDER BY id_rekam_medis DESC LIMIT 60');
?>
<div class="page-head">
<div>
<h2>Rekam Medis</h2>
<p>Catatan pemeriksaan pasien. Jika memilih nomor antrian, data pasien dan dokter akan mengikuti antrian tersebut.</p>
</div>
</div>
<section class="card">
<h3>Tambah Rekam Medis</h3>
<p class="required-info">Kolom bertanda merah wajib diisi.</p>
<form method="post" action="actions/save_rekam_medis.php" class="form-grid">
<div class="form-row full">
<label>Ambil dari Antrian</label>
<select name="id_antrian">
<option value="">Tanpa antrian</option>
<?php
foreach ($antrian as $a):
?>
<option value="
<?= e($a['id_antrian']) ?>
">
<?= e($a['no_antrian'].' - '.$a['nama_pasien'].' - '.$a['nama_layanan']) ?>
</option>
<?php
endforeach;
?>
</select>
</div>
<div class="form-row">
<label>Pasien jika tanpa antrian</label>
<select name="id_pasien">
<option value="0">-</option>
<?php
foreach ($pasien as $p):
?>
<option value="
<?= e($p['id_pasien']) ?>
">
<?= e($p['no_rekam_medis'].' - '.$p['nama_pasien']) ?>
</option>
<?php
endforeach;
?>
</select>
</div>
<div class="form-row">
<label>Dokter jika tanpa antrian</label>
<select name="id_dokter">
<option value="0">-</option>
<?php
foreach ($dokter as $d):
?>
<option value="
<?= e($d['id_dokter']) ?>
">
<?= e($d['nama_dokter']) ?>
</option>
<?php
endforeach;
?>
</select>
</div>
<div class="form-row full">
<label>Anamnesis</label>
<textarea name="anamnesis">
</textarea>
</div>
<div class="form-row full">
<label>Pemeriksaan Fisik</label>
<textarea name="pemeriksaan_fisik">
</textarea>
</div>
<div class="form-row">
<label>Diagnosis <span class="required-mark">*</span>
</label>
<input name="diagnosis" required>
<small class="required-text">Diagnosis wajib diisi.</small>
</div>
<div class="form-row">
<label>Tindakan <span class="required-mark">*</span>
</label>
<input name="tindakan" required>
<small class="required-text">Tindakan wajib diisi.</small>
</div>
<div class="form-row full">
<label>Resep Obat</label>
<textarea name="resep_obat">
</textarea>
</div>
<div class="form-row">
<label>Tekanan Darah</label>
<input name="tekanan_darah" placeholder="120/80">
</div>
<div class="form-row">
<label>Suhu</label>
<input type="number" step="0.1" name="suhu">
</div>
<div class="form-row">
<label>Nadi</label>
<input type="number" name="nadi">
</div>
<div class="form-row">
<label>Berat Badan</label>
<input type="number" step="0.1" name="berat_badan">
</div>
<div class="form-row">
<label>Tinggi Badan</label>
<input type="number" step="0.1" name="tinggi_badan">
</div>
<div class="form-row full">
<label>Catatan</label>
<textarea name="catatan">
</textarea>
</div>
<div class="form-row full">
<button type="submit">Simpan Rekam Medis</button>
</div>
</form>
</section>
<section class="card">
<h3>Riwayat Rekam Medis</h3>
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
