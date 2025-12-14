# Integrasi Supabase - Costbun

Website Costbun sudah terhubung ke Supabase. Berikut adalah fitur-fitur yang sudah terintegrasi:

## Konfigurasi
- **File Konfigurasi**: `config/supabase.php` - Berisi URL dan API Key
- **Helper Functions**: `config/supabase_helper.php` - Fungsi CRUD untuk semua tabel
- **Request Handler**: `config/supabase_request.php` - Handle HTTP request ke Supabase

## Fitur yang Sudah Terhubung

### 1. **Data Kamar** (`pages/data_kamar.php`)
- ✅ Menampilkan semua kamar dari Supabase
- ✅ Ubah status kamar (kosong/terisi)
- ✅ Tambah kamar baru
- **API**: `api/kamar.php`

### 2. **Data Kost/Penyewa** (`pages/data_kost.php`)
- ✅ Menampilkan daftar penyewa/user dari Supabase
- ✅ Hapus penyewa
- ✅ Detail dan edit penyewa
- **API**: `api/user.php`

### 3. **Laporan** (`pages/laporan.php`)
- ✅ Menampilkan laporan dengan data user (join table)
- ✅ Filter laporan berdasarkan status
- ✅ Ubah status laporan
- **API**: `api/laporan.php`

### 4. **Notifikasi** (`pages/notifikasi.php` & `notifikasi.js`)
- ✅ Load notifikasi dari Supabase secara dinamis
- ✅ Format waktu relatif
- ✅ Hapus notifikasi
- **API**: `api/notifikasi.php` & `api/notifikasi_data.php`

### 5. **Login** (`auth/login_process.php`)
- ✅ Sudah menggunakan Supabase untuk autentikasi

## API Endpoints

Semua API endpoint tersedia di folder `api/`:

### Kamar API (`api/kamar.php`)
```
GET  api/kamar.php?action=get              - Get semua kamar
GET  api/kamar.php?action=get&id=1         - Get kamar by ID
GET  api/kamar.php?action=update_status&id=1&status=kosong - Ubah status
GET  api/kamar.php?action=create&nama=A01&... - Tambah kamar
POST api/kamar.php?action=update&id=1      - Update kamar
GET  api/kamar.php?action=delete&id=1      - Hapus kamar
```

### User API (`api/user.php`)
```
GET  api/user.php?action=get               - Get semua user
GET  api/user.php?action=get&id=1          - Get user by ID
POST api/user.php?action=create            - Tambah user
POST api/user.php?action=update&id=1       - Update user
GET  api/user.php?action=delete&id=1       - Hapus user
```

### Laporan API (`api/laporan.php`)
```
GET  api/laporan.php?action=get            - Get semua laporan
GET  api/laporan.php?action=get&id=1       - Get laporan by ID
GET  api/laporan.php?action=update_status&id=1&status=selesai
POST api/laporan.php?action=create         - Tambah laporan
POST api/laporan.php?action=update&id=1    - Update laporan
GET  api/laporan.php?action=delete&id=1    - Hapus laporan
```

### Notifikasi API (`api/notifikasi.php`)
```
GET  api/notifikasi.php?action=get         - Get semua notifikasi
GET  api/notifikasi_data.php               - Get notifikasi (format JSON)
POST api/notifikasi.php?action=create      - Tambah notifikasi
POST api/notifikasi.php?action=update&id=1 - Update notifikasi
GET  api/notifikasi.php?action=delete&id=1 - Hapus notifikasi
```

### Tagihan API (`api/tagihan.php`)
```
GET  api/tagihan.php?action=get            - Get semua tagihan
POST api/tagihan.php?action=create         - Tambah tagihan
POST api/tagihan.php?action=update&id=1    - Update tagihan
GET  api/tagihan.php?action=delete&id=1    - Hapus tagihan
```

### Keuangan API (`api/keuangan.php`)
```
GET  api/keuangan.php?action=get           - Get semua keuangan
POST api/keuangan.php?action=create        - Tambah keuangan
POST api/keuangan.php?action=update&id=1   - Update keuangan
GET  api/keuangan.php?action=delete&id=1   - Hapus keuangan
```

## Cara Menggunakan

### 1. Menggunakan Helper Functions
```php
// Di halaman PHP manapun
require_once __DIR__ . '/../config/supabase_helper.php';

// Get data
$kamarList = getKamar();           // Semua kamar
$kamar = getKamar(1);              // Kamar by ID

// Create
$newKamar = createKamar([
    'nama' => 'A-01',
    'kasur' => 1,
    'kipas' => 1,
    'lemari' => 1,
    'keranjang_sampah' => 1,
    'ac' => 0,
    'harga' => 500000,
    'status' => 'kosong'
]);

// Update
updateKamar(1, ['status' => 'terisi']);

// Delete
deleteKamar(1);
```

### 2. Join Table (Foreign Key)
Helper functions sudah support join otomatis:
```php
// Laporan dengan data user
$laporanList = getLaporan();
foreach($laporanList as $lap) {
    echo $lap['user']['nama'];  // Nama user dari foreign key
}

// Tagihan dengan user dan kamar
$tagihanList = getTagihan();
foreach($tagihanList as $tag) {
    echo $tag['user']['nama'];   // Nama user
    echo $tag['kamar']['nama'];  // Nama kamar
}
```

## Testing

Untuk testing koneksi, buka halaman yang sudah terintegrasi:
1. **Login**: Sudah berfungsi dengan Supabase
2. **Data Kamar**: http://localhost/Costbun/index.php?page=data_kamar
3. **Data Kost**: http://localhost/Costbun/index.php?page=data_kost
4. **Laporan**: http://localhost/Costbun/index.php?page=laporan
5. **Notifikasi**: http://localhost/Costbun/index.php?page=notifikasi

## Notes
- API Key dan URL sudah dikonfigurasi di `config/supabase.php` (tidak diubah sesuai permintaan)
- Semua query sudah menggunakan Supabase REST API
- Login sudah menggunakan Supabase (file: `auth/login_process.php`)
- Helper functions mendukung foreign key relationships
- Error handling sudah diimplementasi

## Struktur File Baru
```
config/
  ├── supabase.php              (sudah ada, tidak diubah)
  ├── supabase_request.php      (sudah ada)
  └── supabase_helper.php       (baru - fungsi CRUD)

api/
  ├── kamar.php                 (baru)
  ├── user.php                  (baru)
  ├── laporan.php               (baru)
  ├── notifikasi.php            (baru)
  ├── notifikasi_data.php       (baru)
  ├── tagihan.php               (baru)
  └── keuangan.php              (baru)

pages/
  ├── data_kamar.php            (diupdate)
  ├── data_kost.php             (diupdate)
  └── laporan.php               (diupdate)

notifikasi.js                   (diupdate)
```
