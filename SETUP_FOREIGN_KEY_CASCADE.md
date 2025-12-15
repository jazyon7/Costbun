# 🔧 FIX: Foreign Key Constraint Error (ON DELETE CASCADE)

## Masalah

Saat menghapus user dari Supabase Dashboard atau via API, muncul error:

```
Unable to delete rows as one of them is currently referenced by a foreign key constraint 
from the table `tagihan`
DETAIL: Key (id_user)=(72) is still referenced from table tagihan.
```

**Root Cause:** Foreign key constraints di database Supabase tidak di-set dengan `ON DELETE CASCADE`, jadi saat user dihapus, database tidak tahu apa yang harus dilakukan dengan data terkait di tabel `notifikasi`, `tagihan`, dan `laporan`.

---

## Solusi

### ⚡ Solusi 1: Set ON DELETE CASCADE di Database (RECOMMENDED)

Ini adalah solusi terbaik karena database akan otomatis menangani penghapusan data terkait.

#### Langkah-langkah:

1. **Buka Supabase Dashboard**
   - Login ke https://supabase.com
   - Pilih project: `plngxhsvzrztfqnivztt`

2. **Buka SQL Editor**
   - Di sidebar kiri, klik **"SQL Editor"**
   - Klik **"New Query"**

3. **Copy-Paste SQL Script**
   - Buka file: `sql/fix_foreign_key_cascade.sql`
   - Copy semua isi file
   - Paste ke SQL Editor

4. **Execute SQL**
   - Klik tombol **"Run"** atau tekan `Ctrl+Enter`
   - Tunggu hingga selesai

5. **Verifikasi**
   - Scroll ke bawah di SQL Editor
   - Akan muncul hasil query verification
   - Pastikan `delete_rule` untuk semua foreign key:
     - `notifikasi` → CASCADE
     - `tagihan` → CASCADE
     - `laporan` → CASCADE
     - `kamar` → SET NULL

#### SQL Script (ada di file sql/fix_foreign_key_cascade.sql):

```sql
-- Drop existing constraints
ALTER TABLE notifikasi DROP CONSTRAINT IF EXISTS notifikasi_id_user_fkey;
ALTER TABLE tagihan DROP CONSTRAINT IF EXISTS tagihan_id_user_fkey;
ALTER TABLE laporan DROP CONSTRAINT IF EXISTS laporan_id_user_fkey;
ALTER TABLE kamar DROP CONSTRAINT IF EXISTS kamar_id_user_fkey;

-- Recreate with ON DELETE CASCADE
ALTER TABLE notifikasi ADD CONSTRAINT notifikasi_id_user_fkey 
FOREIGN KEY (id_user) REFERENCES "user"(id_user) ON DELETE CASCADE;

ALTER TABLE tagihan ADD CONSTRAINT tagihan_id_user_fkey 
FOREIGN KEY (id_user) REFERENCES "user"(id_user) ON DELETE CASCADE;

ALTER TABLE laporan ADD CONSTRAINT laporan_id_user_fkey 
FOREIGN KEY (id_user) REFERENCES "user"(id_user) ON DELETE CASCADE;

ALTER TABLE kamar ADD CONSTRAINT kamar_id_user_fkey 
FOREIGN KEY (id_user) REFERENCES "user"(id_user) ON DELETE SET NULL;
```

---

### 🔄 Solusi 2: Manual Delete via PHP (BACKUP)

Kode PHP sudah di-update untuk menghapus data terkait secara manual. Ini adalah backup jika Solusi 1 tidak diterapkan.

**File:** `api/user.php` (sudah diperbaiki)

```php
// Urutan delete untuk avoid foreign key error:
STEP 1: Delete notifikasi terkait user
STEP 2: Delete tagihan terkait user
STEP 3: Delete laporan terkait user
STEP 4: Delete foto from Supabase Storage
STEP 5: Delete user from database
STEP 6: Set kamar menjadi kosong
```

---

## Perbedaan 2 Solusi

| Aspek | Solusi 1 (ON DELETE CASCADE) | Solusi 2 (Manual PHP) |
|-------|------------------------------|----------------------|
| **Performance** | ⚡ Lebih cepat (1 query) | 🐌 Lebih lambat (multiple queries) |
| **Reliability** | ✅ Database handle otomatis | ⚠️ Tergantung kode PHP |
| **Maintenance** | ✅ Simple & clean | ⚠️ Harus maintain kode |
| **Delete dari Dashboard** | ✅ Bisa | ❌ Tidak bisa (harus via API) |
| **Delete via API** | ✅ Otomatis cascade | ✅ Manual cascade |

**Rekomendasi:** Gunakan **Solusi 1** (ON DELETE CASCADE) karena lebih reliable dan performant.

---

## Testing

### Test 1: Delete via Supabase Dashboard

Setelah run SQL script:

1. Buka Supabase Dashboard → Table Editor
2. Pilih table **user**
3. Pilih user yang ingin dihapus (selain admin)
4. Klik **Delete**
5. Konfirmasi

**Expected Result:**
- ✅ User berhasil dihapus
- ✅ Semua notifikasi user otomatis terhapus
- ✅ Semua tagihan user otomatis terhapus
- ✅ Semua laporan user otomatis terhapus
- ✅ Kamar user menjadi NULL (kosong)

### Test 2: Delete via Web Interface

1. Buka: http://costbun.test/index.php?page=data_kos
2. Login sebagai admin
3. Klik tombol **Hapus** pada user penghuni
4. Konfirmasi

**Expected Result:**
- ✅ User berhasil dihapus
- ✅ Foto KTP terhapus dari Supabase Storage
- ✅ Data terkait otomatis terhapus (by CASCADE)
- ✅ Kamar di-set ke status 'kosong'

### Test 3: Delete via API

```bash
curl -X GET "http://costbun.test/api/user.php?action=delete&id=72"
```

**Expected Response:**
```json
{
  "success": true,
  "message": "User berhasil dihapus beserta semua data terkait"
}
```

---

## Flowchart Delete Process

### Sebelum ON DELETE CASCADE:
```
Delete User
  ↓
❌ ERROR: Foreign key constraint violated!
  ↓
❌ Harus hapus notifikasi manual
❌ Harus hapus tagihan manual
❌ Harus hapus laporan manual
  ↓
Baru bisa delete user
```

### Setelah ON DELETE CASCADE:
```
Delete User
  ↓
✅ Database otomatis hapus:
   - Notifikasi terkait
   - Tagihan terkait
   - Laporan terkait
   - Kamar di-set NULL
  ↓
✅ SUCCESS: User & semua data terkait terhapus!
```

---

## Rollback (Jika Ada Masalah)

Jika setelah run SQL ada masalah, bisa rollback dengan:

```sql
-- Restore original foreign key (tanpa CASCADE)
ALTER TABLE notifikasi DROP CONSTRAINT IF EXISTS notifikasi_id_user_fkey;
ALTER TABLE notifikasi ADD CONSTRAINT notifikasi_id_user_fkey 
FOREIGN KEY (id_user) REFERENCES "user"(id_user);

ALTER TABLE tagihan DROP CONSTRAINT IF EXISTS tagihan_id_user_fkey;
ALTER TABLE tagihan ADD CONSTRAINT tagihan_id_user_fkey 
FOREIGN KEY (id_user) REFERENCES "user"(id_user);

ALTER TABLE laporan DROP CONSTRAINT IF EXISTS laporan_id_user_fkey;
ALTER TABLE laporan ADD CONSTRAINT laporan_id_user_fkey 
FOREIGN KEY (id_user) REFERENCES "user"(id_user);

ALTER TABLE kamar DROP CONSTRAINT IF EXISTS kamar_id_user_fkey;
ALTER TABLE kamar ADD CONSTRAINT kamar_id_user_fkey 
FOREIGN KEY (id_user) REFERENCES "user"(id_user);
```

---

## FAQ

**Q: Apa itu ON DELETE CASCADE?**
A: Ini adalah aturan di database yang membuat data terkait otomatis terhapus saat parent record dihapus.

**Q: Apakah aman menggunakan CASCADE?**
A: Ya, sangat aman untuk kasus seperti ini dimana notifikasi, tagihan, dan laporan memang seharusnya terhapus saat user dihapus.

**Q: Kenapa kamar pakai SET NULL bukan CASCADE?**
A: Karena kamar adalah entitas independen. Saat user dihapus, kamar tidak ikut terhapus, hanya jadi kosong (id_user = NULL).

**Q: Apakah data yang sudah terhapus bisa dikembalikan?**
A: Tidak. Pastikan backup data penting sebelum menghapus user.

**Q: Apakah harus run SQL script?**
A: Sangat disarankan. Tanpa ON DELETE CASCADE, delete akan selalu error kecuali via PHP API (yang sudah handle manual delete).

---

## Checklist Setup

- [ ] Run SQL script di Supabase SQL Editor
- [ ] Verifikasi foreign key constraints (cek delete_rule = CASCADE)
- [ ] Test delete user dari Supabase Dashboard
- [ ] Test delete user dari web interface
- [ ] Verifikasi data terkait ikut terhapus
- [ ] Verifikasi kamar jadi kosong (id_user = NULL)

---

**Status:** ⏳ **MENUNGGU RUN SQL SCRIPT**  
**Priority:** 🔴 **HIGH** (Blocking delete functionality)  
**Estimasi:** 2 menit untuk run script + test

Setelah run SQL script, delete user akan berfungsi sempurna baik dari Dashboard maupun via API! 🚀
