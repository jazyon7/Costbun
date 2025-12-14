# 📋 Fitur Laporan - Role-Based Access

## 🎯 Overview

Sistem laporan memungkinkan **penghuni kos** untuk melaporkan masalah/keluhan (seperti AC mati, kerusakan, dll) dan **admin** untuk mengelola serta mengubah status laporan tersebut.

---

## 👥 Role-Based Access Control

### 🏠 **Penghuni Kos**

**Dapat melakukan:**
- ✅ **Membuat laporan baru** (Tombol "Tambah Laporan")
- ✅ **Melihat laporan sendiri** (hanya laporan yang dibuat oleh penghuni tersebut)
- ✅ **Melihat detail laporan**
- ✅ **Filter laporan by status** (Diproses/Selesai)

**Tidak dapat melakukan:**
- ❌ Mengubah status laporan
- ❌ Menghapus laporan
- ❌ Melihat laporan penghuni lain
- ❌ Edit laporan setelah submit

### 👨‍💼 **Admin**

**Dapat melakukan:**
- ✅ **Melihat semua laporan** dari seluruh penghuni
- ✅ **Mengubah status laporan** (Diproses ↔ Selesai)
- ✅ **Melihat detail laporan**
- ✅ **Filter laporan by status**

**Tidak dapat melakukan:**
- ❌ Membuat laporan baru (tombol tidak muncul)

---

## 📝 Cara Penggunaan

### Untuk Penghuni

#### 1. Buat Laporan Baru

1. Login sebagai **penghuni**
2. Buka halaman **Laporan**: http://costbun.test/index.php?page=laporan
3. Klik tombol **"+ Tambah Laporan"** (kanan atas)
4. Isi form modal:
   - **Judul Laporan**: Judul singkat (contoh: "AC Kamar Mati")
   - **Deskripsi Masalah**: Jelaskan masalah secara detail
5. Klik **"Kirim Laporan"**
6. Laporan akan muncul di tabel dengan status **"diproses"**

#### 2. Lihat Laporan Sendiri

- Setelah login, penghuni hanya melihat laporan yang mereka buat sendiri
- Tidak bisa melihat laporan penghuni lain (privacy)

#### 3. Cek Status Laporan

- **Diproses** (🟡): Laporan sedang ditangani admin
- **Selesai** (🟢): Masalah sudah diselesaikan

### Untuk Admin

#### 1. Lihat Semua Laporan

- Login sebagai **admin**
- Buka halaman **Laporan**
- Admin bisa melihat **semua laporan** dari seluruh penghuni
- Tombol "Tambah Laporan" **tidak muncul** untuk admin

#### 2. Ubah Status Laporan

1. Klik tombol **"Ubah Status"** pada laporan
2. Konfirmasi perubahan status:
   - Dari **"diproses"** → **"selesai"**
   - Dari **"selesai"** → **"diproses"** (jika perlu dibuka kembali)
3. Status akan berubah dan halaman refresh

#### 3. Filter Laporan

- Gunakan dropdown **Status** di atas tabel
- Pilih: Semua / Diproses / Selesai
- Klik tombol **"Filter"**

---

## 🗂️ Struktur Database

### Table: `laporan`

| Column | Type | Description |
|--------|------|-------------|
| `id_laporan` | BIGINT | Primary key (auto increment) |
| `judul_laporan` | VARCHAR | Judul singkat laporan |
| `deskripsi` | TEXT | Deskripsi detail masalah |
| `status_laporan` | VARCHAR | Status: 'diproses' atau 'selesai' |
| `id_user` | BIGINT | Foreign key ke table user (pelapor) |
| `created_at` | TIMESTAMP | Waktu laporan dibuat |

### Relasi

```
laporan.id_user → user.id_user (Foreign Key)
```

---

## 🔧 Technical Implementation

### Frontend (pages/laporan.php)

**Role Detection:**
```php
<?php 
$userRole = $_SESSION['role'] ?? 'admin';
$userId = $_SESSION['id_user'] ?? 0;

// Filter untuk penghuni - hanya laporan sendiri
if ($userRole === 'penghuni') {
    $laporanList = array_filter($laporanList, function($laporan) use ($userId) {
        return $laporan['id_user'] == $userId;
    });
}
?>
```

**Conditional UI:**
```php
<!-- Tombol Tambah Laporan (hanya untuk penghuni) -->
<?php if ($_SESSION['role'] === 'penghuni'): ?>
<button class="btn-tambah-laporan" onclick="openModalLaporan()">
  <i class="fa fa-plus"></i> Tambah Laporan
</button>
<?php endif; ?>

<!-- Tombol Ubah Status (hanya untuk admin) -->
<?php if ($userRole === 'admin'): ?>
<button class="btn-edit" onclick="updateStatus(...)">Ubah Status</button>
<?php endif; ?>
```

**Modal Form:**
- Modern modal design dengan fade-in animation
- Form validation (required fields)
- AJAX submit dengan Fetch API
- Auto-reload setelah berhasil

**JavaScript Functions:**
```javascript
// Open/Close Modal
openModalLaporan()
closeModalLaporan()

// Submit dengan validation
async submitLaporan(event)

// Filter by status
filterLaporan()

// Lihat detail (alert popup)
detailLaporan(id)

// Update status (admin only)
updateStatus(id, currentStatus)
```

### Backend (api/laporan.php)

**API Endpoints:**

| Endpoint | Method | Role | Description |
|----------|--------|------|-------------|
| `?action=create` | POST | Penghuni | Buat laporan baru |
| `?action=update_status` | GET | Admin | Ubah status laporan |
| `?action=get` | GET | All | Get single laporan |
| `?action=get` (no id) | GET | All | Get all laporan |
| `?action=update` | PATCH | Admin | Update laporan |
| `?action=delete` | DELETE | Admin | Hapus laporan |

**Role Validation:**

```php
// Cek role untuk create
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penghuni') {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
    exit;
}

// Cek role untuk update status
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
    exit;
}
```

**Request Handling:**

```php
// Parse JSON request
$input = json_decode(file_get_contents("php://input"), true);

// Validasi
if (empty($input['judul_laporan']) || empty($input['deskripsi'])) {
    echo json_encode(['success' => false, 'message' => 'Field wajib diisi']);
    exit;
}

// Save to database
$data = [
    'judul_laporan' => trim($input['judul_laporan']),
    'deskripsi' => trim($input['deskripsi']),
    'status_laporan' => 'diproses',
    'id_user' => (int)$_SESSION['id_user']
];

$result = createLaporan($data);
```

### Database Helper (config/supabase_helper.php)

**Functions:**

```php
// Get all laporan atau single laporan
getLaporan($id_laporan = null)

// Create laporan baru
createLaporan($data)

// Update laporan (status atau field lain)
updateLaporan($id_laporan, $data)

// Delete laporan
deleteLaporan($id_laporan)
```

**Supabase Query dengan Join:**
```php
// Get dengan nama user (JOIN)
supabase_request('GET', '/rest/v1/laporan?select=*,user(nama)&order=created_at.desc');
```

---

## 🎨 UI Components

### Tombol Tambah Laporan

```css
.btn-tambah-laporan {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-tambah-laporan:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
}
```

### Modal Form

- **Width**: 90%, max 600px
- **Animation**: Fade-in + slide-down
- **Style**: Modern with shadow and rounded corners
- **Fields**: 
  - Input text (judul)
  - Textarea (deskripsi)
- **Buttons**: Submit (gradient) + Cancel (gray)

### Table Status Badge

- **Diproses**: Yellow badge with `.pending` class
- **Selesai**: Green badge with `.success` class

---

## 📊 Contoh Data Laporan

```json
{
  "id_laporan": 1,
  "judul_laporan": "AC Kamar Mati",
  "deskripsi": "AC di kamar 101 tidak menyala sejak kemarin malam. Sudah dicoba nyalakan berkali-kali tapi tetap tidak berfungsi. Mohon segera diperbaiki karena cuaca panas.",
  "status_laporan": "diproses",
  "id_user": 5,
  "created_at": "2025-12-15T14:30:00",
  "user": {
    "nama": "Rizki Ramadhan"
  }
}
```

---

## ✅ Testing Checklist

### Test sebagai Penghuni

- [ ] Login sebagai penghuni
- [ ] Tombol "Tambah Laporan" muncul
- [ ] Klik tombol → modal terbuka
- [ ] Isi form dan submit
- [ ] Laporan muncul di tabel dengan status "diproses"
- [ ] Hanya melihat laporan sendiri (tidak ada laporan penghuni lain)
- [ ] Tombol "Ubah Status" tidak muncul
- [ ] Klik "Detail" → popup detail laporan
- [ ] Filter by status berfungsi

### Test sebagai Admin

- [ ] Login sebagai admin
- [ ] Tombol "Tambah Laporan" tidak muncul
- [ ] Melihat semua laporan dari seluruh penghuni
- [ ] Tombol "Ubah Status" muncul di setiap row
- [ ] Klik "Ubah Status" → konfirmasi → status berubah
- [ ] Status toggle: diproses ↔ selesai
- [ ] Klik "Detail" → popup detail laporan
- [ ] Filter by status berfungsi

---

## 🔐 Security Features

1. **Session-based Authentication**
   - Semua API memerlukan session aktif
   - Role check di setiap endpoint sensitif

2. **Role-based Authorization**
   - Penghuni hanya bisa create dan view own
   - Admin bisa view all dan update status
   - Cross-role access ditolak dengan message

3. **Data Validation**
   - Required fields validation
   - Trim whitespace
   - HTML escape output (XSS prevention)

4. **Error Logging**
   - Semua operation di-log ke PHP error log
   - Include request data dan result

---

## 🚀 Future Enhancements

Fitur yang bisa ditambahkan nanti:

1. **Upload Gambar**
   - Penghuni bisa upload foto masalah
   - Store di Supabase Storage

2. **Status Tambahan**
   - "pending" (belum dibaca)
   - "diproses" (sedang ditangani)
   - "selesai" (resolved)
   - "ditolak" (invalid report)

3. **Comment System**
   - Admin bisa komen di laporan
   - Penghuni bisa reply

4. **Notifikasi Real-time**
   - Penghuni dapat notif saat status berubah
   - Admin dapat notif saat ada laporan baru

5. **Filter Advanced**
   - Filter by date range
   - Filter by pelapor (admin only)
   - Search by keyword

6. **Priority Level**
   - Rendah / Sedang / Tinggi / Urgent
   - Admin bisa set priority

---

## 📞 Troubleshooting

### Tombol "Tambah Laporan" tidak muncul

**Penyebab:** Role bukan "penghuni" atau session tidak ada

**Solusi:**
```php
// Check session
var_dump($_SESSION['role']); // Harus "penghuni"
```

### Laporan tidak tersimpan

**Penyebab:** Kolom `id_user` di table laporan belum ada

**Solusi:**
```sql
ALTER TABLE laporan ADD COLUMN id_user BIGINT;
ALTER TABLE laporan ADD CONSTRAINT laporan_id_user_fkey 
FOREIGN KEY (id_user) REFERENCES "user"(id_user) ON DELETE CASCADE;
```

### Penghuni bisa lihat laporan orang lain

**Penyebab:** Filter di PHP tidak jalan

**Solusi:** Check filter logic di pages/laporan.php:
```php
if ($userRole === 'penghuni') {
    $laporanList = array_filter($laporanList, function($laporan) use ($userId) {
        return $laporan['id_user'] == $userId;
    });
}
```

### Error "Akses ditolak"

**Penyebab:** Role tidak sesuai dengan endpoint

**Solusi:** 
- Pastikan penghuni tidak akses update_status
- Pastikan admin tidak akses create

---

## 🔗 Related Files

### Frontend
- [pages/laporan.php](pages/laporan.php) - Main page dengan modal
- [style.css](style.css) - Global styles

### Backend
- [api/laporan.php](api/laporan.php) - API endpoints dengan role check
- [config/supabase_helper.php](config/supabase_helper.php) - Database functions

### Documentation
- [STRUKTUR_APLIKASI.md](STRUKTUR_APLIKASI.md) - Overall app structure
- [FITUR_NOTIFIKASI.md](FITUR_NOTIFIKASI.md) - Similar feature reference

---

**Terakhir diupdate:** 15 Desember 2025  
**Status:** ✅ Implemented & Tested
