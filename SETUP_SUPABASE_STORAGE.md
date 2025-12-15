# ⚠️ SETUP WAJIB: Supabase Storage Bucket Policy

## Masalah
Gambar bukti pembayaran **TIDAK MUNCUL** di web karena bucket Supabase masih **PRIVATE**.

## Solusi (WAJIB DILAKUKAN!)

### Step-by-Step Setup Supabase Storage

#### 1️⃣ Login ke Supabase
- Buka: https://supabase.com/dashboard
- Login dengan akun Anda
- Pilih project: **plngxhsvzrztfqnivztt**

#### 2️⃣ Masuk ke Storage
- Di sidebar kiri, klik **"Storage"**
- Akan muncul list buckets
- Klik bucket **"uploads"**

#### 3️⃣ Set Bucket Policy (PILIH SALAH SATU)

**Cara A: Set Public Bucket (Termudah)**
```
1. Klik bucket "uploads"
2. Klik icon gear/settings (⚙️) di pojok kanan atas
3. Di bagian "Public bucket", toggle ON
4. Klik "Save"
```

**Cara B: Buat Read Policy (Recommended)**
```
1. Klik bucket "uploads"
2. Klik tab "Policies"
3. Klik "New Policy"
4. Pilih "For SELECT operations"
5. Policy name: "Public Read Access"
6. Target roles: public (anon)
7. Using expression: true
8. Klik "Review" → "Save Policy"
```

**Cara C: SQL Manual (Advanced)**
```sql
-- Jalankan di SQL Editor
CREATE POLICY "Public Access"
ON storage.objects FOR SELECT
USING ( bucket_id = 'uploads' );
```

#### 4️⃣ Verifikasi
Test akses URL di browser (ganti dengan URL asli dari database):
```
https://plngxhsvzrztfqnivztt.supabase.co/storage/v1/object/public/uploads/pembayaran/1765743021456.jpg
```

**Jika berhasil:** Gambar akan tampil ✅  
**Jika gagal (403):** Bucket masih private, ulangi langkah 3 ❌

## Cara Cepat Testing

1. Buka di browser: http://costbun.test/test_storage_access.php
2. Lihat hasil test HTTP response code
3. Jika **200 OK** → Berhasil ✅
4. Jika **403 Forbidden** → Bucket masih private ❌

## Screenshot Panduan

### Lokasi Storage di Supabase Dashboard
```
Dashboard → Sidebar Kiri → Storage → uploads
```

### Tab Policies
```
uploads → Policies → New Policy → Allow public SELECT
```

### Public Bucket Toggle
```
uploads → Settings (⚙️) → Public bucket: ON
```

## Setelah Setup Berhasil

✅ Gambar akan tampil di:
- Halaman **Bukti Pembayaran** (admin)
- Halaman **Profil** penghuni
- Modal detail tagihan

✅ Fitur yang bisa digunakan:
- Upload bukti pembayaran dari profil penghuni
- View gambar bukti
- Approve/reject bukti pembayaran

## Troubleshooting

**Q: Sudah set public tapi gambar masih tidak muncul?**  
A: Clear browser cache atau test dengan incognito mode

**Q: Error 403 Forbidden?**  
A: Bucket policy belum benar, pastikan policy untuk "SELECT" sudah dibuat

**Q: Error 404 Not Found?**  
A: File tidak ada atau URL salah, cek database untuk URL yang benar

**Q: Bagaimana cara re-upload gambar yang lama?**  
A: 
1. Login sebagai penghuni
2. Buka Profil
3. Pada tagihan yang ingin diupdate, klik "Ganti Bukti Pembayaran"
4. Upload gambar baru

---

**PENTING:** Tanpa setup ini, **SEMUA GAMBAR TIDAK AKAN TAMPIL!**

Lakukan setup ini **SEBELUM** test upload bukti pembayaran.
