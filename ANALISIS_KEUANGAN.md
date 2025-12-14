# 📊 Analisis Kebutuhan - Fitur Keuangan

## 📋 Database Schema

### Table: `keuangan`

| Column | Type | Description |
|--------|------|-------------|
| `id_keuangan` | INTEGER | Primary key (auto increment) |
| `tanggal_tranksaksi` | DATE | Tanggal transaksi dilakukan |
| `jenis` | VARCHAR | Jenis transaksi: "pemasukan" atau "pengeluaran" |
| `keterangan` | VARCHAR | Deskripsi/keterangan transaksi |
| `jumlah` | INTEGER | Nominal uang (dalam rupiah) |
| `sumber` | VARCHAR | Sumber transaksi (misal: Pembayaran Kamar 101, Listrik, dll) |

---

## 🎯 Fitur yang Dibutuhkan

### 1. **Dashboard Summary** (Card Statistik)
- 💰 **Total Pemasukan** - Sum semua transaksi jenis "pemasukan"
- 💸 **Total Pengeluaran** - Sum semua transaksi jenis "pengeluaran"
- 💵 **Saldo** - Pemasukan - Pengeluaran
- 📊 **Jumlah Transaksi** - Total record

### 2. **List Transaksi** (Table)
- Tampilkan semua transaksi dengan:
  - Tanggal
  - Jenis (badge: hijau untuk pemasukan, merah untuk pengeluaran)
  - Keterangan
  - Sumber
  - Jumlah (format Rp.)
  - Aksi (Edit/Delete - admin only)

### 3. **Filter & Search**
- Filter by jenis: Semua / Pemasukan / Pengeluaran
- Filter by periode: Bulan ini / 3 Bulan / 6 Bulan / Tahun ini / Custom
- Search by keterangan/sumber

### 4. **Tambah Transaksi** (Admin Only)
- Modal form dengan fields:
  - Tanggal transaksi (date picker)
  - Jenis (dropdown: Pemasukan/Pengeluaran)
  - Keterangan (textarea)
  - Jumlah (number input)
  - Sumber (input text)

### 5. **Edit Transaksi** (Admin Only)
- Modal form pre-filled dengan data existing
- Update semua field kecuali id_keuangan

### 6. **Delete Transaksi** (Admin Only)
- Konfirmasi sebelum hapus
- Soft delete atau hard delete

---

## 👥 Role-Based Access Control

### 🏠 **Penghuni Kos**
- ✅ Lihat summary (read-only)
- ✅ Lihat list transaksi
- ✅ Filter & search
- ❌ Tidak ada tombol "Tambah Transaksi"
- ❌ Tidak ada tombol Edit/Delete

### 👨‍💼 **Admin**
- ✅ Semua fitur penghuni
- ✅ Tombol "Tambah Transaksi"
- ✅ Edit transaksi
- ✅ Delete transaksi
- ✅ Full CRUD access

---

## 🎨 UI Components

### 1. **Header dengan Summary Cards**
```
┌─────────────┬─────────────┬─────────────┬─────────────┐
│ 💰 PEMASUKAN│ 💸 PENGELUARAN│ 💵 SALDO   │ 📊 TRANSAKSI│
│ Rp 15.000.000│ Rp 8.500.000│ Rp 6.500.000│ 125 Records│
└─────────────┴─────────────┴─────────────┴─────────────┘
```

### 2. **Filter Bar**
- Dropdown Jenis
- Dropdown Periode
- Input Search
- Button "Tambah Transaksi" (admin only)

### 3. **Table Transaksi**
| Tanggal | Jenis | Keterangan | Sumber | Jumlah | Aksi |
|---------|-------|------------|--------|--------|------|
| 01 Dec 2025 | PEMASUKAN | Pembayaran kos bulan Desember | Kamar 101 | Rp 1.500.000 | Edit Delete |
| 02 Dec 2025 | PENGELUARAN | Bayar listrik bulanan | PLN | Rp 500.000 | Edit Delete |

### 4. **Modal Form**
- Modern design dengan animation
- Form validation
- Date picker untuk tanggal
- Number format untuk jumlah
- Auto-calculate saldo setelah submit

---

## 📊 Contoh Data Transaksi

### Pemasukan (Hijau)
```json
{
  "id_keuangan": 1,
  "tanggal_tranksaksi": "2025-12-01",
  "jenis": "pemasukan",
  "keterangan": "Pembayaran kos bulan Desember",
  "jumlah": 1500000,
  "sumber": "Kamar 101 - Rizki Ramadhan"
}
```

### Pengeluaran (Merah)
```json
{
  "id_keuangan": 2,
  "tanggal_tranksaksi": "2025-12-05",
  "jenis": "pengeluaran",
  "keterangan": "Bayar tagihan listrik bulanan",
  "jumlah": 500000,
  "sumber": "PLN"
}
```

---

## 🔧 Technical Implementation

### Frontend (pages/keuangan.php)

**Summary Calculation (PHP):**
```php
$keuanganList = getKeuangan();
$totalPemasukan = 0;
$totalPengeluaran = 0;

foreach ($keuanganList as $item) {
    if (strtolower($item['jenis']) === 'pemasukan') {
        $totalPemasukan += $item['jumlah'];
    } else {
        $totalPengeluaran += $item['jumlah'];
    }
}

$saldo = $totalPemasukan - $totalPengeluaran;
$jumlahTransaksi = count($keuanganList);
```

**Role Check:**
```php
$userRole = $_SESSION['role'] ?? 'penghuni kos';
$isAdmin = ($userRole === 'admin');
```

**Conditional UI:**
```php
<?php if ($isAdmin): ?>
<button onclick="openModalTambah()">Tambah Transaksi</button>
<?php endif; ?>
```

### Backend (api/keuangan.php)

**Endpoints:**
- `POST ?action=create` - Create transaksi (admin only)
- `PATCH ?action=update&id=X` - Update transaksi (admin only)
- `DELETE ?action=delete&id=X` - Delete transaksi (admin only)
- `GET ?action=get` - Get all transaksi (all roles)
- `GET ?action=get&id=X` - Get single transaksi (all roles)

**Role Validation:**
```php
session_start();
if ($action === 'create' || $action === 'update' || $action === 'delete') {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
        exit;
    }
}
```

---

## 💡 Fitur Advanced (Optional - Future)

1. **Export to Excel/PDF**
   - Export laporan keuangan per periode
   - Format: Excel atau PDF

2. **Chart/Grafik**
   - Line chart: Pemasukan vs Pengeluaran per bulan
   - Pie chart: Proporsi pengeluaran by kategori
   - Bar chart: Transaksi per sumber

3. **Kategori Transaksi**
   - Tambah field `kategori` di database
   - Kategori: Operasional, Maintenance, Utilitas, dll

4. **Budget Planning**
   - Set budget bulanan
   - Alert jika pengeluaran melebihi budget

5. **Recurring Transactions**
   - Auto-create transaksi rutin (misal: listrik tiap bulan)
   - Reminder untuk transaksi recurring

6. **Multi-Currency**
   - Support mata uang lain (USD, EUR)
   - Auto convert ke IDR

---

## ✅ Implementation Checklist

### Phase 1: Basic CRUD
- [ ] Buat pages/keuangan.php dengan UI lengkap
- [ ] Summary cards (pemasukan, pengeluaran, saldo)
- [ ] Table list transaksi
- [ ] Modal tambah transaksi (admin)
- [ ] Modal edit transaksi (admin)
- [ ] Delete transaksi dengan konfirmasi (admin)
- [ ] Role-based access control

### Phase 2: Filter & Search
- [ ] Filter by jenis (pemasukan/pengeluaran)
- [ ] Filter by periode (bulan ini, 3 bulan, dll)
- [ ] Search by keterangan/sumber
- [ ] Reset filter button

### Phase 3: UI/UX Enhancement
- [ ] Format currency (Rp 1.500.000)
- [ ] Date picker untuk tanggal
- [ ] Form validation (required fields, min value)
- [ ] Loading state saat submit
- [ ] Success/error toast notification
- [ ] Responsive design (mobile friendly)

### Phase 4: Advanced Features (Optional)
- [ ] Export to Excel
- [ ] Chart dengan Chart.js
- [ ] Print laporan
- [ ] Pagination untuk banyak data

---

## 🔐 Security Considerations

1. **Input Validation**
   - Validate jenis: hanya "pemasukan" atau "pengeluaran"
   - Validate jumlah: harus positif, integer
   - Validate tanggal: format valid
   - Sanitize input untuk prevent XSS

2. **Authorization**
   - Check role di setiap endpoint sensitif
   - Penghuni tidak bisa akses create/update/delete
   - Log semua perubahan data (audit trail)

3. **Data Integrity**
   - Foreign key constraints (jika ada)
   - Check saldo tidak negatif (optional)
   - Backup data sebelum delete

---

## 📱 Responsive Design

**Desktop (> 1024px):**
- 4 cards summary dalam 1 row
- Table full width dengan semua kolom
- Modal width 600px

**Tablet (768px - 1024px):**
- 2 cards per row (2x2 grid)
- Table scrollable horizontal
- Modal width 90%

**Mobile (< 768px):**
- 1 card per row (stack vertical)
- Card design untuk list transaksi (bukan table)
- Modal full screen
- Sticky filter bar

---

**Next Step:** Implement pages/keuangan.php dengan semua fitur di atas!
