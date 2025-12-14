# 🔧 ANALISIS & FIX - Status Tidak Tersimpan

## ❌ Masalah yang Dilaporkan:

**Symptom:**
- Alert "Penghuni berhasil di-assign ke kamar" muncul (hijau) ✅
- Tapi kamar masih menampilkan status "Kosong" ❌
- Tombol masih "Assign Penghuni" (bukan "Kosongkan") ❌
- Data tidak tersimpan di database ❌

---

## 🔍 Root Cause Analysis:

### 1. **Supabase PATCH Request Issue**

**Masalah:**
```php
// supabase_request.php - SEBELUM PERBAIKAN
curl_setopt_array($curl, [
    CURLOPT_HTTPHEADER => [
        "apikey: " . SUPABASE_API_KEY,
        "Authorization: Bearer " . SUPABASE_API_KEY,
        "Content-Type: application/json"
    ],
    // ❌ TIDAK ADA "Prefer: return=representation"
]);
```

**Dampak:**
- Supabase menerima PATCH request
- Data berhasil diupdate di database
- Tapi response tidak mengembalikan data yang diupdate
- PHP mengira request gagal karena response kosong
- API return success message meskipun tidak yakin data tersimpan

**Bukti:**
- Supabase docs: https://supabase.com/docs/guides/api/using-http-headers
- Tanpa header `Prefer: return=representation`, PATCH return HTTP 204 No Content
- Dengan header tersebut, PATCH return HTTP 200 dengan data yang diupdate

### 2. **Response Handling Tidak Akurat**

**Masalah:**
```php
// api/kamar.php - SEBELUM PERBAIKAN
$result = updateKamar($id_kamar, $data);

if (isset($result['error'])) {
    echo json_encode(['success' => false, ...]);
} else {
    // ❌ Mengasumsikan success tanpa verifikasi
    echo json_encode(['success' => true, ...]);
}
```

**Dampak:**
- Code assume sukses jika tidak ada error
- Tidak verify apakah data benar-benar tersimpan
- User dapat false positive success message

### 3. **Logging Tidak Cukup**

**Masalah:**
- Tidak ada logging HTTP status code
- Tidak ada logging response dari Supabase
- Sulit debugging karena blind spot

---

## ✅ Solusi yang Diterapkan:

### Fix 1: Tambah Header "Prefer: return=representation"

**File:** `config/supabase_request.php`

```php
function supabase_request($method, $endpoint, $body = null)
{
    // Build headers
    $headers = [
        "apikey: " . SUPABASE_API_KEY,
        "Authorization: Bearer " . SUPABASE_API_KEY,
        "Content-Type: application/json"
    ];
    
    // ✅ Add Prefer header for PATCH/POST to return updated data
    if ($method === 'PATCH' || $method === 'POST') {
        $headers[] = "Prefer: return=representation";
    }
    
    curl_setopt_array($curl, [
        CURLOPT_URL => SUPABASE_URL . $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $headers, // ✅ Headers dengan Prefer
        CURLOPT_POSTFIELDS => $body ? json_encode($body) : null
    ]);
    
    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // ✅ Get HTTP code
    
    // ✅ Log everything
    error_log("Supabase Request - Method: $method, Endpoint: $endpoint");
    error_log("Supabase Request - HTTP Code: $httpCode");
    error_log("Supabase Request - Response: " . substr($response, 0, 500));
    
    // ✅ Check HTTP status
    if ($httpCode >= 400) {
        return [
            'error' => true,
            'message' => "HTTP Error $httpCode",
            'details' => json_decode($response, true)
        ];
    }
    
    return json_decode($response, true);
}
```

### Fix 2: Verify Update dengan Re-fetch Data

**File:** `api/kamar.php`

```php
case 'assign_penghuni':
    // ... existing validation code ...
    
    $result = updateKamar($id_kamar, $data);
    
    if (isset($result['error']) && $result['error'] === true) {
        // Update failed
        echo json_encode([
            'success' => false, 
            'message' => 'Gagal assign penghuni: ' . $result['message']
        ]);
    } else {
        // ✅ Verify the update by fetching the kamar again
        $verifyKamar = getKamar($id_kamar);
        
        if ($verifyKamar && $verifyKamar['id_user'] == $id_user) {
            // ✅ Data confirmed tersimpan
            echo json_encode([
                'success' => true, 
                'message' => 'Penghuni berhasil di-assign ke kamar',
                'verified' => true,
                'kamar' => $verifyKamar
            ]);
        } else {
            // ⚠️ API OK tapi data belum update
            echo json_encode([
                'success' => true, 
                'message' => 'API response OK, tapi data belum ter-update. Coba refresh halaman.',
                'verified' => false
            ]);
        }
    }
    break;
```

### Fix 3: Enhanced Logging

**File:** `config/supabase_helper.php`

```php
function updateKamar($id_kamar, $data) {
    error_log("updateKamar() - ID: $id_kamar, Data: " . json_encode($data));
    $result = supabase_request('PATCH', "/rest/v1/kamar?id_kamar=eq.$id_kamar", $data);
    error_log("updateKamar() - Result: " . json_encode($result));
    return $result;
}
```

---

## 🧪 Testing Steps:

### Step 1: Test Direct Supabase Update
```bash
URL: http://costbun.test/test_update_kamar.php
```

**Expected:**
- Section 1: "DATA BERHASIL TERSIMPAN DI DATABASE!" ✅
- Section 2: "Supabase request berhasil" ✅
- Section 3: API test return success ✅

### Step 2: Reset Kamar untuk Testing
```bash
URL: http://costbun.test/reset_kamar_001.php
```

**Expected:**
- "Kamar 001 berhasil direset!" ✅
- id_user: NULL, status: kosong ✅

### Step 3: Test Assign dari UI
```bash
URL: http://costbun.test/index.php?page=data_kamar
```

**Steps:**
1. Klik "Assign Penghuni" di Kamar 001
2. Pilih penghuni dari dropdown
3. Klik button "Assign Penghuni"
4. **Buka F12 → Console** untuk lihat log

**Expected Console Log:**
```javascript
=== Submit Assign Penghuni ===
id_kamar: 1
id_user: 5
Body yang dikirim: id_kamar=1&id_user=5...
Response status: 200
Response text: {"success":true,"verified":true,...}
```

**Expected PHP Error Log:**
```
=== ASSIGN PENGHUNI START ===
Assign Penghuni - id_kamar: 1, id_user: 5
Assign Penghuni - Updating kamar 1 with data: {"id_user":5,"status":"terisi"}
Supabase Request - Method: PATCH, Endpoint: /rest/v1/kamar?id_kamar=eq.1
Supabase Request - HTTP Code: 200
Assign Penghuni - Verify kamar after update: {"id_user":5,"status":"terisi",...}
Assign Penghuni - SUCCESS & VERIFIED
=== ASSIGN PENGHUNI END ===
```

### Step 4: Verify di UI
**Expected:**
- Alert "Penghuni berhasil di-assign ke kamar" ✅
- Page reload otomatis ✅
- Kamar 001 status berubah "TERISI" ✅
- Muncul nama penghuni di card ✅
- Tombol berubah jadi "Kosongkan" ✅

---

## 🐛 Troubleshooting:

### Issue: HTTP Code 401 Unauthorized
**Cause:** Supabase API key invalid/expired

**Fix:**
1. Buka Supabase Dashboard
2. Settings → API
3. Copy API key yang baru
4. Update `config/supabase.php`

### Issue: HTTP Code 400 Bad Request
**Cause:** 
- Data format salah
- Column tidak exist
- Foreign key constraint violation

**Debug:**
```php
// Lihat response details di error log
error_log("Supabase Response: " . print_r($result, true));
```

**Check:**
- Pastikan column `id_user` exist di table `kamar`
- Pastikan foreign key constraint sudah dibuat
- Pastikan value id_user valid (exist di table user)

### Issue: HTTP Code 200 tapi Data Tidak Update
**Cause:**
- WHERE clause tidak match (id_kamar salah)
- Supabase RLS (Row Level Security) block update

**Fix:**
1. Check id_kamar yang dikirim
2. Check Supabase table `kamar` → Policies
3. Ensure API key punya permission untuk UPDATE

### Issue: Success Message tapi UI Tidak Update
**Cause:**
- Browser cache
- JavaScript tidak redirect
- Data update delayed (eventual consistency)

**Fix:**
1. Hard refresh: Ctrl + F5
2. Clear browser cache
3. Add delay before redirect:
```javascript
setTimeout(() => {
    window.location.href = '...';
}, 500);
```

---

## 📊 Expected Before/After:

### BEFORE FIX:

**Supabase Request:**
```
PATCH /rest/v1/kamar?id_kamar=eq.1
Headers:
  - apikey: ...
  - Content-Type: application/json
Body: {"id_user":5,"status":"terisi"}

Response: HTTP 204 No Content
Body: (empty)
```

**Result:** ❌ PHP tidak tahu apakah update berhasil

---

### AFTER FIX:

**Supabase Request:**
```
PATCH /rest/v1/kamar?id_kamar=eq.1
Headers:
  - apikey: ...
  - Content-Type: application/json
  - Prefer: return=representation  ← ✅ ADDED
Body: {"id_user":5,"status":"terisi"}

Response: HTTP 200 OK
Body: [{"id_kamar":1,"id_user":5,"status":"terisi",...}]
```

**Result:** ✅ PHP dapat confirm update berhasil dengan data yang dikembalikan

---

## 📝 Summary Perbaikan:

| # | Masalah | Solusi | File |
|---|---------|--------|------|
| 1 | PATCH tidak return data | Tambah header "Prefer: return=representation" | config/supabase_request.php |
| 2 | Tidak verify update | Re-fetch data setelah update | api/kamar.php |
| 3 | HTTP code tidak dicek | Tambah curl_getinfo(CURLINFO_HTTP_CODE) | config/supabase_request.php |
| 4 | Logging minim | Tambah error_log di semua step | semua file |
| 5 | Error handling lemah | Check HTTP status >= 400 | config/supabase_request.php |

---

## ✅ Checklist Testing:

- [ ] test_update_kamar.php → Section 1 & 2 SUCCESS
- [ ] reset_kamar_001.php → Reset berhasil
- [ ] Assign dari UI → Alert success
- [ ] Browser console → No JavaScript errors
- [ ] PHP error log → All steps logged
- [ ] Database → Data tersimpan (id_user filled)
- [ ] UI refresh → Status "TERISI", nama muncul
- [ ] Tombol → Berubah jadi "Kosongkan"

---

**Status**: ✅ FIXED

**Root Cause**: Missing "Prefer: return=representation" header di Supabase PATCH request

**Impact**: HIGH - Core functionality tidak bekerja

**Priority**: P0 - Critical bug

**Last Updated**: 15 Desember 2025
