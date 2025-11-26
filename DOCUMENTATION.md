# 📚 Dokumentasi E-Arsip

## Daftar Isi

1. [Panduan Instalasi](#panduan-instalasi)
2. [Panduan Penggunaan](#panduan-penggunaan)
3. [Fitur Detail](#fitur-detail)
4. [Troubleshooting](#troubleshooting)

---

## 📥 Panduan Instalasi

### **Instalasi di Windows (Laragon/XAMPP)**

1. **Persiapan**
   - Install Laragon atau XAMPP
   - Install Composer
   - Install Node.js & NPM

2. **Clone/Download Proyek**
   ```bash
   cd C:\laragon\www
   git clone [repository-url] e-arsip
   cd e-arsip
   ```

3. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

4. **Setup Environment**
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

5. **Konfigurasi Database**
   
   Buat database baru di phpMyAdmin atau MySQL:
   ```sql
   CREATE DATABASE e_arsip;
   ```
   
   Edit `.env`:
   ```env
   DB_DATABASE=e_arsip
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Jalankan Migration & Seeder**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Build Assets**
   ```bash
   npm run build
   ```

8. **Akses Aplikasi**
   
   Buka browser: `http://localhost/e-arsip/public`

---

## 📖 Panduan Penggunaan

### **Login Pertama Kali**

1. Buka aplikasi di browser
2. Login dengan kredensial default:
   - **Admin:** admin@example.com / password
   - **Pimpinan:** pimpinan@example.com / password
   - **Staff:** staff@example.com / password
3. **PENTING:** Ganti password setelah login pertama!

### **Input Surat Masuk (Staff/Admin)**

1. Klik menu **Surat Masuk**
2. Klik tombol **Tambah Surat Masuk**
3. Isi form:
   - Nomor agenda (auto-generate)
   - Nomor surat
   - Tanggal surat & tanggal terima
   - Pengirim
   - Perihal
   - Prioritas & Sifat
   - Upload file (opsional)
4. Klik **Simpan**

### **Buat Disposisi (Pimpinan/Admin)**

1. Buka detail surat masuk
2. Klik **Buat Disposisi**
3. Pilih user tujuan
4. Isi instruksi
5. Set batas waktu (opsional)
6. Upload file lampiran (opsional)
7. Klik **Simpan**

### **Export Laporan**

1. Klik menu **Laporan**
2. Pilih periode tanggal
3. Klik **Tampilkan Laporan**
4. Pilih jenis export:
   - **PDF** - Untuk laporan cetak
   - **Excel** - Untuk analisis data
5. Klik tombol export yang diinginkan

---

## 🎯 Fitur Detail

### **Dashboard Admin**

Menampilkan:
- Total surat masuk, surat keluar, arsip, dan user
- Statistik per status
- Surat masuk/keluar terbaru
- Quick actions

### **Dashboard Pimpinan**

Menampilkan:
- Total disposisi
- Disposisi pending/proses/selesai
- Disposisi terbaru
- Surat masuk terbaru

### **Dashboard Staff**

Menampilkan:
- Surat masuk/keluar yang dibuat user
- Disposisi yang ditugaskan
- Tugas hari ini
- Quick actions untuk input data

### **Manajemen User (Admin Only)**

- Tambah user baru
- Edit data user
- Ubah role dan status
- Hapus user
- Lihat detail user dengan statistik

### **Log Aktivitas (Admin Only)**

- Lihat semua aktivitas sistem
- Filter berdasarkan modul, user, tanggal
- Detail log dengan IP address
- Hapus log lama

### **Pengaturan (Admin Only)**

- Nama aplikasi & instansi
- Alamat & kontak
- Pengaturan file upload
- Pengaturan notifikasi
- Retensi log

---

## 🔧 Troubleshooting

### **Error: Class not found**

```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### **Error: 500 Internal Server Error**

1. Cek file `.env` sudah ada
2. Cek permission folder `storage` dan `bootstrap/cache`
3. Jalankan:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

### **File upload tidak berfungsi**

1. Pastikan folder `public/uploads` ada dan writable
2. Cek `php.ini`:
   ```ini
   upload_max_filesize = 10M
   post_max_size = 10M
   ```

### **Export PDF/Excel error**

1. Pastikan package sudah terinstall:
   ```bash
   composer require barryvdh/laravel-dompdf
   composer require maatwebsite/excel
   ```
2. Clear cache:
   ```bash
   php artisan config:clear
   ```

### **Database connection error**

1. Cek konfigurasi di `.env`
2. Pastikan database sudah dibuat
3. Cek MySQL service berjalan
4. Test koneksi:
   ```bash
   php artisan migrate:status
   ```

---

## 📞 Bantuan

Jika mengalami masalah, silakan:
1. Cek log di `storage/logs/laravel.log`
2. Buat issue di repository
3. Hubungi developer

---

**Versi Dokumentasi:** 1.0  
**Terakhir Update:** 2024

