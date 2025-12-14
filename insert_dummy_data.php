<?php
require_once __DIR__ . '/config/supabase_helper.php';

echo "<h1>🚀 Insert Data Dummy ke Supabase</h1>";
echo "<style>body{font-family:Arial;padding:20px;} .success{color:green;} .error{color:red;}</style>";

// ============ 1. KAMAR (15 data) ============
echo "<h2>1. Insert Data Kamar (15 data)</h2>";
$kamarData = [
    ['nama' => 'A-01', 'kasur' => 1, 'kipas' => 1, 'lemari' => 1, 'keranjang_sampah' => 1, 'ac' => 0, 'harga' => 500000, 'status' => 'kosong'],
    ['nama' => 'A-02', 'kasur' => 1, 'kipas' => 1, 'lemari' => 1, 'keranjang_sampah' => 1, 'ac' => 1, 'harga' => 750000, 'status' => 'terisi'],
    ['nama' => 'A-03', 'kasur' => 1, 'kipas' => 1, 'lemari' => 1, 'keranjang_sampah' => 1, 'ac' => 0, 'harga' => 500000, 'status' => 'terisi'],
    ['nama' => 'A-04', 'kasur' => 2, 'kipas' => 2, 'lemari' => 1, 'keranjang_sampah' => 1, 'ac' => 1, 'harga' => 900000, 'status' => 'kosong'],
    ['nama' => 'A-05', 'kasur' => 1, 'kipas' => 1, 'lemari' => 1, 'keranjang_sampah' => 1, 'ac' => 0, 'harga' => 500000, 'status' => 'terisi'],
    ['nama' => 'B-01', 'kasur' => 1, 'kipas' => 1, 'lemari' => 1, 'keranjang_sampah' => 1, 'ac' => 1, 'harga' => 750000, 'status' => 'terisi'],
    ['nama' => 'B-02', 'kasur' => 1, 'kipas' => 1, 'lemari' => 1, 'keranjang_sampah' => 1, 'ac' => 0, 'harga' => 500000, 'status' => 'kosong'],
    ['nama' => 'B-03', 'kasur' => 1, 'kipas' => 1, 'lemari' => 1, 'keranjang_sampah' => 1, 'ac' => 1, 'harga' => 750000, 'status' => 'terisi'],
    ['nama' => 'B-04', 'kasur' => 2, 'kipas' => 2, 'lemari' => 2, 'keranjang_sampah' => 1, 'ac' => 1, 'harga' => 1000000, 'status' => 'kosong'],
    ['nama' => 'B-05', 'kasur' => 1, 'kipas' => 1, 'lemari' => 1, 'keranjang_sampah' => 1, 'ac' => 0, 'harga' => 500000, 'status' => 'terisi'],
    ['nama' => 'C-01', 'kasur' => 1, 'kipas' => 1, 'lemari' => 1, 'keranjang_sampah' => 1, 'ac' => 1, 'harga' => 750000, 'status' => 'kosong'],
    ['nama' => 'C-02', 'kasur' => 1, 'kipas' => 1, 'lemari' => 1, 'keranjang_sampah' => 1, 'ac' => 0, 'harga' => 500000, 'status' => 'terisi'],
    ['nama' => 'C-03', 'kasur' => 1, 'kipas' => 1, 'lemari' => 1, 'keranjang_sampah' => 1, 'ac' => 1, 'harga' => 750000, 'status' => 'kosong'],
    ['nama' => 'C-04', 'kasur' => 1, 'kipas' => 1, 'lemari' => 1, 'keranjang_sampah' => 1, 'ac' => 0, 'harga' => 500000, 'status' => 'terisi'],
    ['nama' => 'C-05', 'kasur' => 2, 'kipas' => 2, 'lemari' => 1, 'keranjang_sampah' => 1, 'ac' => 1, 'harga' => 900000, 'status' => 'terisi'],
];

foreach ($kamarData as $kamar) {
    $result = createKamar($kamar);
    if ($result) {
        echo "<p class='success'>✓ Kamar {$kamar['nama']} berhasil ditambahkan</p>";
    } else {
        echo "<p class='error'>✗ Kamar {$kamar['nama']} gagal ditambahkan</p>";
    }
}

// ============ 2. USER (15 data) ============
echo "<h2>2. Insert Data User (15 data)</h2>";
$userData = [
    ['nama' => 'Admin Utama', 'email' => 'admin@kos.com', 'password' => password_hash('admin123', PASSWORD_BCRYPT), 'no_hp' => '081234567890', 'role' => 'admin', 'no_kamar' => null],
    ['nama' => 'Budi Santoso', 'email' => 'budi@email.com', 'password' => password_hash('budi123', PASSWORD_BCRYPT), 'no_hp' => '081234567891', 'role' => 'penyewa', 'no_kamar' => 'A-02'],
    ['nama' => 'Siti Nurhaliza', 'email' => 'siti@email.com', 'password' => password_hash('siti123', PASSWORD_BCRYPT), 'no_hp' => '081234567892', 'role' => 'penyewa', 'no_kamar' => 'A-03'],
    ['nama' => 'Andi Wijaya', 'email' => 'andi@email.com', 'password' => password_hash('andi123', PASSWORD_BCRYPT), 'no_hp' => '081234567893', 'role' => 'penyewa', 'no_kamar' => 'A-05'],
    ['nama' => 'Dewi Lestari', 'email' => 'dewi@email.com', 'password' => password_hash('dewi123', PASSWORD_BCRYPT), 'no_hp' => '081234567894', 'role' => 'penyewa', 'no_kamar' => 'B-01'],
    ['nama' => 'Rudi Hartono', 'email' => 'rudi@email.com', 'password' => password_hash('rudi123', PASSWORD_BCRYPT), 'no_hp' => '081234567895', 'role' => 'penyewa', 'no_kamar' => 'B-03'],
    ['nama' => 'Lisa Anggraini', 'email' => 'lisa@email.com', 'password' => password_hash('lisa123', PASSWORD_BCRYPT), 'no_hp' => '081234567896', 'role' => 'penyewa', 'no_kamar' => 'B-05'],
    ['nama' => 'Ahmad Fauzi', 'email' => 'ahmad@email.com', 'password' => password_hash('ahmad123', PASSWORD_BCRYPT), 'no_hp' => '081234567897', 'role' => 'penyewa', 'no_kamar' => 'C-02'],
    ['nama' => 'Maya Putri', 'email' => 'maya@email.com', 'password' => password_hash('maya123', PASSWORD_BCRYPT), 'no_hp' => '081234567898', 'role' => 'penyewa', 'no_kamar' => 'C-04'],
    ['nama' => 'Doni Prasetyo', 'email' => 'doni@email.com', 'password' => password_hash('doni123', PASSWORD_BCRYPT), 'no_hp' => '081234567899', 'role' => 'penyewa', 'no_kamar' => 'C-05'],
    ['nama' => 'Rina Kusuma', 'email' => 'rina@email.com', 'password' => password_hash('rina123', PASSWORD_BCRYPT), 'no_hp' => '081234567800', 'role' => 'penyewa', 'no_kamar' => null],
    ['nama' => 'Joko Widodo', 'email' => 'joko@email.com', 'password' => password_hash('joko123', PASSWORD_BCRYPT), 'no_hp' => '081234567801', 'role' => 'penyewa', 'no_kamar' => null],
    ['nama' => 'Ani Suryani', 'email' => 'ani@email.com', 'password' => password_hash('ani123', PASSWORD_BCRYPT), 'no_hp' => '081234567802', 'role' => 'penyewa', 'no_kamar' => null],
    ['nama' => 'Bambang Susilo', 'email' => 'bambang@email.com', 'password' => password_hash('bambang123', PASSWORD_BCRYPT), 'no_hp' => '081234567803', 'role' => 'penyewa', 'no_kamar' => null],
    ['nama' => 'Citra Dewi', 'email' => 'citra@email.com', 'password' => password_hash('citra123', PASSWORD_BCRYPT), 'no_hp' => '081234567804', 'role' => 'penyewa', 'no_kamar' => null],
];

foreach ($userData as $user) {
    $result = createUser($user);
    if ($result) {
        echo "<p class='success'>✓ User {$user['nama']} berhasil ditambahkan</p>";
    } else {
        echo "<p class='error'>✗ User {$user['nama']} gagal ditambahkan</p>";
    }
}

// Get user IDs untuk foreign key
$allUsers = getUser();
$userId1 = $allUsers[1]['id'] ?? 1;
$userId2 = $allUsers[2]['id'] ?? 2;
$userId3 = $allUsers[3]['id'] ?? 3;
$userId4 = $allUsers[4]['id'] ?? 4;
$userId5 = $allUsers[5]['id'] ?? 5;

// ============ 3. LAPORAN (15 data) ============
echo "<h2>3. Insert Data Laporan (15 data)</h2>";
$laporanData = [
    ['id_user' => $userId1, 'kategori' => 'kerusakan', 'deskripsi' => 'Lampu kamar mati', 'status_laporan' => 'diproses', 'tanggal_laporan' => date('Y-m-d H:i:s')],
    ['id_user' => $userId2, 'kategori' => 'kebersihan', 'deskripsi' => 'Kamar mandi kotor', 'status_laporan' => 'selesai', 'tanggal_laporan' => date('Y-m-d H:i:s', strtotime('-1 day'))],
    ['id_user' => $userId1, 'kategori' => 'kerusakan', 'deskripsi' => 'AC tidak dingin', 'status_laporan' => 'diproses', 'tanggal_laporan' => date('Y-m-d H:i:s', strtotime('-2 days'))],
    ['id_user' => $userId3, 'kategori' => 'fasilitas', 'deskripsi' => 'Wifi lemot', 'status_laporan' => 'selesai', 'tanggal_laporan' => date('Y-m-d H:i:s', strtotime('-3 days'))],
    ['id_user' => $userId2, 'kategori' => 'kerusakan', 'deskripsi' => 'Kunci pintu rusak', 'status_laporan' => 'diproses', 'tanggal_laporan' => date('Y-m-d H:i:s', strtotime('-4 days'))],
    ['id_user' => $userId4, 'kategori' => 'kebersihan', 'deskripsi' => 'Kamar berbau', 'status_laporan' => 'selesai', 'tanggal_laporan' => date('Y-m-d H:i:s', strtotime('-5 days'))],
    ['id_user' => $userId1, 'kategori' => 'fasilitas', 'deskripsi' => 'Lemari rusak', 'status_laporan' => 'diproses', 'tanggal_laporan' => date('Y-m-d H:i:s', strtotime('-6 days'))],
    ['id_user' => $userId5, 'kategori' => 'kerusakan', 'deskripsi' => 'Kipas angin mati', 'status_laporan' => 'selesai', 'tanggal_laporan' => date('Y-m-d H:i:s', strtotime('-7 days'))],
    ['id_user' => $userId3, 'kategori' => 'kebersihan', 'deskripsi' => 'Coridor kotor', 'status_laporan' => 'diproses', 'tanggal_laporan' => date('Y-m-d H:i:s', strtotime('-8 days'))],
    ['id_user' => $userId2, 'kategori' => 'fasilitas', 'deskripsi' => 'Air PAM mati', 'status_laporan' => 'selesai', 'tanggal_laporan' => date('Y-m-d H:i:s', strtotime('-9 days'))],
    ['id_user' => $userId4, 'kategori' => 'kerusakan', 'deskripsi' => 'Jendela tidak bisa ditutup', 'status_laporan' => 'diproses', 'tanggal_laporan' => date('Y-m-d H:i:s', strtotime('-10 days'))],
    ['id_user' => $userId1, 'kategori' => 'kebersihan', 'deskripsi' => 'Tempat sampah penuh', 'status_laporan' => 'selesai', 'tanggal_laporan' => date('Y-m-d H:i:s', strtotime('-11 days'))],
    ['id_user' => $userId5, 'kategori' => 'fasilitas', 'deskripsi' => 'Listrik sering mati', 'status_laporan' => 'diproses', 'tanggal_laporan' => date('Y-m-d H:i:s', strtotime('-12 days'))],
    ['id_user' => $userId3, 'kategori' => 'kerusakan', 'deskripsi' => 'Pintu kamar susah dibuka', 'status_laporan' => 'selesai', 'tanggal_laporan' => date('Y-m-d H:i:s', strtotime('-13 days'))],
    ['id_user' => $userId2, 'kategori' => 'kebersihan', 'deskripsi' => 'Lantai licin', 'status_laporan' => 'diproses', 'tanggal_laporan' => date('Y-m-d H:i:s', strtotime('-14 days'))],
];

foreach ($laporanData as $laporan) {
    $result = createLaporan($laporan);
    if ($result) {
        echo "<p class='success'>✓ Laporan '{$laporan['deskripsi']}' berhasil ditambahkan</p>";
    } else {
        echo "<p class='error'>✗ Laporan '{$laporan['deskripsi']}' gagal ditambahkan</p>";
    }
}

// ============ 4. NOTIFIKASI (15 data) ============
echo "<h2>4. Insert Data Notifikasi (15 data)</h2>";
$notifikasiData = [
    ['id_user' => $userId1, 'judul' => 'Selamat Datang', 'pesan' => 'Selamat datang di Kos Bu Anna!', 'status' => 'read', 'tanggal' => date('Y-m-d H:i:s')],
    ['id_user' => $userId2, 'judul' => 'Tagihan Bulan Ini', 'pesan' => 'Tagihan sewa bulan ini sudah jatuh tempo', 'status' => 'unread', 'tanggal' => date('Y-m-d H:i:s', strtotime('-1 day'))],
    ['id_user' => $userId1, 'judul' => 'Pembayaran Diterima', 'pesan' => 'Pembayaran Anda sudah kami terima', 'status' => 'read', 'tanggal' => date('Y-m-d H:i:s', strtotime('-2 days'))],
    ['id_user' => $userId3, 'judul' => 'Perbaikan Selesai', 'pesan' => 'Perbaikan AC di kamar Anda sudah selesai', 'status' => 'read', 'tanggal' => date('Y-m-d H:i:s', strtotime('-3 days'))],
    ['id_user' => $userId2, 'judul' => 'Pengumuman', 'pesan' => 'Akan ada pemadaman listrik besok pagi', 'status' => 'unread', 'tanggal' => date('Y-m-d H:i:s', strtotime('-4 days'))],
    ['id_user' => $userId4, 'judul' => 'Reminder', 'pesan' => 'Jangan lupa bayar tagihan sebelum tanggal 10', 'status' => 'unread', 'tanggal' => date('Y-m-d H:i:s', strtotime('-5 days'))],
    ['id_user' => $userId1, 'judul' => 'Update Fasilitas', 'pesan' => 'WiFi sudah diperbaiki dan lebih cepat', 'status' => 'read', 'tanggal' => date('Y-m-d H:i:s', strtotime('-6 days'))],
    ['id_user' => $userId5, 'judul' => 'Info Pembersihan', 'pesan' => 'Kamar akan dibersihkan hari Sabtu', 'status' => 'unread', 'tanggal' => date('Y-m-d H:i:s', strtotime('-7 days'))],
    ['id_user' => $userId3, 'judul' => 'Tagihan Denda', 'pesan' => 'Ada denda keterlambatan pembayaran', 'status' => 'unread', 'tanggal' => date('Y-m-d H:i:s', strtotime('-8 days'))],
    ['id_user' => $userId2, 'judul' => 'Pemberitahuan', 'pesan' => 'Aturan baru tentang jam malam', 'status' => 'read', 'tanggal' => date('Y-m-d H:i:s', strtotime('-9 days'))],
    ['id_user' => $userId4, 'judul' => 'Konfirmasi', 'pesan' => 'Laporan Anda sedang diproses', 'status' => 'read', 'tanggal' => date('Y-m-d H:i:s', strtotime('-10 days'))],
    ['id_user' => $userId1, 'judul' => 'Event Kos', 'pesan' => 'Ada acara gathering penghuni minggu depan', 'status' => 'unread', 'tanggal' => date('Y-m-d H:i:s', strtotime('-11 days'))],
    ['id_user' => $userId5, 'judul' => 'Maintenance', 'pesan' => 'Maintenance AC akan dilakukan besok', 'status' => 'unread', 'tanggal' => date('Y-m-d H:i:s', strtotime('-12 days'))],
    ['id_user' => $userId3, 'judul' => 'Terima Kasih', 'pesan' => 'Terima kasih sudah membayar tepat waktu', 'status' => 'read', 'tanggal' => date('Y-m-d H:i:s', strtotime('-13 days'))],
    ['id_user' => $userId2, 'judul' => 'Update Sistem', 'pesan' => 'Sistem pembayaran online sudah tersedia', 'status' => 'unread', 'tanggal' => date('Y-m-d H:i:s', strtotime('-14 days'))],
];

foreach ($notifikasiData as $notif) {
    $result = createNotifikasi($notif);
    if ($result) {
        echo "<p class='success'>✓ Notifikasi '{$notif['judul']}' berhasil ditambahkan</p>";
    } else {
        echo "<p class='error'>✗ Notifikasi '{$notif['judul']}' gagal ditambahkan</p>";
    }
}

// Get kamar IDs untuk tagihan
$allKamar = getKamar();
$kamarId1 = $allKamar[1]['id'] ?? 1;
$kamarId2 = $allKamar[2]['id'] ?? 2;
$kamarId3 = $allKamar[4]['id'] ?? 3;

// ============ 5. TAGIHAN (15 data) ============
echo "<h2>5. Insert Data Tagihan (15 data)</h2>";
$tagihanData = [
    ['id_user' => $userId1, 'id_kamar' => $kamarId1, 'bulan' => 'Januari 2025', 'jumlah' => 750000, 'status_bayar' => 'lunas', 'tanggal_bayar' => date('Y-m-d', strtotime('-20 days'))],
    ['id_user' => $userId2, 'id_kamar' => $kamarId2, 'bulan' => 'Januari 2025', 'jumlah' => 500000, 'status_bayar' => 'lunas', 'tanggal_bayar' => date('Y-m-d', strtotime('-18 days'))],
    ['id_user' => $userId3, 'id_kamar' => $kamarId3, 'bulan' => 'Januari 2025', 'jumlah' => 500000, 'status_bayar' => 'belum', 'tanggal_bayar' => null],
    ['id_user' => $userId1, 'id_kamar' => $kamarId1, 'bulan' => 'Februari 2025', 'jumlah' => 750000, 'status_bayar' => 'lunas', 'tanggal_bayar' => date('Y-m-d', strtotime('-5 days'))],
    ['id_user' => $userId2, 'id_kamar' => $kamarId2, 'bulan' => 'Februari 2025', 'jumlah' => 500000, 'status_bayar' => 'belum', 'tanggal_bayar' => null],
    ['id_user' => $userId3, 'id_kamar' => $kamarId3, 'bulan' => 'Februari 2025', 'jumlah' => 500000, 'status_bayar' => 'belum', 'tanggal_bayar' => null],
    ['id_user' => $userId4, 'id_kamar' => $kamarId1, 'bulan' => 'Desember 2024', 'jumlah' => 750000, 'status_bayar' => 'lunas', 'tanggal_bayar' => date('Y-m-d', strtotime('-35 days'))],
    ['id_user' => $userId5, 'id_kamar' => $kamarId2, 'bulan' => 'Desember 2024', 'jumlah' => 500000, 'status_bayar' => 'lunas', 'tanggal_bayar' => date('Y-m-d', strtotime('-33 days'))],
    ['id_user' => $userId1, 'id_kamar' => $kamarId1, 'bulan' => 'November 2024', 'jumlah' => 750000, 'status_bayar' => 'lunas', 'tanggal_bayar' => date('Y-m-d', strtotime('-60 days'))],
    ['id_user' => $userId2, 'id_kamar' => $kamarId2, 'bulan' => 'November 2024', 'jumlah' => 500000, 'status_bayar' => 'lunas', 'tanggal_bayar' => date('Y-m-d', strtotime('-58 days'))],
    ['id_user' => $userId3, 'id_kamar' => $kamarId3, 'bulan' => 'November 2024', 'jumlah' => 500000, 'status_bayar' => 'lunas', 'tanggal_bayar' => date('Y-m-d', strtotime('-56 days'))],
    ['id_user' => $userId4, 'id_kamar' => $kamarId1, 'bulan' => 'Oktober 2024', 'jumlah' => 750000, 'status_bayar' => 'lunas', 'tanggal_bayar' => date('Y-m-d', strtotime('-85 days'))],
    ['id_user' => $userId5, 'id_kamar' => $kamarId2, 'bulan' => 'Oktober 2024', 'jumlah' => 500000, 'status_bayar' => 'lunas', 'tanggal_bayar' => date('Y-m-d', strtotime('-83 days'))],
    ['id_user' => $userId1, 'id_kamar' => $kamarId1, 'bulan' => 'Maret 2025', 'jumlah' => 750000, 'status_bayar' => 'belum', 'tanggal_bayar' => null],
    ['id_user' => $userId2, 'id_kamar' => $kamarId2, 'bulan' => 'Maret 2025', 'jumlah' => 500000, 'status_bayar' => 'belum', 'tanggal_bayar' => null],
];

foreach ($tagihanData as $tagihan) {
    $result = createTagihan($tagihan);
    if ($result) {
        echo "<p class='success'>✓ Tagihan {$tagihan['bulan']} berhasil ditambahkan</p>";
    } else {
        echo "<p class='error'>✗ Tagihan {$tagihan['bulan']} gagal ditambahkan</p>";
    }
}

// ============ 6. KEUANGAN (15 data) ============
echo "<h2>6. Insert Data Keuangan (15 data)</h2>";
$keuanganData = [
    ['jenis' => 'pemasukan', 'keterangan' => 'Pembayaran sewa kamar A-02', 'jumlah' => 750000, 'tanggal' => date('Y-m-d', strtotime('-20 days'))],
    ['jenis' => 'pemasukan', 'keterangan' => 'Pembayaran sewa kamar A-03', 'jumlah' => 500000, 'tanggal' => date('Y-m-d', strtotime('-18 days'))],
    ['jenis' => 'pengeluaran', 'keterangan' => 'Perbaikan AC kamar B-01', 'jumlah' => 350000, 'tanggal' => date('Y-m-d', strtotime('-15 days'))],
    ['jenis' => 'pemasukan', 'keterangan' => 'Pembayaran sewa kamar B-03', 'jumlah' => 750000, 'tanggal' => date('Y-m-d', strtotime('-12 days'))],
    ['jenis' => 'pengeluaran', 'keterangan' => 'Bayar listrik bulanan', 'jumlah' => 500000, 'tanggal' => date('Y-m-d', strtotime('-10 days'))],
    ['jenis' => 'pengeluaran', 'keterangan' => 'Bayar air PDAM', 'jumlah' => 200000, 'tanggal' => date('Y-m-d', strtotime('-9 days'))],
    ['jenis' => 'pemasukan', 'keterangan' => 'Pembayaran sewa kamar C-02', 'jumlah' => 500000, 'tanggal' => date('Y-m-d', strtotime('-8 days'))],
    ['jenis' => 'pengeluaran', 'keterangan' => 'Beli perlengkapan kebersihan', 'jumlah' => 150000, 'tanggal' => date('Y-m-d', strtotime('-7 days'))],
    ['jenis' => 'pemasukan', 'keterangan' => 'Pembayaran sewa kamar C-04', 'jumlah' => 500000, 'tanggal' => date('Y-m-d', strtotime('-6 days'))],
    ['jenis' => 'pengeluaran', 'keterangan' => 'Service kipas angin', 'jumlah' => 100000, 'tanggal' => date('Y-m-d', strtotime('-5 days'))],
    ['jenis' => 'pemasukan', 'keterangan' => 'Pembayaran sewa kamar A-02', 'jumlah' => 750000, 'tanggal' => date('Y-m-d', strtotime('-4 days'))],
    ['jenis' => 'pengeluaran', 'keterangan' => 'Ganti kunci pintu', 'jumlah' => 75000, 'tanggal' => date('Y-m-d', strtotime('-3 days'))],
    ['jenis' => 'pemasukan', 'keterangan' => 'Denda keterlambatan pembayaran', 'jumlah' => 50000, 'tanggal' => date('Y-m-d', strtotime('-2 days'))],
    ['jenis' => 'pengeluaran', 'keterangan' => 'Upgrade WiFi', 'jumlah' => 300000, 'tanggal' => date('Y-m-d', strtotime('-1 day'))],
    ['jenis' => 'pengeluaran', 'keterangan' => 'Gaji cleaning service', 'jumlah' => 1000000, 'tanggal' => date('Y-m-d')],
];

foreach ($keuanganData as $keuangan) {
    $result = createKeuangan($keuangan);
    if ($result) {
        echo "<p class='success'>✓ Keuangan '{$keuangan['keterangan']}' berhasil ditambahkan</p>";
    } else {
        echo "<p class='error'>✗ Keuangan '{$keuangan['keterangan']}' gagal ditambahkan</p>";
    }
}

echo "<hr>";
echo "<h2>🎉 Selesai!</h2>";
echo "<p>Semua data dummy berhasil ditambahkan ke Supabase.</p>";
echo "<p><a href='index.php' style='padding:10px 20px; background:#3681ff; color:white; text-decoration:none; border-radius:5px;'>Lihat Dashboard</a></p>";
echo "<p><a href='pages/test_koneksi.php' style='padding:10px 20px; background:#4CAF50; color:white; text-decoration:none; border-radius:5px; margin-left:10px;'>Test Koneksi</a></p>";
?>
