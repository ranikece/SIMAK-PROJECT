<?php
$rows = queryAll('SELECT l.*, s.nama_site, s.wilayah, (SELECT COUNT(*) FROM antrian a WHERE a.id_layanan=l.id_layanan AND a.tanggal=CURDATE()) AS total_hari_ini, (SELECT COUNT(*) FROM antrian a WHERE a.id_layanan=l.id_layanan AND a.tanggal=CURDATE() AND a.status="MENUNGGU") AS menunggu FROM layanan l JOIN sites s ON l.id_site=s.id_site WHERE l.aktif=1 ORDER BY l.nama_layanan');
?>
<div class="page-head">
<div>
<h2>Layanan Klinik</h2>
<p>Lihat poli, lokasi, kuota harian, dan kepadatan antrian.</p>
</div>
</div>
<section class="grid cols-2">
<?php
foreach ($rows as $row):
?>
<div class="card feature-card">
<div class="icon">
<?= e(substr($row['kode_layanan'], 0, 2)) ?>
</div>
<h3>
<?= e($row['nama_layanan']) ?>
</h3>
<p>
<?= e($row['nama_site']) ?>
•
<?= e($row['wilayah']) ?>
</p>
<p>Kuota: <b>
<?= e($row['kapasitas_harian']) ?>
</b> | Hari ini: <b>
<?= e($row['total_hari_ini']) ?>
</b> | Menunggu: <b>
<?= e($row['menunggu']) ?>
</b>
</p>
<a class="btn light" href="index.php?page=ambil-antrian">Ambil Antrian</a>
</div>
<?php
endforeach;
?>
</section>
