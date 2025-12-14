# 📊 Data Dummy Penghuni Kos

## Overview

Script untuk insert **20 data penghuni kos** dummy ke database Supabase telah dibuat dan siap digunakan.

## 📁 File Script

**File:** `insert_penghuni_kos.php`

**Akses:** http://costbun.test/insert_penghuni_kos.php

## 👥 Data Penghuni Kos (20 Data)

### 📋 Struktur Data

Setiap penghuni memiliki data lengkap:
- ✅ Nama lengkap
- ✅ Nomor HP/WA
- ✅ Alamat lengkap
- ✅ KTP/KTM (16 digit)
- ✅ Email unik
- ✅ Role: **"penghuni kos"**
- ✅ Username unik
- ✅ Password ter-hash (bcrypt)
- ✅ Telegram ID

### 👤 Daftar Penghuni

| No | Nama | Username | Email | No. HP |
|----|------|----------|-------|--------|
| 1 | Rizki Ramadhan | rizki_r | rizki.ramadhan@email.com | 081234567001 |
| 2 | Putri Amelia | putri_a | putri.amelia@email.com | 081234567002 |
| 3 | Fajar Nugroho | fajar_n | fajar.nugroho@email.com | 081234567003 |
| 4 | Sinta Dewi | sinta_d | sinta.dewi@email.com | 081234567004 |
| 5 | Arief Budiman | arief_b | arief.budiman@email.com | 081234567005 |
| 6 | Diana Permata | diana_p | diana.permata@email.com | 081234567006 |
| 7 | Hendra Saputra | hendra_s | hendra.saputra@email.com | 081234567007 |
| 8 | Indah Lestari | indah_l | indah.lestari@email.com | 081234567008 |
| 9 | Joko Susanto | joko_s | joko.susanto@email.com | 081234567009 |
| 10 | Kartika Sari | kartika_s | kartika.sari@email.com | 081234567010 |
| 11 | Lukman Hakim | lukman_h | lukman.hakim@email.com | 081234567011 |
| 12 | Maya Anggraini | maya_a | maya.anggraini@email.com | 081234567012 |
| 13 | Nurdin Halim | nurdin_h | nurdin.halim@email.com | 081234567013 |
| 14 | Olivia Tan | olivia_t | olivia.tan@email.com | 081234567014 |
| 15 | Prasetyo Adi | prasetyo_a | prasetyo.adi@email.com | 081234567015 |
| 16 | Qonita Zahira | qonita_z | qonita.zahira@email.com | 081234567016 |
| 17 | Rahmat Hidayat | rahmat_h | rahmat.hidayat@email.com | 081234567017 |
| 18 | Sri Mulyani | sri_m | sri.mulyani@email.com | 081234567018 |
| 19 | Taufik Rahman | taufik_r | taufik.rahman@email.com | 081234567019 |
| 20 | Vania Putri | vania_p | vania.putri@email.com | 081234567020 |

## 🔑 Login Credentials

### Pattern Password
Semua penghuni menggunakan pola password yang sama:
```
Password = [username] + "123"
```

### Contoh Login:
- **Username:** `rizki_r` → **Password:** `rizki123`
- **Username:** `putri_a` → **Password:** `putri123`
- **Username:** `fajar_n` → **Password:** `fajar123`
- dst...

### 🔐 Keamanan
- ✅ Password di-hash menggunakan **bcrypt** (PASSWORD_BCRYPT)
- ✅ Hash format: `$2y$10$...` (60 karakter)
- ✅ Tidak ada password plain text tersimpan

## 🌍 Data Geografis

Data penghuni berasal dari berbagai kota di Indonesia:

| Kota | Jumlah |
|------|--------|
| Jakarta | 1 |
| Bandung | 2 |
| Semarang | 1 |
| Surabaya | 1 |
| Yogyakarta | 1 |
| Malang | 1 |
| Solo | 1 |
| Medan | 1 |
| Palembang | 1 |
| Denpasar | 1 |
| Makassar | 1 |
| Bogor | 1 |
| Pontianak | 1 |
| Batam | 1 |
| Samarinda | 1 |
| Tangerang | 1 |
| Bekasi | 1 |
| Depok | 1 |
| Cirebon | 1 |

## 🚀 Cara Penggunaan

### Method 1: Via Browser
1. Buka browser
2. Akses: http://costbun.test/insert_penghuni_kos.php
3. Tunggu proses insert selesai
4. Lihat summary hasil

### Method 2: Via Command Line
```bash
php insert_penghuni_kos.php
```

### Verifikasi Data
Setelah insert, verifikasi dengan:
1. Buka: http://costbun.test/index.php?page=data_kos
2. Atau: http://costbun.test/test_user_management.php

## 📊 Fitur Script

### 1. Real-time Progress
- Menampilkan progress insert per penghuni
- Status success/error per data
- Loading animation

### 2. Summary Report
- Total berhasil
- Total gagal
- Success rate percentage
- Detail error (jika ada)

### 3. Error Handling
- Try-catch mechanism
- Delay antar insert (0.2 detik)
- Rate limit prevention
- Detail error message

### 4. Responsive UI
- Modern gradient design
- Card-based layout
- Color-coded status
- Mobile friendly

## 🗄️ Database Impact

### Table: `user`
```sql
+20 rows penghuni kos
Role: 'penghuni kos'
Status: Active
Password: Bcrypt hashed
```

### Storage Size
Estimasi: ~2 KB per record = ~40 KB total

## 🧪 Testing Checklist

Setelah insert data, test hal berikut:

- [ ] Login dengan salah satu akun penghuni
- [ ] Cek akses halaman data kos (read-only)
- [ ] Verifikasi tombol edit/delete disabled
- [ ] Test filter/search (jika ada)
- [ ] Cek data di Supabase dashboard
- [ ] Test API endpoint GET user

## 🔄 Reset Data

Jika perlu reset dan insert ulang:

### Option 1: Via Supabase Dashboard
1. Login ke Supabase
2. Buka Table Editor → user
3. Filter: `role = 'penghuni kos'`
4. Delete selected rows
5. Run script lagi

### Option 2: Via SQL
```sql
DELETE FROM public.user 
WHERE role = 'penghuni kos';
```

### Option 3: Via Script (Create new)
Buat file `delete_penghuni_kos.php`:
```php
<?php
require_once 'config/supabase_helper.php';
$users = getUser();
foreach ($users as $user) {
    if ($user['role'] == 'penghuni kos') {
        deleteUser($user['id_user']);
    }
}
?>
```

## 📈 Next Steps

Data dummy ini bisa digunakan untuk:

1. **Testing fitur manajemen user**
   - CRUD operations
   - Role-based access
   - Search & filter

2. **Testing fitur lain**
   - Assign kamar ke penghuni
   - Generate tagihan
   - Kirim notifikasi
   - Buat laporan

3. **Demo aplikasi**
   - Presentasi ke client
   - User acceptance testing
   - Training pengguna

## 📞 Troubleshooting

### Error: "Duplicate key value"
**Solusi:** Data sudah ada, hapus dulu atau ubah username/email

### Error: "Connection timeout"
**Solusi:** Cek koneksi internet dan API key Supabase

### Error: "Password hash failed"
**Solusi:** Pastikan PHP version >= 5.5.0

### Success rate < 100%
**Solusi:** 
- Cek error detail di summary
- Verifikasi constraint database
- Cek log Supabase

## 🎯 Tips

1. **Jangan run script berulang kali** tanpa hapus data lama (akan error duplicate)
2. **Backup database** sebelum insert data besar
3. **Gunakan pagination** jika data sangat banyak
4. **Monitor performa** saat insert banyak data

## 📝 Change Log

**Version 1.0** - 14 Desember 2025
- ✅ 20 data penghuni kos
- ✅ Password bcrypt
- ✅ Data geografis Indonesia
- ✅ UI modern & responsive
- ✅ Error handling
- ✅ Summary report

---

**File:** insert_penghuni_kos.php  
**Author:** System  
**Date:** 14 Desember 2025  
**Status:** ✅ Ready to use
