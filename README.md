# 🅿️ E-Parkir - Sistem Manajemen Parkir Digital

Sistem manajemen parkir berbasis web yang dirancang untuk mengelola transaksi parkir, user, tarif, area parkir, dan laporan pendapatan secara real-time.

---

## 📋 Daftar Isi
- [Deskripsi Sistem](#deskripsi-sistem)
- [Fitur Utama](#fitur-utama)
- [Role & Pengguna](#role--pengguna)
- [Alur Sistem](#alur-sistem)
- [Struktur Folder](#struktur-folder)
- [Teknologi](#teknologi)
- [Setup & Instalasi](#setup--instalasi)
- [Database](#database)

---

## 📌 Deskripsi Sistem

**E-Parkir** adalah platform manajemen parkir terintegrasi yang memungkinkan:
- Pencatatan kendaraan masuk dan keluar parkir
- Perhitungan otomatis biaya parkir berdasarkan durasi dan jenis kendaraan
- Pengelolaan area parkir dengan kapasitas real-time
- Manajemen user dengan role berbeda
- Laporan pendapatan dan riwayat transaksi
- Log aktivitas untuk audit trail

---

## ✨ Fitur Utama

### 1. **Dashboard Real-Time**
- Monitoring kendaraan yang sedang parkir
- Status area parkir (kapasitas & terisi)
- Statistik transaksi harian
- Grafik pendapatan 7 hari terakhir

### 2. **Transaksi Parkir**
- Input kendaraan masuk (plat, jenis, area)
- Perhitungan otomatis durasi & biaya
- Pencatatan kendaraan keluar
- Struk digital

### 3. **Kelola Data Master**
- Manajemen area parkir (nama, kapasitas)
- Manajemen tarif per jenis kendaraan
- Manajemen data kendaraan
- Manajemen user dengan role-based access

### 4. **Laporan & Analytics**
- Rekap transaksi dengan filter periode
- Laporan pendapatan harian/bulanan
- Cetak laporan (print/download)
- Log aktivitas user

### 5. **Responsive Design**
- Desktop, tablet, dan mobile-friendly
- Hamburger menu di mobile
- Dropdown navigation
- Touch-friendly interface

---

## 👥 Role & Pengguna

Sistem terdapat **3 role utama** dengan permission berbeda:

### 🔐 **ADMIN**
**Tujuan:** Mengelola sistem secara keseluruhan

**Akses:**
- ✓ Dashboard monitoring
- ✓ Kelola User (tambah, edit, hapus)
- ✓ Kelola Area Parkir
- ✓ Kelola Tarif
- ✓ Kelola Data Kendaraan
- ✓ Monitoring Transaksi
- ✓ Log Aktivitas
- ✓ Logout

**File:** `admin/` folder

---

### 🚗 **PETUGAS**
**Tujuan:** Input transaksi parkir harian

**Akses:**
- ✓ Dashboard (real-time vehicles status)
- ✓ Input Transaksi Masuk
- ✓ Input Transaksi Keluar
- ✓ Lihat Riwayat Transaksi
- ✓ Cetak Struk
- ✓ Logout

**File:** `petugas/` folder

---

### 📊 **OWNER**
**Tujuan:** Monitoring financial & reporting

**Akses:**
- ✓ Dashboard (ringkasan pendapatan)
- ✓ Rekap Transaksi (dengan filter periode)
- ✓ Export/Cetak Laporan Pendapatan
- ✓ Logout

**File:** `owner/` folder

---

## 🔄 Alur Sistem

### **1️⃣ ALUR LOGIN**
```
User Akses Sistem
        ↓
[/auth/login.php] - Validasi username & password
        ↓
Login Berhasil → Redirect ke Dashboard sesuai role
        ↓
Session Tersimpan di $_SESSION
```

### **2️⃣ ALUR TRANSAKSI MASUK**
```
Petugas Buka "Transaksi Masuk"
        ↓
[petugas/transaksi_masuk.php]
        ↓
Pilih Kendaraan (dropdown dari tabel kendaraan)
        ↓
Sistem Auto-fill: Jenis Kendaraan
        ↓
Pilih Area Parkir
        ↓
Sistem Cek: Apakah area masih ada slot?
        ↓
Jika PENUH → Alert & batal
Jika TERSEDIA → Lanjut
        ↓
Klik "Simpan"
        ↓
[transaksi_keluar.php] - INSERT ke tabel transaksi
        ↓
Redirect ke Dashboard + Success Alert
        ↓
Email/Notif Masuk (Opsional)

**Database:**
- INSERT: transaksi (id_kendaraan, waktu_masuk, id_tarif, status='masuk', id_user, id_area)
```

### **3️⃣ ALUR TRANSAKSI KELUAR**
```
Petugas Lihat Kendaraan di Dashboard
        ↓
Klik Tombol "Keluar" pada kendaraan
        ↓
[petugas/transaksi_keluar.php]
        ↓
Sistem Hitung:
  - Waktu Masuk: dari database
  - Waktu Keluar: NOW()
  - Durasi: CEIL((waktu_keluar - waktu_masuk) / 3600)
  - Biaya: durasi × tarif_per_jam
        ↓
UPDATE transaksi:
  - waktu_keluar = NOW()
  - durasi_jam = hitung
  - biaya_total = hitung
  - status = 'keluar'
        ↓
Redirect → Struk Digital
        ↓
[petugas/struk.php] - Tampilkan struk
        ↓
User Bisa Print / Kembali ke Dashboard
```

### **4️⃣ ALUR MANAJEMEN AREA PARKIR (ADMIN)**
```
Admin Buka "Area Parkir"
        ↓
[admin/area.php]
        ↓
LIHAT DAFTAR:
- Tampil semua area
- Kolom: ID, Nama, Kapasitas, Terisi
        ↓
TAMBAH AREA:
- Input nama area & kapasitas
- INSERT: area_parkir (nama_area, kapasitas, terisi=0)
        ↓
HAPUS AREA:
- DELETE dari area_parkir WHERE id_area = X
- Log aktivitas tercatat
```

### **5️⃣ ALUR MANAJEMEN TARIF (ADMIN)**
```
Admin Buka "Tarif"
        ↓
[admin/tarif.php]
        ↓
LIHAT DAFTAR:
- Tampil: Jenis Kendaraan, Tarif/Jam
        ↓
TAMBAH TARIF:
- Pilih jenis (motor, mobil, lainnya)
- Input tarif/jam
- Format currency dengan separator Rp
- INSERT: tarif (jenis_kendaraan, tarif_per_jam)
        ↓
HAPUS TARIF:
- DELETE dari tarif WHERE id_tarif = X
```

### **6️⃣ ALUR LAPORAN PENDAPATAN (OWNER)**
```
Owner Buka "Rekap Transaksi"
        ↓
[owner/rekap.php]
        ↓
INPUT FILTER:
- Dari Tanggal (date picker)
- Sampai Tanggal (date picker)
        ↓
QUERY DATABASE:
SELECT * FROM transaksi 
WHERE status='keluar' 
  AND DATE(waktu_keluar) BETWEEN tgl1 AND tgl2
        ↓
TAMPILKAN TABEL:
- Daftar transaksi sesuai filter
- Total pendapatan dihitung otomatis
        ↓
AKSI:
- Print → Window.print()
- Download → [owner/cetak_laporan.php]
  - Generate PDF / Excel
  - Simpan file
  - Download ke device
```

### **7️⃣ ALUR LOG AKTIVITAS (ADMIN)**
```
Admin Buka "Log Aktivitas"
        ↓
[admin/log.php]
        ↓
QUERY DATABASE:
SELECT FROM log_aktivitas
JOIN dengan user untuk nama & role
ORDER BY waktu_aktivitas DESC
        ↓
TAMPILKAN:
- User, Role, Aktivitas, Waktu
- Contoh aktivitas:
  - Login ke sistem sebagai admin
  - Menambah area parkir "Area A"
  - Menghapus user ID 5
  - Melihat rekap transaksi
```

---

## 📁 Struktur Folder

```
parkir/
├── index.php                    # Redirect ke login
├── README.md                    # Dokumentasi
├── db_parkir.sql                # Backup database
│
├── assets/
│   └── style.css                # CSS global (responsive)
│
├── config/
│   ├── auth.php                 # Session & role check
│   ├── koneksi.php              # Database connection
│   └── helper.php               # Helper functions
│
├── auth/
│   ├── login.php                # Form login
│   ├── logout.php               # Logout handler
│   └── proses_login.php         # Login validation
│
├── admin/                        # Role: ADMIN
│   ├── sidebar_admin.php        # Navigation menu
│   ├── dashboard.php            # Admin dashboard
│   ├── user.php                 # Kelola user
│   ├── kendaraan.php            # Kelola kendaraan
│   ├── tarif.php                # Kelola tarif
│   ├── area.php                 # Kelola area
│   ├── transaksi.php            # Monitor transaksi
│   └── log.php                  # Log aktivitas
│
├── petugas/                      # Role: PETUGAS
│   ├── sidebar.php              # Navigation menu
│   ├── dashboard.php            # Petugas dashboard
│   ├── transaksi_masuk.php      # Input kendaraan masuk
│   ├── transaksi_keluar.php     # Process kendaraan keluar
│   ├── riwayat.php              # Riwayat transaksi
│   └── struk.php                # Struk digital
│
├── owner/                        # Role: OWNER
│   ├── sidebar_owner.php        # Navigation menu
│   ├── dashboard.php            # Owner dashboard
│   ├── rekap.php                # Rekap transaksi
│   └── cetak_laporan.php        # Export/Print laporan
│
└── [Akses Terbatas]
    └── HTTP → HTTPS redirect
    └── Invalid role → Redirect login
```

---

## 🛠️ Teknologi

| Komponen | Teknologi |
|----------|-----------|
| **Backend** | PHP 8.2+ |
| **Database** | MySQL 8.0+ |
| **Frontend** | HTML5, CSS3, JavaScript |
| **Framework** | Bootstrap 5.3.2 |
| **Icons** | Font Awesome 6.5.0 |
| **Charts** | Chart.js |
| **Font** | Google Fonts (Inter) |
| **Server** | Apache (Laragon) |

---

## 🚀 Setup & Instalasi

### **1. Prerequisites**
- PHP 8.0+
- MySQL 8.0+
- Apache/Nginx
- Laragon (recommended) atau XAMPP

### **2. Instalasi Database**
```bash
# Import file SQL
mysql -u root -p db_parkir < db_parkir.sql

# Atau gunakan PhpMyAdmin:
# 1. Buka phpMyAdmin
# 2. Create database "db_parkir"
# 3. Import file db_parkir.sql
```

### **3. Konfigurasi Koneksi**
Edit file `config/koneksi.php`:
```php
$conn = mysqli_connect(
    "localhost",  // Host
    "root",       // Username
    "",           // Password
    "db_parkir",  // Database
    3308          // Port (sesuaikan)
);
```

### **4. Jalankan Aplikasi**
```bash
# Laragon
http://localhost/parkir

# XAMPP (jika di folder htdocs)
http://localhost/parkir
```

### **5. Login Pertama Kali**
| Role | Username | Password |
|------|----------|----------|
| Admin | admin | admin123 |
| Petugas | petugas | petugas123 |
| Owner | owner | owner123 |

⚠️ **Ubah password setelah login pertama!**

---

## 💾 Database Schema

### **Tabel: user**
```sql
id_user (PK)
nama_lengkap
username (UNIQUE)
password
role (admin | petugas | owner)
status_aktif
created_at
```

### **Tabel: area_parkir**
```sql
id_area (PK)
nama_area
kapasitas
terisi (calculated)
created_at
```

### **Tabel: tarif**
```sql
id_tarif (PK)
jenis_kendaraan (motor | mobil | lainnya)
tarif_per_jam
created_at
```

### **Tabel: kendaraan**
```sql
id_kendaraan (PK)
plat_nomor (UNIQUE)
jenis_kendaraan
warna
pemilik
id_user (FK)
created_at
```

### **Tabel: transaksi**
```sql
id_parkir (PK)
id_kendaraan (FK)
id_area (FK)
id_tarif (FK)
id_user (FK)
waktu_masuk
waktu_keluar
durasi_jam
biaya_total
status (masuk | keluar)
created_at
```

### **Tabel: log_aktivitas**
```sql
id_log (PK)
id_user (FK)
aktivitas (TEXT)
waktu_aktivitas
```

---

## 🔐 Security

- ✓ Session-based authentication
- ✓ Role-based access control (RBAC)
- ✓ Password hashing (recommend: password_hash)
- ✓ SQL injection prevention (mysqli_real_escape_string)
- ✓ CSRF protection (recommend: token)
- ✓ Input validation & sanitization
- ✓ Activity logging for audit trail

---

## 📱 Responsive Design

- ✓ Mobile-first approach
- ✓ Breakpoints: 1200px, 992px, 768px, 480px, 320px
- ✓ Hamburger menu di mobile
- ✓ Dropdown navigation
- ✓ Touch-friendly buttons (44px min-height)
- ✓ Horizontal scroll tables
- ✓ Full-width forms

---

## 🐛 Troubleshooting

### **Error: Koneksi Database Gagal**
- Pastikan MySQL running
- Periksa port MySQL (default: 3306,ulangi dengan 3308)
- Verifikasi credentials di `config/koneksi.php`

### **Error: Session Tidak Tersimpan**
- Pastikan `session_start()` di atas file
- Check PHP session save path permissions
- Verifikasi cookie settings

### **Hamburger Menu Tidak Muncul**
- Buka di browser mobile/responsif
- Breakpoint: max-width 991px
- Buka DevTools → Toggle Device Toolbar

---

## 📞 Support & Maintenance

- Database backup: Minimal seminggu sekali
- Log cleanup: Monthly (archive old logs)
- Password reset: Hubungi Admin
- Report bug: Contact system administrator

---

## 📄 Lisensi

Proprietary - Developed untuk sistem parkir internal

---

**Versi:** 1.0  
**Terakhir diupdate:** April 2026  
**Developer:** E-Parkir Team

---

## 🎯 Roadmap Fitur Mendatang

- [ ] SMS/Email notification
- [ ] QR code parking pass
- [ ] Mobile app (Android)
- [ ] Payment gateway integration
- [ ] Analytics dashboard
- [ ] API REST
- [ ] Multi-branch support
- [ ] Booking system

---

**Happy Parking! 🅿️**
