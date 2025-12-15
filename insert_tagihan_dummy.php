<?php
require_once 'config/supabase_helper.php';

echo "<h2>📝 Insert Dummy Data Tagihan</h2>";

// Get user list
$userList = getUser();
$kamarList = getKamar();

if (empty($userList) || empty($kamarList)) {
    echo "<p style='color: red;'>❌ Tidak ada data user atau kamar. Pastikan data user dan kamar sudah ada.</p>";
    exit;
}

// Filter penghuni
$penghuniList = array_filter($userList, function($user) {
    return strtolower($user['role']) === 'penghuni kos';
});

if (empty($penghuniList)) {
    echo "<p style='color: red;'>❌ Tidak ada data penghuni kos.</p>";
    exit;
}

echo "<div style='padding: 20px; background: #f5f5f5; border-radius: 8px; margin: 20px 0;'>";
echo "<p>Total Penghuni: <strong>" . count($penghuniList) . "</strong></p>";
echo "<p>Total Kamar: <strong>" . count($kamarList) . "</strong></p>";
echo "</div>";

// Sample bukti pembayaran URLs (from Supabase Storage)
$buktiSamples = [
    'https://plngxhsvzrztfqnivztt.supabase.co/storage/v1/object/public/uploads/pembayaran/1765742996218.jpg',
    'https://plngxhsvzrztfqnivztt.supabase.co/storage/v1/object/public/uploads/pembayaran/1765743021456.jpg',
    '', // no bukti
    '', // no bukti
];

$success = 0;
$failed = 0;

echo "<h3>📋 Proses Insert Data:</h3>";
echo "<div style='padding: 20px; background: #fff; border-radius: 8px; margin: 20px 0;'>";

// Create 10 dummy tagihan
for ($i = 0; $i < 10; $i++) {
    $penghuni = $penghuniList[array_rand($penghuniList)];
    $kamar = $kamarList[array_rand($kamarList)];
    
    // Random date in current month
    $day = rand(1, 28);
    $tglTagihan = date('Y-m-') . str_pad($day, 2, '0', STR_PAD_LEFT);
    $tglTempo = date('Y-m-') . str_pad($day + 7, 2, '0', STR_PAD_LEFT);
    
    // Random status
    $statuses = ['lunas', 'belum_lunas', 'pending'];
    $status = $statuses[array_rand($statuses)];
    
    // Random jumlah (500k - 2jt)
    $jumlah = rand(500, 2000) * 1000;
    
    // Random metode
    $metodes = ['Transfer Bank', 'Cash', 'E-Wallet', 'QRIS'];
    $metode = $metodes[array_rand($metodes)];
    
    // Bukti pembayaran (50% chance)
    $bukti = rand(0, 100) > 50 ? $buktiSamples[array_rand($buktiSamples)] : '';
    
    $data = [
        'jumlah' => $jumlah,
        'tgl_tagihan' => $tglTagihan,
        'tgl_tempo' => $tglTempo,
        'status_pembayaran' => $status,
        'metode_pembayaran' => $metode,
        'bukti_pembayaran' => $bukti,
        'id_user' => $penghuni['id_user'],
        'id_kamar' => $kamar['id_kamar']
    ];
    
    $result = createTagihan($data);
    
    if (!isset($result['error'])) {
        $success++;
        $buktiIcon = $bukti ? '🖼️' : '❌';
        echo "<p style='color: green;'>✅ Tagihan #" . ($i+1) . " - {$penghuni['nama']} - Rp " . number_format($jumlah, 0, ',', '.') . " - $status $buktiIcon</p>";
    } else {
        $failed++;
        echo "<p style='color: red;'>❌ Tagihan #" . ($i+1) . " gagal: " . ($result['message'] ?? 'Unknown error') . "</p>";
    }
}

echo "</div>";

echo "<div style='padding: 20px; background: #d4edda; border-radius: 8px; margin: 20px 0;'>";
echo "<h3 style='color: #155724;'>📊 Hasil:</h3>";
echo "<p><strong>Berhasil:</strong> $success</p>";
echo "<p><strong>Gagal:</strong> $failed</p>";
echo "</div>";

// Show created data
echo "<h3>✅ Data yang Berhasil Dibuat:</h3>";
$tagihanList = getTagihan();
if (!empty($tagihanList)) {
    $recent = array_slice($tagihanList, 0, 10);
    
    echo "<div style='padding: 20px; background: #fff; border-radius: 8px; margin: 20px 0; overflow-x: auto;'>";
    echo "<table style='width: 100%; border-collapse: collapse;'>";
    echo "<tr style='background: #2196F3; color: white;'>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>ID</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Penghuni</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Kamar</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Jumlah</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Status</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Bukti</th>";
    echo "</tr>";
    
    foreach ($recent as $tag) {
        $namaUser = isset($tag['user']['nama']) ? $tag['user']['nama'] : 'Unknown';
        $namaKamar = isset($tag['kamar']['nama']) ? $tag['kamar']['nama'] : 'Unknown';
        $hasBukti = !empty($tag['bukti_pembayaran']) ? '✅ Ada' : '❌ Tidak';
        
        echo "<tr>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$tag['id_tagihan']}</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . htmlspecialchars($namaUser) . "</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . htmlspecialchars($namaKamar) . "</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>Rp " . number_format($tag['jumlah'], 0, ',', '.') . "</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$tag['status_pembayaran']}</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>$hasBukti</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    echo "</div>";
}

echo "<hr>";
echo "<p style='margin-top: 30px;'>";
echo "<a href='index.php?page=bukti_pembayaran' style='padding: 10px 20px; background: #2196F3; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;'>← Lihat Bukti Pembayaran</a>";
echo "<a href='debug_bukti_pembayaran.php' style='padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px;'>Debug Data</a>";
echo "</p>";
?>
