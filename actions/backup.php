<?php
require_once __DIR__ . '/../config/database.php';
requireRole('admin');

$date = date('Y-m-d_H-i-s');
$backupDir = __DIR__ . '/../storage/backups';

if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

$file = 'simak_terpadu_backup_' . $date . '.sql';
$path = $backupDir . DIRECTORY_SEPARATOR . $file;
$dumpPaths = [
    'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe',
    'C:\\xampp\\mysql\\bin\\mysqldump.exe',
    'mysqldump'
];

$selectedDump = null;

foreach ($dumpPaths as $dumpPath) {
    if ($dumpPath === 'mysqldump' || file_exists($dumpPath)) {
        $selectedDump = $dumpPath;
        break;
    }
}

$command = '"' . $selectedDump . '" --host=' . escapeshellarg(DB_HOST) . ' --port=' . escapeshellarg(DB_PORT) . ' --user=' . escapeshellarg(DB_USER) . ' ' . (DB_PASS !== '' ? '--password=' . escapeshellarg(DB_PASS) . ' ' : '') . escapeshellarg(DB_NAME) . ' --result-file=' . escapeshellarg($path) . ' 2>&1';
$output = [];
$returnCode = 0;
exec($command, $output, $returnCode);

try {
    if ($returnCode === 0 && file_exists($path) && filesize($path) > 0) {
        db()->prepare('INSERT INTO backup_log (nama_file, lokasi_file, metode, status, keterangan) VALUES (?, ?, ?, ?, ?)')->execute([$file, $path, 'WEB', 'BERHASIL', 'Backup database berhasil dibuat dari halaman laporan admin.']);
        flash('success', 'Backup database berhasil dibuat: ' . $file);
    } else {
        $message = trim(implode(' ', $output));
        if ($message === '') {
            $message = 'mysqldump tidak berhasil dijalankan. Cek path mysqldump dan nama database.';
        }
        db()->prepare('INSERT INTO backup_log (nama_file, lokasi_file, metode, status, keterangan) VALUES (?, ?, ?, ?, ?)')->execute([$file, $path, 'WEB', 'GAGAL', $message]);
        flash('error', 'Backup gagal. Cek konfigurasi mysqldump atau jalankan scripts/backup/mysqlbackup.bat.');
    }
} catch (Throwable $e) {
    flash('error', 'Backup gagal dicatat: ' . $e->getMessage());
}

redirectAction('laporan');
