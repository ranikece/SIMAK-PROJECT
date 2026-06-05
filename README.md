# 🏥 SIMAK Terpadu (Proyek UAP)
Kelompok 6

Putri Maharani - 2417051006

Reggy Desvita Kamal - 2417051016

Rama Praditha Ryananda - 2417051039

Proyek ini merupakan sistem informasi manajemen klinik berbasis web yang dibangun menggunakan PHP dan MySQL. Sistem dirancang untuk membantu proses pelayanan pasien, pengelolaan antrian, rekam medis, serta administrasi klinik secara terintegrasi. Selain mengimplementasikan fitur utama sistem informasi, proyek ini juga menerapkan beberapa konsep basis data lanjutan yang menjadi ketentuan UAP, yaitu Trigger, Fragmentasi Database, Backup Database, dan Task Scheduler. Implementasi tersebut digunakan untuk menjaga konsistensi data, mendukung pengelolaan data terdistribusi, meningkatkan keamanan data melalui proses pencadangan, serta mengotomatisasi proses tertentu dalam sistem. Sistem menyediakan tiga role utama yaitu Admin, Resepsionis, dan Pasien. Setiap role memiliki hak akses yang berbeda sesuai kebutuhan operasional klinik sehingga proses pelayanan dapat berjalan lebih efektif dan terorganisir.

<img width="1919" height="1199" alt="image" src="https://github.com/user-attachments/assets/e4da4a41-8463-4d4c-8bbd-28b9a63d9d17" />

# 📌 Detail Konsep
Pada SIMAK (Sistem Informasi Manajemen Klinik Terpadu), sebagian besar proses pengolahan data tidak hanya dilakukan pada sisi aplikasi, tetapi juga diimplementasikan langsung pada tingkat database. Pendekatan ini digunakan agar setiap proses yang berkaitan dengan data pasien, dokter, antrian, rekam medis, dan layanan klinik dapat berjalan secara lebih konsisten, terkontrol, dan efisien. Dengan memanfaatkan Function, Trigger, View, dan Transaction, sistem mampu menjalankan berbagai proses secara otomatis, seperti perhitungan data, pencatatan aktivitas, validasi informasi, hingga penyajian laporan. Selain itu, penerapan konsep tersebut juga membantu menjaga integritas data ketika sistem digunakan oleh banyak pengguna secara bersamaan. Implementasi fitur-fitur database ini menjadi bagian penting dalam mendukung keandalan sistem serta memenuhi kebutuhan pengelolaan data yang lebih terstruktur dan mudah dipelihara.

<img width="826" height="442" alt="image" src="https://github.com/user-attachments/assets/76aad774-8d1f-461b-b8f7-8f023a7dc22f" />

<img width="898" height="568" alt="image" src="https://github.com/user-attachments/assets/b5ea2f95-adc6-4ee7-9136-1cc28b47531d" />

Beberapa Function, Transaction, View dan Trigger yang Digunakan

### 1. Function `hitung_usia(p_tanggal_lahir)`

Digunakan untuk menghitung usia pasien berdasarkan tanggal lahir yang tersimpan pada database.

Function ini mengembalikan nilai berupa usia pasien dalam satuan tahun, sehingga data usia dapat ditampilkan secara otomatis tanpa perlu dihitung secara manual pada aplikasi.

```sql
SELECT hitung_usia(tanggal_lahir) AS usia
FROM pasien;
```

Contoh hasil:

```text
21 Tahun
35 Tahun
47 Tahun
```

---

### 2. Function `fn_hitung_umur(p_tanggal_lahir)`

Digunakan untuk menghitung umur pasien berdasarkan tanggal lahir.

Function ini membantu sistem menampilkan informasi umur pasien dengan lebih cepat dan konsisten.

```sql
SELECT fn_hitung_umur(tanggal_lahir) AS umur
FROM pasien;
```

Contoh hasil:

```text
21
35
47
```

---

### 3. Function `fn_estimasi_tunggu(p_urutan)`

Digunakan untuk menghitung estimasi waktu tunggu pasien berdasarkan nomor urutan antrian.

Function ini mengembalikan nilai berupa perkiraan waktu pelayanan sehingga pasien dapat mengetahui estimasi waktu sebelum dipanggil.

```sql
SELECT fn_estimasi_tunggu(5);
```

Contoh hasil:

```text
37 Menit
```

---

### 4. Function `fn_label_status(p_status)`

Digunakan untuk mengubah kode status antrian menjadi informasi yang lebih mudah dipahami oleh pengguna.

Function ini membantu menampilkan status antrian pada halaman monitoring agar lebih jelas.

```sql
SELECT fn_label_status('MENUNGGU');
```

Contoh hasil:

```text
MENUNGGU
DIPANGGIL
SELESAI
BATAL
```

---

### 5. Function `cek_stok_obat(p_id_obat)`

Digunakan untuk memeriksa kondisi stok obat yang tersedia pada klinik.

Function ini mengembalikan status stok seperti tersedia, menipis, atau habis sehingga memudahkan proses pengelolaan persediaan obat.

```sql
SELECT cek_stok_obat(1);
```

Contoh hasil:

```text
Tersedia
Menipis
Habis
```

---

### 6. Transaction pada Penyimpanan Rekam Medis

Pada proses penyimpanan rekam medis, sistem menggunakan transaction untuk menjaga konsistensi data.

Transaction digunakan agar proses penyimpanan data berjalan secara utuh. Jika terjadi kesalahan, maka proses akan dibatalkan menggunakan rollback sehingga data yang tidak lengkap tidak masuk ke database.

```php
$pdo->beginTransaction();

$stmt = $pdo->prepare(
    'INSERT INTO rekam_medis(
        id_antrian,
        id_pasien,
        id_dokter,
        tanggal_periksa,
        anamnesis,
        pemeriksaan_fisik,
        diagnosis,
        tindakan,
        resep_obat,
        catatan
    )
    VALUES(?,?,?,?,?,?,?,?,?,?)'
);

$stmt->execute([
    $_POST['id_antrian'],
    $_POST['id_pasien'],
    $_POST['id_dokter'],
    $_POST['tanggal_periksa'],
    $_POST['anamnesis'],
    $_POST['pemeriksaan_fisik'],
    $_POST['diagnosis'],
    $_POST['tindakan'],
    $_POST['resep_obat'],
    $_POST['catatan']
]);

$pdo->commit();
```

Apabila terjadi kesalahan, sistem akan menjalankan:

```php
$pdo->rollBack();
```

---

### 7. Trigger `trg_pasien_before_insert`

Trigger ini dijalankan sebelum data pasien disimpan ke database.

Tujuan:

- Menyiapkan data pasien sebelum proses insert dilakukan.
- Membantu menjaga konsistensi data pasien.
- Memastikan data yang masuk sudah sesuai kebutuhan sistem.

---

### 8. Trigger `trg_pasien_after_insert`

Trigger ini dijalankan setelah data pasien berhasil ditambahkan.

Tujuan:

- Mencatat aktivitas penambahan pasien.
- Membuat histori data pasien.
- Mendukung proses audit data pada sistem.

---

### 9. Trigger `trg_antrian_before_insert`

Trigger ini dijalankan sebelum data antrian disimpan.

Tujuan:

- Membuat nomor antrian secara otomatis.
- Menentukan urutan antrian pasien.
- Mencegah terjadinya duplikasi nomor antrian.

---

### 10. Trigger `trg_antrian_after_insert`

Trigger ini dijalankan setelah antrian berhasil dibuat.

Tujuan:

- Mencatat aktivitas pembuatan antrian.
- Menyimpan histori pelayanan pasien.
- Mendukung monitoring aktivitas sistem.

---

### 11. Trigger `trg_antrian_after_update`

Trigger ini dijalankan ketika status antrian berubah.

Tujuan:

- Menyimpan riwayat perubahan status.
- Mencatat proses pelayanan pasien.
- Mendukung proses audit aktivitas.

Contoh perubahan status:

```text
MENUNGGU = DIPANGGIL
DIPANGGIL = SELESAI
```

---

### 12. Trigger `trg_obat_after_insert`

Trigger ini dijalankan setelah data obat berhasil ditambahkan.

Tujuan:

- Menyimpan histori stok obat.
- Mendukung monitoring persediaan obat.

---

### 13. Trigger `trg_rekam_medis_after_insert`

Trigger ini dijalankan setelah rekam medis berhasil disimpan.

Tujuan:

- Menyimpan histori pelayanan pasien.
- Mencatat aktivitas rekam medis.
- Mendukung proses audit data klinik.

---

### 14. View `v_antrian_aktif`

View ini digunakan untuk menampilkan data antrian pasien yang masih aktif.

```sql
SELECT *
FROM v_antrian_aktif;
```

Contoh hasil:

```text
No Antrian   Pasien           Layanan          Status
A001         Reggy            Umum             Menunggu
A002         Rani             Gigi             Dipanggil
```

---

### 15. View `v_laporan_harian`

View ini digunakan untuk menampilkan ringkasan aktivitas pelayanan klinik dalam satu hari.

```sql
SELECT *
FROM v_laporan_harian;
```

Contoh hasil:

```text
Tanggal       Total Pasien    Total Rekam Medis
2026-06-05    35              35
```

---

### 16. View `v_rekam_medis_lengkap`

View ini digunakan untuk menampilkan informasi rekam medis secara lengkap dengan menggabungkan data pasien, dokter, dan hasil pemeriksaan.

```sql
SELECT *
FROM v_rekam_medis_lengkap;
```

Contoh hasil:

```text
Pasien         Dokter          Diagnosis
Reggy          dr. Rama        Demam
Rani           dr. Rani        Flu
```

---

### 17. View `v_tagihan_lengkap`

View ini digunakan untuk menampilkan informasi tagihan pasien secara lengkap.

```sql
SELECT *
FROM v_tagihan_lengkap;
```

Contoh hasil:

```text
Pasien         Total Tagihan
Reggy Desvita   Rp 150.000
Putri Maharani  Rp 200.000
```

View digunakan untuk mempermudah proses penyajian laporan tanpa perlu menuliskan query JOIN yang panjang secara berulang.

tuk mempermudah proses pembuatan laporan tanpa perlu menuliskan query yang kompleks secara berulang.

### 18. Implementasi Backup Otomatis

Backup otomatis digunakan untuk membuat salinan database `simak_terpadu` ke dalam file `.sql`. Pada project SIMAK, proses backup disimpan ke folder `storage/backups` agar data klinik tetap memiliki cadangan apabila terjadi error atau kehilangan data.

Backup dapat dijalankan oleh admin melalui fitur backup pada sistem. Ketika proses backup dijalankan, sistem akan membuat file backup baru dengan nama berdasarkan tanggal dan waktu.

```php
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

$command = '"' . $selectedDump . '" --host=' . escapeshellarg(DB_HOST) .
    ' --port=' . escapeshellarg(DB_PORT) .
    ' --user=' . escapeshellarg(DB_USER) . ' ' .
    (DB_PASS !== '' ? '--password=' . escapeshellarg(DB_PASS) . ' ' : '') .
    escapeshellarg(DB_NAME) .
    ' --result-file=' . escapeshellarg($path) . ' 2>&1';

$output = [];
$returnCode = 0;

exec($command, $output, $returnCode);

try {
    if ($returnCode === 0 && file_exists($path) && filesize($path) > 0) {
        db()->prepare('
            INSERT INTO backup_log 
                (nama_file, lokasi_file, metode, status, keterangan) 
            VALUES 
                (?, ?, ?, ?, ?)
        ')->execute([
            $file,
            $path,
            'WEB',
            'BERHASIL',
            'Backup database berhasil dibuat dari halaman admin.'
        ]);

        flash('success', 'Backup database berhasil dibuat: ' . $file);
    } else {
        db()->prepare('
            INSERT INTO backup_log 
                (nama_file, lokasi_file, metode, status, keterangan) 
            VALUES 
                (?, ?, ?, ?, ?)
        ')->execute([
            $file,
            $path,
            'WEB',
            'GAGAL',
            trim(implode(' ', $output))
        ]);

        flash('error', 'Backup database gagal dibuat.');
    }
} catch (Throwable $e) {
    flash('error', 'Backup gagal dicatat: ' . $e->getMessage());
}

redirectAction('laporan');
```

Kode tersebut menggunakan `mysqldump` untuk mengekspor database `simak_terpadu`. File hasil backup akan disimpan di folder `storage/backups` dengan format nama seperti berikut:

```text
simak_terpadu_backup_2026-06-05_14-53-26.sql
```

Selain membuat file `.sql`, sistem juga mencatat hasil backup ke tabel `backup_log`. Jika backup berhasil, status yang disimpan adalah `BERHASIL`. Jika backup gagal, status yang disimpan adalah `GAGAL`.

<img width="1919" height="1132" alt="Screenshot 2026-06-05 225901" src="https://github.com/user-attachments/assets/35bbe485-1b8b-4203-86d2-ac3b6643eee4" />
<img width="1919" height="1072" alt="image" src="https://github.com/user-attachments/assets/d5c0afd5-5cbb-4677-8ef2-09ff5cd7c640" />
<img width="1117" height="471" alt="image" src="https://github.com/user-attachments/assets/6959c636-3939-41f3-a18e-b0ced4c8e34c" />




---

### 19. Implementasi Task Scheduler

Task Scheduler digunakan agar backup database dapat berjalan otomatis tanpa harus dilakukan manual. Pada project SIMAK, Task Scheduler diarahkan untuk menjalankan file batch `mysqlbackup.bat`.

File batch ini menjalankan perintah `mysqldump`, lalu menyimpan hasil backup ke folder `storage/backups`.

```bat
@echo off
set DB_NAME=simak_terpadu
set DB_USER=root
set DB_PASS=
set BACKUP_DIR=%~dp0..\..\storage\backups

if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"

set FILE_NAME=%DB_NAME%_%DATE:~-4%%DATE:~3,2%%DATE:~0,2%_%TIME:~0,2%%TIME:~3,2%%TIME:~6,2%.sql
set FILE_NAME=%FILE_NAME: =0%

mysqldump -u %DB_USER% %DB_NAME% > "%BACKUP_DIR%\%FILE_NAME%"

echo Backup selesai: %BACKUP_DIR%\%FILE_NAME%
pause
```

Script tersebut menentukan nama database, user database, dan folder penyimpanan backup. Setelah itu, `mysqldump` dijalankan untuk membuat file backup database dalam format `.sql`.

Agar script berjalan otomatis, file `mysqlbackup.bat` dimasukkan ke Windows Task Scheduler. Pada bagian trigger, jadwal dapat diatur harian. Pada bagian action, program yang dijalankan adalah file batch backup tersebut.

Contoh konfigurasi Task Scheduler:

```bat
@echo off

schtasks /Create ^
 /TN "SIMAK_Backup_Database_Harian" ^
 /TR "C:\laragon\www\SIMAK-PROJECT\scripts\backup\mysqlbackup.bat" ^
 /SC DAILY ^
 /ST 07:00 ^
 /F

echo Task Scheduler untuk backup database SIMAK berhasil dibuat.
pause
```

Keterangan:

```text
/TN  = nama task scheduler
/TR  = lokasi file mysqlbackup.bat yang dijalankan
/SC  = jadwal task, dalam contoh ini DAILY
/ST  = waktu task dijalankan
/F   = menimpa task lama jika nama task sudah ada
```

Dengan implementasi ini, backup database SIMAK dapat berjalan otomatis setiap hari. Hasil backup tetap tersimpan di folder `storage/backups`, sehingga sistem memiliki cadangan data secara rutin.

<img width="1919" height="1132" alt="image" src="https://github.com/user-attachments/assets/0c1d7132-b564-4f6f-87cf-2bd9284f8cf8" />


