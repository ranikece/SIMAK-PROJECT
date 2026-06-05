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
## 📌 Beberapa Function, Transaction, View dan Trigger yang Digunakan

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
