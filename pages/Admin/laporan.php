<?php
$backupFiles = [];
$backupDir = __DIR__ . '/../../storage/backups';
if (is_dir($backupDir)) {
    foreach (glob($backupDir . DIRECTORY_SEPARATOR . '*.sql') ?: [] as $backupPath) {
        $backupFiles[] = [
            'nama_file' => basename($backupPath),
            'ukuran' => filesize($backupPath),
            'waktu' => filemtime($backupPath),
        ];
    }
    usort($backupFiles, fn($a, $b) => $b['waktu'] <=> $a['waktu']);
}
?>
<div class="page-head">
<div>
<h2>Backup Database</h2>
<p>Buat salinan database SIMAK dalam format SQL.</p>
</div>
<form method="post" action="actions/backup.php" class="actions">
<button type="submit" class="btn">Buat Backup</button>
</form>
</div>
<section class="card">
<div class="page-head compact-head">
<div>
<h3>Riwayat Backup</h3>
<p>File backup tersimpan otomatis di folder <b>storage/backups</b>.</p>
</div>
</div>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>Nama File</th>
<th>Ukuran</th>
<th>Waktu Dibuat</th>
</tr>
</thead>
<tbody>
<?php foreach ($backupFiles as $backup): ?>
<tr>
<td><?= e($backup['nama_file']) ?></td>
<td><?= e(number_format($backup['ukuran'] / 1024, 2, ',', '.')) ?> KB</td>
<td><?= e(date('d M Y H:i:s', $backup['waktu'])) ?> WIB</td>
</tr>
<?php endforeach; ?>
<?php if (!$backupFiles): ?>
<tr>
<td colspan="3">Belum ada file backup. Klik tombol Buat Backup untuk membuat backup pertama.</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>
</section>
