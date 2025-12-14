# 🔧 FIX ASSIGN PENGHUNI - Troubleshooting Guide

## ❌ Masalah yang Dilaporkan:
- Saat klik nama penghuni di dropdown dan klik "Assign Penghuni", data tidak tersimpan
- Kamar tetap kosong
- Status tidak berubah ke "terisi"

---

## 🔍 Analisis Masalah:

### 1. **Flow Proses Assign:**
```
1. User klik button "Assign Penghuni" di card kamar
2. Modal terbuka dengan form (assignModal)
3. User pilih penghuni dari dropdown
4. User klik button "Assign Penghuni" (submit form)
5. JavaScript submitAssignPenghuni() dipanggil
6. FormData dikirim via fetch ke api/kamar.php?action=assign_penghuni
7. API update database via updateKamar()
8. Redirect ke halaman dengan success message
```

### 2. **Kemungkinan Masalah:**

#### A. POST Data Tidak Terbaca
- API menggunakan `parse_str(file_get_contents("php://input"))` 
- Frontend mengirim dengan `Content-Type: application/x-www-form-urlencoded`
- **Status**: ✅ Sudah diperbaiki dengan logging

#### B. Dropdown Value Kosong
- User tidak memilih penghuni (value masih "")
- **Fix**: Tambahkan validasi di JavaScript sebelum submit

#### C. ID Kamar Tidak Tersimpan di Hidden Input
- `document.getElementById('assign_id_kamar').value = idKamar;`
- **Check**: Pastikan hidden input ter-populate

#### D. Supabase Update Gagal
- updateKamar() return error
- Foreign key constraint issue
- **Check**: Lihat response dari API

---

## ✅ Perbaikan yang Diterapkan:

### 1. **File: api/kamar.php**
```php
case 'assign_penghuni':
    // Get POST data
    $rawInput = file_get_contents("php://input");
    parse_str($rawInput, $input);
    
    // Log untuk debugging
    error_log("Assign Penghuni - Raw input: " . $rawInput);
    error_log("Assign Penghuni - Parsed input: " . print_r($input, true));
    
    $id_kamar = $input['id_kamar'] ?? null;
    $id_user = $input['id_user'] ?? null;
    
    error_log("Assign Penghuni - id_kamar: $id_kamar, id_user: $id_user");
    
    if (!$id_kamar || !$id_user) {
        echo json_encode([
            'success' => false, 
            'message' => 'Data tidak lengkap (id_kamar: ' . ($id_kamar ? 'OK' : 'NULL') . ', id_user: ' . ($id_user ? 'OK' : 'NULL') . ')'
        ]);
        exit;
    }
    
    // Update kamar
    $data = [
        'id_user' => (int)$id_user,
        'status' => 'terisi'
    ];
    
    error_log("Assign Penghuni - Updating kamar with data: " . print_r($data, true));
    
    $result = updateKamar($id_kamar, $data);
    
    error_log("Assign Penghuni - Update result: " . print_r($result, true));
    
    if (isset($result['error'])) {
        echo json_encode(['success' => false, 'message' => 'Gagal assign penghuni: ' . $result['error']]);
    } else {
        echo json_encode(['success' => true, 'message' => 'Penghuni berhasil di-assign ke kamar']);
    }
    break;
```

### 2. **File: pages/data_kamar.php (JavaScript)**
```javascript
function submitAssignPenghuni(event) {
    event.preventDefault();
    
    const form = event.target;
    const submitBtn = form.querySelector('.btn-submit');
    const formData = new FormData(form);
    
    // Debugging: Log form data
    console.log('=== Submit Assign Penghuni ===');
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }
    
    const id_kamar = formData.get('id_kamar');
    const id_user = formData.get('id_user');
    
    console.log('id_kamar:', id_kamar);
    console.log('id_user:', id_user);
    
    // VALIDASI: Cek apakah user sudah dipilih
    if (!id_user || id_user === '') {
        alert('❌ Silakan pilih penghuni terlebih dahulu!');
        return;
    }
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    
    const params = new URLSearchParams();
    for (let [key, value] of formData.entries()) {
        params.append(key, value);
    }
    
    console.log('Body yang dikirim:', params.toString());
    
    fetch('api/kamar.php?action=assign_penghuni', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: params.toString()
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.text();
    })
    .then(text => {
        console.log('Response text:', text);
        try {
            const data = JSON.parse(text);
            console.log('Response parsed:', data);
            
            if (data.success) {
                alert('✅ Penghuni berhasil di-assign ke kamar!');
                window.location.href = 'index.php?page=data_kamar&msg=' + encodeURIComponent(data.message);
            } else {
                alert('❌ Gagal assign penghuni: ' + data.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Assign Penghuni';
            }
        } catch (e) {
            console.error('JSON parse error:', e);
            console.error('Response was:', text);
            alert('❌ Terjadi kesalahan: Response tidak valid');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Assign Penghuni';
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert('❌ Terjadi kesalahan: ' + error.message);
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Assign Penghuni';
    });
}
```

---

## 🧪 Testing Steps:

### Step 1: Test Menggunakan Debug Page
1. Buka: **http://costbun.test/debug_assign_penghuni.php**
2. Di section "2. Test dengan Data Real":
   - Pilih kamar dari dropdown
   - Pilih penghuni dari dropdown
   - Klik "Test Assign (Real Data)"
3. Lihat response:
   - ✅ Jika success: `{"success": true, "message": "..."}`
   - ❌ Jika gagal: Lihat error message

### Step 2: Cek Browser Console
1. Buka: **http://costbun.test/index.php?page=data_kamar**
2. Tekan **F12** untuk buka Developer Tools
3. Pilih tab **Console**
4. Klik button "Assign Penghuni" di salah satu kamar
5. Pilih penghuni dari dropdown
6. Klik "Assign Penghuni"
7. **Lihat console log:**
   ```
   === Submit Assign Penghuni ===
   id_kamar: 1
   id_user: 5
   tanggal_mulai: 2025-12-15
   Body yang dikirim: id_kamar=1&id_user=5&tanggal_mulai=2025-12-15
   Response status: 200
   Response text: {"success":true,"message":"..."}
   Response parsed: {success: true, message: "..."}
   ```

### Step 3: Cek PHP Error Log
1. Buka Laragon
2. Menu → Log → PHP Error Log
3. Cari log dengan prefix "Assign Penghuni":
   ```
   Assign Penghuni - Raw input: id_kamar=1&id_user=5&tanggal_mulai=2025-12-15
   Assign Penghuni - Parsed input: Array([id_kamar] => 1 [id_user] => 5 ...)
   Assign Penghuni - id_kamar: 1, id_user: 5
   Assign Penghuni - Updating kamar with data: Array([id_user] => 5 [status] => terisi)
   Assign Penghuni - Update result: Array(...)
   ```

### Step 4: Verify Database
1. Buka Supabase Dashboard
2. Table Editor → kamar
3. Cari kamar yang di-assign
4. Kolom `id_user` harus terisi dengan ID penghuni
5. Kolom `status` harus berubah jadi "terisi"

---

## 🐛 Troubleshooting:

### Error: "Data tidak lengkap"
**Kemungkinan:**
- Dropdown penghuni tidak dipilih (value kosong)
- Hidden input id_kamar tidak ter-populate

**Solusi:**
1. Cek console log untuk nilai id_kamar dan id_user
2. Pastikan dropdown penghuni ada value-nya
3. Inspect element modal, cek value hidden input

### Error: "Gagal assign penghuni"
**Kemungkinan:**
- Supabase update gagal
- Foreign key constraint violation
- Network issue

**Solusi:**
1. Cek PHP error log untuk detail error
2. Cek apakah id_user valid (ada di table user)
3. Cek apakah foreign key constraint sudah dibuat
4. Test direct API di debug_assign_penghuni.php

### Data Tersimpan Tapi UI Tidak Update
**Kemungkinan:**
- Browser cache
- Redirect tidak jalan

**Solusi:**
1. Hard refresh (Ctrl + F5)
2. Clear browser cache
3. Cek apakah ada JavaScript error yang block redirect

### Dropdown Kosong
**Kemungkinan:**
- Tidak ada data penghuni
- Query user error

**Solusi:**
1. Cek database, pastikan ada user dengan role "penghuni kos"
2. Jalankan insert_penghuni_kos.php jika belum ada
3. Cek query di pages/data_kamar.php

---

## 📊 Expected Behavior:

### Before Assign:
```
Kamar Card:
┌─────────────────────────────┐
│     Kamar 001               │
├─────────────────────────────┤
│ 🛏️ Kasur: 1                │
│ 💨 AC: 1                    │
│ 💰 Rp 500,000               │
├─────────────────────────────┤
│ [Status: KOSONG]            │
├─────────────────────────────┤
│ [➕ Assign Penghuni]        │
└─────────────────────────────┘
```

### After Assign:
```
Kamar Card:
┌─────────────────────────────┐
│     Kamar 001               │
├─────────────────────────────┤
│ 🛏️ Kasur: 1                │
│ 💨 AC: 1                    │
│ 💰 Rp 500,000               │
├─────────────────────────────┤
│ [Status: TERISI]            │
├─────────────────────────────┤
│ 👤 Rizki Ramadhan           │
├─────────────────────────────┤
│ [➖ Kosongkan]              │
└─────────────────────────────┘
```

### Database Changes:
```sql
-- Before:
id_kamar | nama      | id_user | status
---------|-----------|---------|--------
1        | Kamar 001 | NULL    | kosong

-- After:
id_kamar | nama      | id_user | status
---------|-----------|---------|--------
1        | Kamar 001 | 5       | terisi
```

---

## 🎯 Checklist Debugging:

- [ ] Dropdown penghuni muncul dengan data
- [ ] Hidden input id_kamar ter-populate saat modal dibuka
- [ ] Console log menampilkan data yang dikirim
- [ ] Network tab (F12) menunjukkan POST request ke api/kamar.php
- [ ] Response status 200 OK
- [ ] Response JSON valid dengan success: true
- [ ] PHP error log menunjukkan data ter-parse
- [ ] Database ter-update dengan benar
- [ ] UI refresh dan menampilkan perubahan
- [ ] Alert success muncul

---

## 📝 Next Steps Jika Masih Error:

1. **Buka debug_assign_penghuni.php** dan test API langsung
2. **Screenshot browser console** saat submit form
3. **Copy paste PHP error log** yang relevan
4. **Export data kamar** sebelum dan sesudah assign
5. **Test dengan ID manual** (id_kamar=1, id_user=1)

---

**Status**: ✅ DEBUGGING TOOLS READY

**Files Modified:**
- ✅ api/kamar.php (added logging)
- ✅ pages/data_kamar.php (added validation & logging)
- ✅ debug_assign_penghuni.php (new test page)

**Last Updated**: 15 Desember 2025
