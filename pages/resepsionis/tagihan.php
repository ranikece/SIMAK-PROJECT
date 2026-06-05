<?php
$antrian = queryAll('SELECT a.id_antrian, a.no_antrian, p.nama_pasien, l.nama_layanan FROM antrian a JOIN pasien p ON a.id_pasien=p.id_pasien JOIN layanan l ON a.id_layanan=l.id_layanan LEFT JOIN tagihan t ON a.id_antrian=t.id_antrian WHERE a.status="SELESAI" AND t.id_tagihan IS NULL ORDER BY a.waktu_daftar DESC');
$rows = queryAll('SELECT * FROM v_tagihan_lengkap ORDER BY id_tagihan DESC LIMIT 80');
?>
<div class="page-head">
<div>
<h2>Tagihan</h2>
<p>Buat tagihan dari antrian yang sudah selesai dan proses pembayaran pasien.</p>
</div>
</div>
<section class="card">
<h3>Buat Tagihan</h3>
<form method="post" action="actions/save_tagihan.php" class="form-grid">
<input type="hidden" name="mode" value="new">
<div class="form-row full">
<label>Antrian Selesai</label>
<select name="id_antrian" required>
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
<label>Biaya Konsultasi</label>
<input type="number" name="biaya_konsultasi" value="25000">
</div>
<div class="form-row">
<label>Biaya Tindakan</label>
<input type="number" name="biaya_tindakan" value="0">
</div>
<div class="form-row">
<label>Biaya Obat</label>
<input type="number" name="biaya_obat" value="0">
</div>
<div class="form-row">
<label>Diskon</label>
<input type="number" name="diskon" value="0">
</div>
<div class="form-row full">
<label>Catatan</label>
<textarea name="catatan">
</textarea>
</div>
<div class="form-row full">
<button type="submit">Buat Tagihan</button>
</div>
</form>
</section>
<section class="card">
<h3>Daftar Tagihan</h3>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>No</th>
<th>Pasien</th>
<th>Layanan</th>
<th>Total</th>
<th>Status</th>
<th>Aksi Bayar</th>
</tr>
</thead>
<tbody>
<?php
foreach ($rows as $r):
?>
<tr>
<td>
<b>
<?= e($r['no_antrian'] ?? '-') ?>
</b>
</td>
<td>
<?= e($r['nama_pasien']) ?>
<br>
<span class="small note">
<?= e($r['no_rekam_medis']) ?>
</span>
</td>
<td>
<?= e($r['nama_layanan'] ?? '-') ?>
</td>
<td>
<?= rupiah($r['total_tagihan']) ?>
</td>
<td>
<span class="badge
<?= $r['status_bayar']==='lunas'?'success':'warning' ?>
">
<?= e($r['status_bayar']) ?>
</span>
</td>
<td>
<?php
if ($r['status_bayar'] !== 'lunas'):
?>
<form method="post" action="actions/save_tagihan.php" class="actions">
<input type="hidden" name="mode" value="bayar">
<input type="hidden" name="id_tagihan" value="
<?= e($r['id_tagihan']) ?>
">
<select name="metode_pembayaran">
<option>Tunai</option>
<option>QRIS</option>
<option>Transfer</option>
</select>
<input type="number" name="jumlah_bayar" placeholder="Jumlah bayar" required>
<button type="submit" class="light">Bayar</button>
</form>
<?php
else:
?>
<?= e($r['metode_pembayaran'] ?? '-') ?>
<?php
endif;
?>
</td>
</tr>
<?php
endforeach;
?>
<?php
if (!$rows):
?>
<tr>
<td colspan="6">Belum ada tagihan.</td>
</tr>
<?php
endif;
?>
</tbody>
</table>
</div>
</section>
