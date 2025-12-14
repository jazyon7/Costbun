# Fitur Manajemen User - Data Kos

## 📋 Ringkasan Fitur

Fitur manajemen user telah ditambahkan ke halaman **Data Kos** (`index.php?page=data_kos`) dengan kontrol akses berbasis role.

## ✨ Fitur yang Ditambahkan

### 1. **Tambah User (Admin Only)**
- ✅ Modal form untuk menambah user baru
- ✅ Field yang tersedia:
  - Nama Lengkap *
  - Email *
  - No. HP/WA *
  - Role * (Admin / Penghuni Kos)
  - Username *
  - Password * (min. 6 karakter)
  - Alamat
  - KTP/KTM
  - Telegram ID
- ✅ Password otomatis di-hash dengan bcrypt
- ✅ Validasi form client-side dan server-side
- ✅ Tombol "Tambah User" hanya muncul untuk admin

### 2. **Kontrol Akses Berbasis Role**
- ✅ Admin: Bisa tambah, edit, hapus user (kecuali admin lain)
- ✅ Penghuni Kos: Hanya bisa melihat data (read-only)
- ✅ Tombol edit & delete disabled untuk non-admin
- ✅ Role disimpan di session saat login

### 3. **Role Management**
- ✅ Dua role utama:
  - **admin**: Akses penuh ke sistem
  - **penghuni kos**: Akses terbatas (view only)
- ✅ Status ditampilkan dengan badge warna:
  - Admin: Biru
  - Penghuni Kos: Hijau
  - User lain: Orange

## 🔐 Keamanan

### Password Security
- Password di-hash menggunakan **bcrypt** (PASSWORD_BCRYPT)
- Backward compatibility: Support login dengan password lama (plain text)
- Password baru selalu tersimpan dalam format hash

### Session Management
```php
$_SESSION['id_user']  // ID user
$_SESSION['nama']      // Nama user
$_SESSION['role']      // Role user (admin/penghuni kos)
```

## 📁 File yang Dimodifikasi

1. **pages/data_kost.php**
   - Tambah cek role di awal file
   - Tambah modal form tambah user
   - Update tampilan tombol aksi berdasarkan role
   - Update mapping field database

2. **api/user.php**
   - Tambah hash password dengan bcrypt
   - Tambah validasi input
   - Update error handling

3. **auth/login_process.php**
   - Support verifikasi password bcrypt
   - Backward compatibility dengan password plain text
   - Simpan role ke session

## 🎯 Cara Penggunaan

### Sebagai Admin:
1. Login dengan akun admin
2. Buka menu "Data Kos"
3. Klik tombol "Tambah User"
4. Isi form dengan lengkap
5. Pilih role: Admin atau Penghuni Kos
6. Klik "Simpan User"

### Sebagai Penghuni Kos:
1. Login dengan akun penghuni kos
2. Buka menu "Data Kos"
3. Hanya bisa melihat data user
4. Tombol edit & delete tidak aktif

## 🗄️ Database Schema

Sesuai dengan table `user`:
```sql
- id_user (bigint, PK)
- nama (varchar)
- nomor (varchar)         -- No. HP/WA
- alamat (varchar)
- ktp_ktm (varchar)
- email (varchar)
- role (varchar)          -- 'admin' atau 'penghuni kos'
- username (varchar)
- password (varchar)      -- Bcrypt hash
- telegram_id (varchar)
```

## 🎨 UI/UX

### Modal Form
- Responsive layout dengan 2 kolom
- Animasi smooth (fade in & slide down)
- Validasi real-time
- Loading state saat submit
- Alert sukses/error

### Tombol Aksi
- Detail (Biru): Semua user bisa akses
- Edit (Hijau): Hanya admin
- Delete (Merah): Hanya admin, tidak bisa hapus admin

## 🧪 Testing

Untuk testing fitur:

1. **Test Admin Access:**
   ```
   Login sebagai admin → Cek tombol "Tambah User" muncul
   Tambah user baru → Verifikasi data tersimpan dengan password ter-hash
   Edit/Delete user → Verifikasi berhasil
   ```

2. **Test Penghuni Kos Access:**
   ```
   Login sebagai penghuni kos → Tombol "Tambah User" tidak muncul
   Coba edit/delete → Tombol disabled
   Cek detail user → Bisa diakses
   ```

3. **Test Password:**
   ```
   Buat user baru dengan password → Cek di database (harus ter-hash)
   Login dengan user baru → Verifikasi berhasil
   ```

## 🚀 Next Steps (Opsional)

- [ ] Tambah fitur edit user via modal
- [ ] Tambah filter/search user
- [ ] Tambah pagination untuk data banyak
- [ ] Tambah export data user (Excel/PDF)
- [ ] Tambah assign kamar ke penghuni
- [ ] Tambah log aktivitas user

## 📞 Troubleshooting

### Modal tidak muncul?
- Cek console browser untuk error JavaScript
- Pastikan sudah login sebagai admin

### Password tidak bisa login?
- Cek hash password di database
- Verifikasi backward compatibility aktif

### Tombol disabled untuk admin?
- Cek session role: `print_r($_SESSION)`
- Pastikan role di database = 'admin' (lowercase)

---

**Update:** 14 Desember 2025
**Version:** 1.0
