# 📁 Struktur Aplikasi Costbun

## Overview
Costbun adalah sistem manajemen kos-kosan yang terintegrasi dengan Supabase database. Semua file sudah dioptimasi dan diintegrasikan dalam satu aplikasi kohesif.

## 🗂️ Struktur Folder

```
Costbun/
├── index.php               # Router utama aplikasi
├── login.php              # Halaman login
├── logout.php             # Proses logout
├── style.css              # Stylesheet global
├── navigasi.js            # JavaScript untuk navigasi sidebar
├── notifikasi.js          # JavaScript untuk notifikasi real-time
├── koneksi.php            # Koneksi database lama (backup)
├── form_handler.php       # Handler untuk form submission
│
├── auth/                  # Autentikasi
│   └── login_process.php  # Proses login dengan Supabase
│
├── config/                # Konfigurasi
│   ├── supabase.php       # Konfigurasi Supabase (URL & API Key)
│   ├── supabase_request.php  # HTTP client untuk Supabase API
│   └── supabase_helper.php   # Helper functions CRUD
│
├── api/                   # API Endpoints
│   ├── kamar.php          # API CRUD kamar
│   ├── user.php           # API CRUD user
│   ├── laporan.php        # API CRUD laporan
│   ├── notifikasi.php     # API CRUD notifikasi
│   ├── notifikasi_data.php # JSON endpoint untuk notifikasi
│   ├── tagihan.php        # API CRUD tagihan
│   └── keuangan.php       # API CRUD keuangan
│
├── pages/                 # Halaman Aplikasi
│   ├── dashboard.php      # Dashboard dengan statistik
│   ├── data_kamar.php     # Manajemen data kamar
│   ├── data_kost.php      # Manajemen data penghuni
│   ├── laporan.php        # Laporan keluhan
│   ├── notifikasi.php     # Daftar notifikasi
│   ├── profil.php         # Profil user
│   ├── setting.php        # Pengaturan
│   ├── tools.php          # Portal tools development
│   ├── test_koneksi.php   # Testing koneksi Supabase
│   ├── tambah_data.php    # Form tambah data (6 tabel)
│   └── demo_api.php       # Demo testing API
│
└── tools/                 # Development Tools
    └── hash_password_once.php  # Tool untuk hash password
```

## 🎨 Tema Warna
- **Primary Blue**: `#3681ff` - Warna utama aplikasi
- **Success Green**: `#4CAF50` - Status berhasil
- **Warning Orange**: `#ffa726` - Peringatan
- **Error Red**: `#ea4335` - Error/Danger
- **Purple Gradient**: `#764ba2` - Aksen tambahan
- **Text**: `#222` - Warna teks utama
- **Background**: `#fafafa` - Background halaman

## 🚀 Navigasi Aplikasi

### Menu Utama
1. **Dashboard** (`?page=dashboard`)
   - Statistik kamar (total, terisi, kosong)
   - Statistik user
   - Status laporan
   - Notifikasi belum dibaca

2. **Notifikasi** (`?page=notifikasi`)
   - Daftar notifikasi real-time
   - Mark as read/unread

3. **Data Kos** (`?page=data_kost`)
   - Daftar penghuni kos
   - Informasi kontak dan kamar

4. **Data Kamar** (`?page=data_kamar`)
   - Manajemen kamar
   - Toggle status kosong/terisi
   - Tambah kamar baru
   - Informasi fasilitas

5. **Laporan** (`?page=laporan`)
   - Laporan keluhan dari penghuni
   - Update status (diproses/selesai)
   - Informasi lengkap dengan JOIN user

### Menu Tools (Development)
6. **Tools Portal** (`?page=tools`)
   - Hub untuk semua development tools
   
7. **Test Koneksi** (`?page=test_koneksi`)
   - Test koneksi ke Supabase
   - Lihat semua data dari 6 tabel
   
8. **Tambah Data** (`?page=tambah_data`)
   - Form lengkap untuk insert data
   - 6 tab: Kamar, User, Laporan, Notifikasi, Tagihan, Keuangan
   
9. **Demo API** (`?page=demo_api`)
   - Testing interaktif semua API endpoint
   - GET, CREATE, UPDATE, DELETE operations

### Menu User
10. **Profil** (`?page=profil`)
    - Informasi profil user
    
11. **Setting** (`?page=setting`)
    - Pengaturan akun
    - Update informasi pribadi

## 🔌 API Endpoints

Semua API menggunakan pattern yang sama:

### GET - Ambil Data
```
api/[table].php?action=get
api/[table].php?action=get&id=1
```

### POST - Create Data
```
api/[table].php?action=create&[params]
```

### PATCH - Update Data
```
api/[table].php?action=update&id=1&[params]
api/[table].php?action=update_status&id=1&status=value
```

### DELETE - Hapus Data
```
api/[table].php?action=delete&id=1
```

### Available Tables
- `kamar` - Data kamar kos
- `user` - Data penghuni
- `laporan` - Laporan keluhan
- `notifikasi` - Notifikasi sistem
- `tagihan` - Tagihan pembayaran
- `keuangan` - Transaksi keuangan

## 📊 Database Tables (Supabase)

### 1. kamar
- `id` (primary key)
- `nama` (varchar)
- `kasur`, `kipas`, `lemari`, `keranjang_sampah`, `ac` (integer)
- `harga` (numeric)
- `status` (varchar: 'kosong' / 'terisi')
- `created_at` (timestamp)

### 2. user
- `id` (primary key)
- `nama` (varchar)
- `no_telp` (varchar)
- `email` (varchar)
- `password` (varchar - hashed)
- `no_ktp` (varchar)
- `no_kamar` (varchar)
- `created_at` (timestamp)

### 3. laporan
- `id` (primary key)
- `id_user` (foreign key → user.id)
- `deskripsi` (text)
- `status` (varchar: 'diproses' / 'selesai')
- `created_at` (timestamp)

### 4. notifikasi
- `id` (primary key)
- `id_user` (foreign key → user.id)
- `judul` (varchar)
- `deskripsi` (text)
- `is_read` (boolean)
- `created_at` (timestamp)

### 5. tagihan
- `id` (primary key)
- `id_user` (foreign key → user.id)
- `id_kamar` (foreign key → kamar.id)
- `bulan` (varchar)
- `jumlah` (numeric)
- `status` (varchar: 'belum_bayar' / 'lunas')
- `created_at` (timestamp)

### 6. keuangan
- `id` (primary key)
- `tanggal` (date)
- `jenis` (varchar: 'pemasukan' / 'pengeluaran')
- `kategori` (varchar)
- `jumlah` (numeric)
- `keterangan` (text)
- `created_at` (timestamp)

## 🔐 Autentikasi

Session-based authentication menggunakan PHP session:
- Login: `login.php` → `auth/login_process.php`
- Logout: `logout.php`
- Session variables:
  - `$_SESSION['user_id']`
  - `$_SESSION['nama']`
  - `$_SESSION['email']`

## 💡 Cara Penggunaan

### 1. Setup Database
- Pastikan semua tabel sudah dibuat di Supabase
- Konfigurasi sudah ada di `config/supabase.php`

### 2. Login
- Akses `login.php`
- Gunakan kredensial yang sudah terdaftar di tabel `user`

### 3. Navigasi
- Gunakan sidebar untuk berpindah halaman
- Klik menu sesuai kebutuhan

### 4. Development
- Akses Tools menu untuk testing
- Gunakan Demo API untuk testing endpoint
- Gunakan Tambah Data untuk insert data testing

## 📝 Notes

- **Semua file HTML sudah dihapus** - Hanya gunakan versi PHP
- **Standalone tools sudah diintegrasikan** - Akses via menu Tools
- **Warna sudah konsisten** - Semua menggunakan `#3681ff`
- **Font**: Montserrat dari Google Fonts
- **Icons**: Font Awesome 6.4.2

## 🔧 Development Tips

1. **Testing API**: Gunakan halaman Demo API (`?page=demo_api`)
2. **Insert Data**: Gunakan halaman Tambah Data (`?page=tambah_data`)
3. **Check Koneksi**: Gunakan Test Koneksi (`?page=test_koneksi`)
4. **Hash Password**: Gunakan `tools/hash_password_once.php`

## 🎯 Next Steps

Aplikasi sudah lengkap dan siap digunakan. Semua fitur sudah terintegrasi dengan baik:
- ✅ Database Supabase terhubung
- ✅ API endpoints berfungsi
- ✅ Semua halaman sudah menggunakan Supabase
- ✅ Tools development terintegrasi
- ✅ Warna konsisten
- ✅ File redundan terhapus

---
**Costbun** - Sistem Manajemen Kos-kosan Modern 🏠
