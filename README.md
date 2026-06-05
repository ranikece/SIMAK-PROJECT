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

Beberapa function, transaction, view dan trigger yang digunakan:

1. QueueController.php

sp_register_queue(p_patient_id, p_doctor_id, p_queue_date) : Digunakan untuk membuat antrian pasien secara otomatis berdasarkan dokter yang dipilih dan tanggal kunjungan. Procedure ini akan menghasilkan nomor antrian berikutnya sehingga tidak terjadi duplikasi data antrian.

$stmt = $pdo->prepare('CALL sp_register_queue(?,?,?)');

$stmt->execute([
    $_POST['patient_id'],
    $_POST['doctor_id'],
    $_POST['queue_date']
]);

2. Function hitung_usia(p_tanggal_lahir)

Digunakan untuk menghitung usia pasien berdasarkan tanggal lahir yang tersimpan pada database.

Function ini mengembalikan nilai berupa usia pasien dalam satuan tahun sehingga data usia dapat ditampilkan secara otomatis tanpa perlu dihitung kembali pada aplikasi.

SELECT hitung_usia(tanggal_lahir) AS usia
FROM pasien;

3. Function fn_estimasi_tunggu(p_urutan)

Digunakan untuk menghitung estimasi waktu tunggu pasien berdasarkan nomor urutan antrian.

Function ini mengembalikan nilai berupa perkiraan waktu pelayanan sehingga pasien dapat mengetahui estimasi waktu sebelum dipanggil.

SELECT fn_estimasi_tunggu(5);

4. Function fn_label_status(p_status)

Digunakan untuk mengubah kode status antrian menjadi informasi yang lebih mudah dipahami pengguna.

Function ini mengembalikan label status yang akan ditampilkan pada halaman monitoring antrian.

SELECT fn_label_status('MENUNGGU');

Contoh hasil:
MENUNGGU 
DIPANGGIL
SELESAI 

5. Function cek_stok_obat(p_id_obat)

Digunakan untuk memeriksa kondisi stok obat yang tersedia pada klinik.

Function ini mengembalikan status stok seperti tersedia, menipis, atau habis sehingga memudahkan proses pengelolaan persediaan obat.

SELECT cek_stok_obat(1);

6. record_store()

Pada proses penyimpanan rekam medis, sistem menggunakan transaction untuk menjaga konsistensi data.

$pdo->beginTransaction();

$stmt = $pdo->prepare(
    'INSERT INTO medical_records(
        patient_id,
        doctor_id,
        visit_date,
        diagnosis,
        treatment,
        prescription,
        cost
    )
    VALUES(?,?,?,?,?,?,?)'
);

$stmt->execute([
    $_POST['patient_id'],
    $_POST['doctor_id'],
    $_POST['visit_date'],
    $_POST['diagnosis'],
    $_POST['treatment'],
    $_POST['prescription'],
    $_POST['cost']
]);

$pdo->commit();

Apabila terjadi kesalahan saat proses penyimpanan data, maka sistem akan menjalankan rollback sehingga data yang belum lengkap tidak akan tersimpan ke database.

$pdo->rollBack();

7. Trigger Audit Log

Trigger digunakan untuk mencatat aktivitas penting yang terjadi pada sistem secara otomatis.

Tujuan penggunaan trigger:

Mencatat penambahan data pasien.
Mencatat pembuatan antrian pasien.
Mencatat perubahan status antrian.
Mencatat penyimpanan rekam medis.
Menyimpan histori aktivitas sistem untuk kebutuhan audit.

Data hasil trigger disimpan pada tabel:

audit_antrian
log_aktivitas
log_status_antrian
log_stok_obat

Sehingga setiap perubahan data yang terjadi di dalam sistem dapat ditelusuri kembali melalui log aktivitas yang tersimpan pada database.

8. View Laporan 

v_patient_queue_summary : Menampilkan ringkasan data antrian pasien beserta dokter yang melayani menggunakan konsep View dan Join.

SELECT *
FROM v_patient_queue_summary;

v_doctor_income : Menampilkan total kunjungan dan pendapatan dokter berdasarkan data rekam medis yang tersimpan pada sistem.

SELECT *
FROM v_doctor_income;

View digunakan untuk mempermudah proses pembuatan laporan tanpa perlu menuliskan query yang kompleks secara berulang.
