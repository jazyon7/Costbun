# 🔧 FIX: Bukti Pembayaran Tidak Muncul

## Masalah yang Ditemukan

1. ✅ **Data bukti pembayaran ADA di database Supabase**
2. ✅ **URL sudah tersimpan dengan format yang benar**
3. ❌ **Gambar tidak tampil karena Supabase Storage bucket belum PUBLIC**

## Solusi yang Sudah Diterapkan

### 1. API Upload Bukti Pembayaran ✅
File: `api/upload_bukti.php`
- Endpoint untuk upload bukti pembayaran dari penghuni
- Validasi file (JPG, PNG, max 2MB)
- Upload ke Supabase Storage bucket `uploads/pembayaran`
- Update status tagihan menjadi "pending"
- Notifikasi ke admin

### 2. Halaman Profil dengan Upload Bukti ✅
File: `pages/profil.php`
- Section tagihan untuk penghuni (non-admin)
- Tombol upload bukti pembayaran per tagihan
- Preview gambar sebelum upload
- View bukti pembayaran yang sudah diupload

### 3. Display Gambar di Halaman Bukti Pembayaran ✅
File: `pages/bukti_pembayaran.php`
- Sudah menggunakan URL langsung dari Supabase
- Icon untuk lihat bukti
- Link ke gambar full size

## LANGKAH PENTING: Setup Supabase Storage Bucket 🔐

### **⚠️ WAJIB DILAKUKAN agar gambar bisa tampil!**

1. **Buka Supabase Dashboard**
   - Login ke https://supabase.com
   - Pilih project: `plngxhsvzrztfqnivztt`

2. **Masuk ke Storage**
   - Klik menu **Storage** di sidebar kiri
   - Pilih bucket **"uploads"**

3. **Set Bucket ke PUBLIC**
   
   **Opsi A: Via Bucket Settings**
   - Klik bucket "uploads"
   - Klik tombol **"Settings"** atau **"Configuration"**
   - Centang **"Public bucket"**
   - Save

   **Opsi B: Via Policies (Recommended)**
   - Klik bucket "uploads"
   - Klik tab **"Policies"**
   - Klik **"New Policy"**
   - Pilih template **"Allow public access to bucket"**
   - Atau buat custom policy:
   
   ```sql
   -- Policy name: Public Read Access
   -- Action: SELECT
   CREATE POLICY "Public read access"
   ON storage.objects
   FOR SELECT
   USING (bucket_id = 'uploads');
   ```

4. **Verifikasi**
   - Coba akses URL gambar di browser:
     ```
     https://plngxhsvzrztfqnivztt.supabase.co/storage/v1/object/public/uploads/pembayaran/1765743021456.jpg
     ```
   - Jika berhasil, gambar akan tampil
   - Jika 403 Forbidden, bucket masih private

## Cara Testing

### 1. Test Akses Storage
```bash
# Jalankan di browser
http://costbun.test/test_storage_access.php
```
Script ini akan:
- ✅ Cek data bukti pembayaran di database
- ✅ Test load gambar dari Supabase
- ✅ Verifikasi HTTP response code
- ✅ Analisis URL pattern

### 2. Test Upload (sebagai Penghuni)
1. Login sebagai penghuni (role: penghuni)
2. Buka **Profil**
3. Lihat section **"Tagihan Pembayaran Saya"**
4. Klik **"Upload Bukti Pembayaran"** pada tagihan yang belum lunas
5. Pilih gambar (JPG/PNG, max 2MB)
6. Preview akan muncul
7. Klik **Upload**
8. Status tagihan berubah menjadi **"Pending"**

### 3. Test Verifikasi (sebagai Admin)
1. Login sebagai admin
2. Buka **Bukti Pembayaran** dari menu
3. Lihat list tagihan dengan bukti
4. Klik icon 🖼️ untuk lihat bukti
5. Klik **Detail** untuk info lengkap
6. Klik **Approve** untuk setujui → status jadi **Lunas**
7. Klik **Reject** untuk tolak → bukti dihapus, status jadi **Belum Lunas**

## URL Pattern yang Benar

### Format URL Supabase Storage:
```
https://[PROJECT_ID].supabase.co/storage/v1/object/public/[BUCKET]/[FOLDER]/[FILENAME]
```

### Contoh URL yang Benar:
```
https://plngxhsvzrztfqnivztt.supabase.co/storage/v1/object/public/uploads/pembayaran/1765743021456.jpg
```

### Breakdown:
- `plngxhsvzrztfqnivztt` = Project ID
- `uploads` = Bucket name
- `pembayaran` = Folder name
- `1765743021456.jpg` = Filename (timestamp + extension)

## Troubleshooting

### ❌ Gambar tidak muncul (403 Forbidden)
**Penyebab:** Bucket Supabase masih private
**Solusi:** Set bucket "uploads" menjadi PUBLIC (lihat panduan di atas)

### ❌ Error saat upload
**Penyebab:** File terlalu besar atau format tidak didukung
**Solusi:** 
- Pastikan file JPG/PNG
- Maksimal 2MB
- Gunakan kompresi gambar jika perlu

### ❌ URL tidak tersimpan di database
**Penyebab:** Upload ke Supabase gagal
**Solusi:**
- Cek koneksi internet
- Cek API key Supabase masih valid
- Lihat error log di PHP error log

### ❌ Gambar lama tidak muncul
**Penyebab:** URL di database masih format lama
**Solusi:**
- Cek format URL di database
- Harus menggunakan `/object/public/` bukan `/object/authenticated/`
- Re-upload bukti pembayaran untuk generate URL baru

## File yang Dimodifikasi/Dibuat

### ✅ File Baru:
1. `api/upload_bukti.php` - API endpoint upload bukti
2. `test_storage_access.php` - Testing tool

### ✅ File Diupdate:
1. `pages/profil.php` - Tambah section upload bukti
2. `pages/bukti_pembayaran.php` - Display gambar dari Supabase

### ✅ File Existing (sudah OK):
1. `config/supabase_helper.php` - Function `uploadToSupabaseStorage()`
2. `api/tagihan.php` - API approve/reject bukti

## Flow Proses Bukti Pembayaran

```
PENGHUNI                                    ADMIN
   │                                          │
   ├─► Login ke sistem                       │
   │                                          │
   ├─► Buka Profil                           │
   │                                          │
   ├─► Lihat Tagihan yang Belum Lunas        │
   │                                          │
   ├─► Upload Bukti Pembayaran               │
   │   (JPG/PNG, max 2MB)                    │
   │                                          │
   ├─► Gambar di-upload ke                   │
   │   Supabase Storage                      │
   │                                          │
   ├─► URL gambar tersimpan                  │
   │   di database                           │
   │                                          │
   ├─► Status → "PENDING"                    │
   │                                          │
   ├─► Notifikasi dikirim ──────────────────►│
   │                                          │
   │                              Login sebagai Admin
   │                                          │
   │                          Buka "Bukti Pembayaran"
   │                                          │
   │                              Lihat list pending
   │                                          │
   │                              Klik "Detail"
   │                                          │
   │                              Lihat gambar bukti
   │                                          │
   │                        ┌─────────────────┴─────────────┐
   │                        │                               │
   │                    APPROVE                          REJECT
   │                        │                               │
   │                 Status → LUNAS                  Status → BELUM LUNAS
   │                        │                               │
   │                 Notifikasi                       Notifikasi
   │   ◄────────────────────┤                               │
   │  "Pembayaran Disetujui"                    "Pembayaran Ditolak"
   │                                             Bukti dihapus
```

## Testing Checklist

- [ ] Bucket Supabase "uploads" sudah PUBLIC
- [ ] URL gambar bisa diakses langsung di browser
- [ ] Test upload sebagai penghuni berhasil
- [ ] Gambar tampil di halaman Profil
- [ ] Gambar tampil di halaman Bukti Pembayaran (admin)
- [ ] Admin bisa approve bukti → status jadi Lunas
- [ ] Admin bisa reject bukti → bukti dihapus
- [ ] Notifikasi terkirim ke penghuni
- [ ] Test dengan gambar JPG berhasil
- [ ] Test dengan gambar PNG berhasil
- [ ] Test dengan file > 2MB ditolak
- [ ] Test dengan format lain (PDF, DOCX) ditolak

## Next Steps

1. ✅ Set bucket Supabase menjadi PUBLIC
2. ✅ Test upload dari web costbun.test
3. ✅ Verifikasi gambar tampil
4. ✅ Test flow approval admin
5. 📱 (Optional) Integrate dengan n8n untuk auto-notifikasi WhatsApp

---

**Author:** GitHub Copilot  
**Date:** 15 Desember 2025  
**Status:** ✅ Ready to Test
