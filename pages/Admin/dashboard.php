<?php
$hariMap = [
'Monday' => 'Senin',
'Tuesday' => 'Selasa',
'Wednesday' => 'Rabu',
'Thursday' => 'Kamis',
'Friday' => 'Jumat',
'Saturday' => 'Sabtu',
'Sunday' => 'Minggu',
];
$hariIni = $hariMap[date('l')] ?? date('l');
$stats = [
'pasien' => scalar('SELECT COUNT(*) FROM pasien'),
'dokter' => scalar('SELECT COUNT(*) FROM dokter'),
'jadwal' => scalar('SELECT COUNT(*) FROM dokter WHERE jadwal IS NOT NULL AND jadwal <> ""'),
'antrian_hari_ini' => scalar('SELECT COUNT(*) FROM antrian WHERE tanggal = CURDATE()'),
'rekam_medis' => scalar('SELECT COUNT(*) FROM rekam_medis'),
];
$latestQueues = queryAll('
SELECT
a.no_antrian,
a.status,
p.nama_pasien,
l.nama_layanan,
d.nama_dokter,
d.spesialisasi
FROM antrian a
JOIN pasien p ON p.id_pasien = a.id_pasien
JOIN layanan l ON l.id_layanan = a.id_layanan
LEFT JOIN dokter d ON d.id_dokter = a.id_dokter
ORDER BY a.tanggal DESC, a.waktu_daftar DESC
LIMIT 5
');
$jadwalDokter = queryAll('
SELECT
d.nama_dokter,
d.spesialisasi,
d.jadwal,
l.nama_layanan
FROM dokter d
LEFT JOIN layanan l ON l.id_layanan = d.id_layanan
WHERE d.status = "aktif"
ORDER BY d.id_dokter ASC
LIMIT 5
');
?>
<section class="hero-card admin-hero">
<div>
<span class="eyebrow">Dashboard Klinik</span>
<h1>Selamat datang di SIMAK</h1>
</div>
<div class="hero-badge">
<b>
<?= e(date('d M Y')) ?>
</b>
<span>
<?= e(date('H:i')) ?>
WIB</span>
</div>
</section>
<section class="grid stats-grid admin-stats">
<div class="stat soft-green">
<span class="stat-icon">👥</span>
<p>Total Pasien</p>
<b>
<?= e($stats['pasien']) ?>
</b>
</div>
<div class="stat soft-lime">
<span class="stat-icon">🩺</span>
<p>Total Dokter</p>
<b>
<?= e($stats['dokter']) ?>
</b>
</div>
<div class="stat soft-yellow">
<span class="stat-icon">📅</span>
<p>Jadwal Dokter</p>
<b>
<?= e($stats['jadwal']) ?>
</b>
</div>
<div class="stat soft-cream">
<span class="stat-icon">⏳</span>
<p>Antrian Hari Ini</p>
<b>
<?= e($stats['antrian_hari_ini']) ?>
</b>
</div>
<div class="stat soft-green">
<span class="stat-icon">📋</span>
<p>Rekam Medis</p>
<b>
<?= e($stats['rekam_medis']) ?>
</b>
</div>
</section>
<section class="dashboard-grid">
<div class="card">
<div class="section-title">
<h3>Antrian Terbaru</h3>
<a href="index.php?page=antrian">Lihat semua</a>
</div>
<?php
if (!$latestQueues):
?>
<p class="muted">Belum ada data antrian.</p>
<?php
else:
?>
<div class="list">
<?php
foreach ($latestQueues as $q):
?>
<div class="list-item">
<span class="number">
<?= e($q['no_antrian']) ?>
</span>
<div>
<b>
<?= e($q['nama_pasien']) ?>
</b>
<small>
<?= e($q['nama_layanan']) ?>
<?= $q['nama_dokter'] ? ' - ' . e($q['nama_dokter']) : '' ?>
</small>
</div>
<span class="badge
<?= $q['status'] === 'SELESAI' ? 'success' : ($q['status'] === 'DIPANGGIL' ? 'warning' : 'status') ?>
">
<?= e(ucwords(strtolower(str_replace('_', ' ', $q['status'])))) ?>
</span>
</div>
<?php
endforeach;
?>
</div>
<?php
endif;
?>
</div>
<div class="card">
<div class="section-title">
<h3>Jadwal Dokter</h3>
<a href="index.php?page=jadwal">Kelola</a>
</div>
<?php
if (!$jadwalDokter):
?>
<p class="muted">Belum ada jadwal dokter.</p>
<?php
else:
?>
<div class="list">
<?php
foreach ($jadwalDokter as $d):
?>
<div class="list-item schedule-item">
<span class="date-chip">
<?= e($hariIni) ?>
</span>
<div>
<b>
<?= e($d['nama_dokter']) ?>
</b>
<small>
<?= e($d['jadwal'] ?: '-') ?>
</small>
</div>
<span class="badge yellow">
<?= e($d['nama_layanan'] ?? $d['spesialisasi']) ?>
</span>
</div>
<?php
endforeach;
?>
</div>
<?php
endif;
?>
</div>
</section>
