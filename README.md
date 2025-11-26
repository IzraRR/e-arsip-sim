# 📁 E-Arsip - Sistem Manajemen Arsip Elektronik

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)

**Sistem manajemen arsip elektronik berbasis web untuk mengelola surat masuk, surat keluar, disposisi, dan arsip dokumen secara digital.**

[Fitur](#-fitur) • [Instalasi](#-instalasi) • [Penggunaan](#-penggunaan) • [Teknologi](#-teknologi)

</div>

---

## 📋 Tentang E-Arsip

**E-Arsip** adalah aplikasi web berbasis Laravel yang dirancang untuk membantu organisasi dalam mengelola arsip elektronik secara efisien dan terstruktur. Sistem ini menyediakan solusi lengkap untuk manajemen surat masuk, surat keluar, disposisi, dan arsip dokumen dengan fitur keamanan berbasis role (Admin, Pimpinan, Staff).

### 🎯 Tujuan

- ✅ Digitalisasi proses manajemen arsip
- ✅ Meningkatkan efisiensi pengelolaan dokumen
- ✅ Memudahkan tracking dan pencarian dokumen
- ✅ Meningkatkan transparansi dan akuntabilitas
- ✅ Mengurangi penggunaan kertas (paperless)

---

## ✨ Fitur

### 🔐 **Manajemen User & Keamanan**
- Sistem autentikasi dengan role-based access control (RBAC)
- 3 level akses: **Admin**, **Pimpinan**, dan **Staff**
- Manajemen user lengkap dengan validasi
- Log aktivitas untuk audit trail
- Keamanan password dengan hashing

### 📨 **Surat Masuk**
- Input dan kelola surat masuk
- Auto-generate nomor agenda
- Upload file surat (PDF, DOC, DOCX, JPG, PNG)
- Filter berdasarkan status, tanggal, dan pencarian
- Tracking status: Pending, Disposisi, Selesai
- Prioritas: Biasa, Penting, Segera
- Sifat: Biasa, Rahasia, Sangat Rahasia

### 📤 **Surat Keluar**
- Buat dan kelola surat keluar
- Workflow: Draft → Approved → Sent
- Upload file surat
- Filter dan pencarian
- Tracking status pengiriman

### 📋 **Disposisi**
- Buat disposisi dari surat masuk
- Assign ke user tertentu
- Tracking status: Pending, Dibaca, Proses, Selesai
- Upload file lampiran
- Batas waktu penyelesaian
- Notifikasi real-time

### 🗄️ **Arsip Dokumen**
- Kategorisasi dokumen
- Upload dan kelola arsip
- Sistem tagging untuk pencarian
- Filter berdasarkan kategori dan tanggal
- Download dokumen

### 📊 **Laporan & Statistik**
- Dashboard statistik real-time
- Export laporan ke **PDF** dan **Excel**
- Laporan per periode (custom date range)
- Statistik per kategori
- Laporan lengkap (gabungan semua data)

### 🔔 **Notifikasi**
- Notifikasi real-time
- Notifikasi untuk disposisi baru
- Notifikasi untuk surat masuk/keluar
- Badge counter notifikasi
- Mark as read/unread

### ⚙️ **Pengaturan Sistem**
- Konfigurasi aplikasi
- Pengaturan instansi
- Pengaturan file upload
- Pengaturan notifikasi
- Retensi log

### 📝 **Log Aktivitas**
- Pencatatan semua aktivitas user
- Filter berdasarkan modul, user, dan tanggal
- Detail log dengan IP address dan user agent
- Hapus log lama otomatis

---

## 🚀 Instalasi

### **Persyaratan Sistem**

- PHP >= 8.1
- Composer
- Node.js & NPM
- Database (MySQL/MariaDB/PostgreSQL)
- Web Server (Apache/Nginx)

### **Langkah Instalasi**

1. **Clone Repository**
   ```bash
   git clone https://github.com/yourusername/e-arsip.git
   cd e-arsip
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi Database**
   
   Edit file `.env` dan sesuaikan konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=e_arsip
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Jalankan Migration & Seeder**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build Assets**
   ```bash
   npm run build
   # atau untuk development:
   npm run dev
   ```

7. **Setup Storage Link**
   ```bash
   php artisan storage:link
   ```

8. **Jalankan Server**
   ```bash
   php artisan serve
   ```

   Aplikasi akan berjalan di `http://localhost:8000`

### **Default Login**

Setelah menjalankan seeder, gunakan kredensial berikut:

- **Admin:**
  - Email: `admin@example.com`
  - Password: `password`

- **Pimpinan:**
  - Email: `pimpinan@example.com`
  - Password: `password`

- **Staff:**
  - Email: `staff@example.com`
  - Password: `password`

> ⚠️ **PENTING:** Ganti password default setelah login pertama kali!

---

## 📖 Penggunaan

### **Untuk Admin**

1. Login dengan akun admin
2. Akses dashboard admin untuk melihat statistik
3. Kelola user melalui menu **Manajemen User**
4. Kelola kategori arsip
5. Lihat log aktivitas sistem
6. Konfigurasi pengaturan aplikasi

### **Untuk Pimpinan**

1. Login dengan akun pimpinan
2. Lihat dashboard dengan statistik disposisi
3. Buat disposisi untuk surat masuk
4. Approve surat keluar
5. Lihat laporan

### **Untuk Staff**

1. Login dengan akun staff
2. Input surat masuk dan surat keluar
3. Lihat disposisi yang ditugaskan
4. Update status disposisi
5. Kelola arsip dokumen

---

## 🛠️ Teknologi

### **Backend**
- **Laravel 10.x** - PHP Framework
- **MySQL/MariaDB** - Database
- **Laravel Sanctum** - API Authentication

### **Frontend**
- **Bootstrap 5.3** - CSS Framework
- **Bootstrap Icons** - Icon Library
- **Alpine.js** - JavaScript Framework
- **Tailwind CSS** - Utility-first CSS
- **Vite** - Build Tool

### **Libraries**
- **DomPDF** - PDF Generation
- **Maatwebsite Excel** - Excel Export
- **Carbon** - Date/Time Handling

---

## 📁 Struktur Proyek

```
e-arsip/
├── app/
│   ├── Http/Controllers/    # Controller aplikasi
│   ├── Models/              # Model Eloquent
│   ├── Exports/             # Excel Export Classes
│   └── View/Components/     # Blade Components
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/             # Database seeders
├── resources/
│   ├── views/               # Blade templates
│   ├── css/                 # Stylesheet
│   └── js/                  # JavaScript
├── routes/
│   ├── web.php              # Web routes
│   └── api.php              # API routes
└── public/
    └── uploads/             # File uploads
```

---

## 🔒 Keamanan

- ✅ Password hashing dengan bcrypt
- ✅ CSRF protection
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ XSS protection
- ✅ File upload validation
- ✅ Role-based access control
- ✅ Session management
- ✅ Rate limiting pada login

---

## 📊 Database

Sistem menggunakan 8 tabel utama:

1. **users** - Data pengguna
2. **surat_masuk** - Surat masuk
3. **surat_keluar** - Surat keluar
4. **disposisi** - Disposisi surat
5. **arsip** - Arsip dokumen
6. **kategori** - Kategori arsip
7. **notifikasi** - Notifikasi sistem
8. **log_aktivitas** - Log aktivitas user
9. **pengaturan** - Pengaturan sistem

---

## 🧪 Testing

```bash
# Run tests
php artisan test

# Run dengan coverage
php artisan test --coverage
```

---

## 📝 Lisensi

Proyek ini menggunakan lisensi **MIT License**.

---

## 👥 Developer

Dikembangkan oleh:

- **Izra Rafif Rabbani**
- **Adniel Rama Ezaputra**
- **Muhammad Rizky**

---

## 📞 Kontak & Support

Untuk pertanyaan, bug report, atau feature request, silakan buat issue di repository ini.

---

## 🙏 Terima Kasih

Terima kasih telah menggunakan **E-Arsip**! Semoga sistem ini dapat membantu meningkatkan efisiensi manajemen arsip di organisasi Anda.

---

<div align="center">

**Made with ❤️ using Laravel**

⭐ Star repository ini jika proyek ini membantu Anda!

</div>
