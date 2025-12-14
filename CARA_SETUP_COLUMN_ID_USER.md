# 🔧 Cara Setup Column id_user di Table Kamar

## ❌ Error yang Terjadi

```
Gagal assign penghuni: Could not find the 'id_user' column of 'kamar' in the schema cache
```

**Penyebab:** Column `id_user` belum ada di table `kamar` di database Supabase.

---

## ✅ Solusi: Tambahkan Column id_user

### Step 1: Buka SQL Editor di Supabase

1. Login ke [Supabase Dashboard](https://supabase.com/dashboard)
2. Pilih project **Costbun**
3. Klik menu **"SQL Editor"** di sidebar kiri
4. Klik **"New query"**

### Step 2: Jalankan SQL Query Ini

```sql
-- Add column id_user to kamar table
ALTER TABLE public.kamar 
ADD COLUMN IF NOT EXISTS id_user BIGINT;

-- Add foreign key constraint to user table
ALTER TABLE public.kamar 
ADD CONSTRAINT kamar_id_user_fkey 
FOREIGN KEY (id_user) 
REFERENCES public.user(id_user) 
ON DELETE SET NULL;

-- Add comment to column
COMMENT ON COLUMN public.kamar.id_user IS 'ID penghuni yang menempati kamar ini';

-- Create index for better query performance
CREATE INDEX IF NOT EXISTS idx_kamar_id_user ON public.kamar(id_user);
```

### Step 3: Klik "Run" atau Tekan Ctrl+Enter

Tunggu sampai muncul pesan **"Success"** di bagian bawah editor.

### Step 4: Verify Column Berhasil Ditambahkan

1. Klik menu **"Table Editor"** di sidebar
2. Pilih table **"kamar"**
3. Pastikan kolom **"id_user"** sudah muncul di list kolom
4. Kolom ini:
   - **Type:** int8 (bigint)
   - **Nullable:** Yes (bisa NULL)
   - **Default:** NULL
   - **Foreign Key:** → user.id_user

### Step 5: Test Assign Penghuni

1. Kembali ke halaman **Data Kamar**: http://costbun.test/index.php?page=data_kamar
2. Klik tombol **"Assign Penghuni"** pada salah satu kamar
3. Pilih penghuni dari dropdown
4. Klik **"Simpan"**
5. ✅ Seharusnya berhasil tanpa error!

---

## 📋 Penjelasan SQL

| SQL Statement | Fungsi |
|---------------|--------|
| `ALTER TABLE ... ADD COLUMN` | Menambahkan kolom baru ke table |
| `IF NOT EXISTS` | Hanya tambahkan jika belum ada (aman dijalankan ulang) |
| `BIGINT` | Tipe data untuk menyimpan ID user (integer besar) |
| `FOREIGN KEY` | Membuat relasi ke table user |
| `REFERENCES user(id_user)` | Kolom ini mereferensi id_user di table user |
| `ON DELETE SET NULL` | Jika user dihapus, id_user di kamar jadi NULL (kamar kosong) |
| `CREATE INDEX` | Membuat index untuk query lebih cepat |

---

## ⚠️ Troubleshooting

### Error: "column already exists"

**Penyebab:** Kolom sudah pernah ditambahkan sebelumnya.

**Solusi:**
1. Check di Table Editor → kamar → apakah kolom id_user sudah ada
2. Jika sudah ada tapi masih error, coba **Restart Project**:
   - Settings → General → Restart project
3. Clear browser cache dan refresh halaman aplikasi

### Error: "permission denied"

**Penyebab:** Akun Anda tidak punya permission untuk ALTER TABLE.

**Solusi:**
1. Pastikan Anda login sebagai **owner project**
2. Atau gunakan **service_role key** di config, bukan anon key

### Error: "relation user does not exist"

**Penyebab:** Table `user` belum dibuat atau nama table salah.

**Solusi:**
1. Check di Table Editor apakah table bernama `user` ada
2. Jika nama table lain (misalnya `users`), ganti di SQL:
   ```sql
   REFERENCES public.users(id_user)
   ```

### Masih Error Setelah Add Column

**Solusi:**
1. **Restart Supabase Project:**
   - Dashboard → Settings → General
   - Scroll ke bawah → Restart project
   - Tunggu 2-3 menit

2. **Clear Browser Cache:**
   - Tekan Ctrl+Shift+Delete
   - Clear cache dan cookies
   - Refresh halaman

3. **Clear Supabase Schema Cache:**
   ```sql
   -- Jalankan di SQL Editor
   SELECT pg_catalog.pg_advisory_unlock_all();
   ```

4. **Check PHP Error Log:**
   - Buka Laragon → Menu → PHP → Error Log
   - Lihat apakah ada error dari Supabase request

---

## 🎯 Verifikasi Berhasil

Anda tahu setup berhasil jika:

✅ SQL query sukses dijalankan tanpa error  
✅ Column `id_user` muncul di Table Editor → kamar  
✅ Assign penghuni berhasil tanpa error "could not find column"  
✅ Status kamar berubah menjadi "TERISI"  
✅ Nama penghuni muncul di card kamar  
✅ Data tersimpan permanent (tidak hilang setelah refresh)

---

## 📚 Referensi

- **Supabase ALTER TABLE:** https://supabase.com/docs/guides/database/tables
- **Foreign Keys:** https://supabase.com/docs/guides/database/joins-and-nesting
- **Indexes:** https://supabase.com/docs/guides/database/postgres/indexes

---

## 🔗 Quick Links

- [Setup Column Page](http://costbun.test/setup_id_user_column.php)
- [Test Update Kamar](http://costbun.test/test_update_kamar.php)
- [Data Kamar](http://costbun.test/index.php?page=data_kamar)
- [Supabase Dashboard](https://supabase.com/dashboard)

---

**Terakhir diupdate:** 15 Desember 2025
