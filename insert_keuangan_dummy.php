<?php
require_once __DIR__ . '/config/supabase_helper.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Insert Dummy Data Keuangan</title>
    <style>
        body { font-family: Arial; padding: 40px; background: #f5f5f5; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        h1 { color: #333; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
        .btn:hover { background: #0056b3; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
<div class='container'>
<h1>🏦 Insert Dummy Data Keuangan</h1>
";

$dummyData = [
    // PEMASUKAN
    [
        'tanggal_tranksaksi' => '2025-12-01',
        'jenis' => 'pemasukan',
        'keterangan' => 'Pembayaran kos bulan Desember',
        'jumlah' => 1500000,
        'sumber' => 'Kamar 101 - Rizki Ramadhan'
    ],
    [
        'tanggal_tranksaksi' => '2025-12-01',
        'jenis' => 'pemasukan',
        'keterangan' => 'Pembayaran kos bulan Desember',
        'jumlah' => 1500000,
        'sumber' => 'Kamar 102 - Dani Setiawan'
    ],
    [
        'tanggal_tranksaksi' => '2025-12-02',
        'jenis' => 'pemasukan',
        'keterangan' => 'Pembayaran kos bulan Desember',
        'jumlah' => 1800000,
        'sumber' => 'Kamar 201 - Budi Santoso'
    ],
    [
        'tanggal_tranksaksi' => '2025-12-03',
        'jenis' => 'pemasukan',
        'keterangan' => 'Pembayaran kos bulan Desember',
        'jumlah' => 1500000,
        'sumber' => 'Kamar 103 - Andi Wijaya'
    ],
    [
        'tanggal_tranksaksi' => '2025-12-05',
        'jenis' => 'pemasukan',
        'keterangan' => 'Pembayaran deposit kamar baru',
        'jumlah' => 3000000,
        'sumber' => 'Kamar 202 - Siti Nurhaliza'
    ],
    
    // PENGELUARAN
    [
        'tanggal_tranksaksi' => '2025-12-05',
        'jenis' => 'pengeluaran',
        'keterangan' => 'Bayar tagihan listrik bulanan',
        'jumlah' => 850000,
        'sumber' => 'PLN'
    ],
    [
        'tanggal_tranksaksi' => '2025-12-06',
        'jenis' => 'pengeluaran',
        'keterangan' => 'Bayar tagihan air PDAM',
        'jumlah' => 350000,
        'sumber' => 'PDAM'
    ],
    [
        'tanggal_tranksaksi' => '2025-12-07',
        'jenis' => 'pengeluaran',
        'keterangan' => 'Service AC kamar 101 dan 103',
        'jumlah' => 500000,
        'sumber' => 'Toko AC Sejahtera'
    ],
    [
        'tanggal_tranksaksi' => '2025-12-08',
        'jenis' => 'pengeluaran',
        'keterangan' => 'Beli perlengkapan kebersihan (sapu, pel, dll)',
        'jumlah' => 250000,
        'sumber' => 'Toko Bangunan Jaya'
    ],
    [
        'tanggal_tranksaksi' => '2025-12-09',
        'jenis' => 'pengeluaran',
        'keterangan' => 'Gaji petugas kebersihan bulan Desember',
        'jumlah' => 1500000,
        'sumber' => 'Pak Amin (Cleaning Service)'
    ],
    [
        'tanggal_tranksaksi' => '2025-12-10',
        'jenis' => 'pengeluaran',
        'keterangan' => 'Perbaikan pipa air bocor kamar 201',
        'jumlah' => 400000,
        'sumber' => 'Tukang Ledeng Makmur'
    ],
    [
        'tanggal_tranksaksi' => '2025-12-11',
        'jenis' => 'pengeluaran',
        'keterangan' => 'Bayar internet bulanan IndiHome',
        'jumlah' => 450000,
        'sumber' => 'Telkom IndiHome'
    ],
    [
        'tanggal_tranksaksi' => '2025-12-12',
        'jenis' => 'pengeluaran',
        'keterangan' => 'Cat tembok kamar 102 yang kusam',
        'jumlah' => 600000,
        'sumber' => 'Toko Cat Warna Indah'
    ],
    [
        'tanggal_tranksaksi' => '2025-12-13',
        'jenis' => 'pengeluaran',
        'keterangan' => 'Beli kasur baru untuk kamar 202',
        'jumlah' => 2000000,
        'sumber' => 'Toko Furniture Abadi'
    ],
    [
        'tanggal_tranksaksi' => '2025-12-14',
        'jenis' => 'pemasukan',
        'keterangan' => 'Pembayaran kos bulan Desember (telat)',
        'jumlah' => 1500000,
        'sumber' => 'Kamar 104 - Ahmad Fauzi'
    ],
];

$successCount = 0;
$errorCount = 0;

echo "<h2>📊 Summary</h2>";
echo "<p>Total data to insert: <strong>" . count($dummyData) . "</strong></p>";

echo "<h2>🚀 Inserting Data...</h2>";

foreach ($dummyData as $index => $data) {
    $no = $index + 1;
    
    echo "<div style='padding: 10px; margin: 10px 0; background: #f8f9fa; border-radius: 5px;'>";
    echo "<strong>#{$no}</strong> - {$data['tanggal_tranksaksi']} - {$data['jenis']} - Rp " . number_format($data['jumlah'], 0, ',', '.') . "<br>";
    echo "<small>{$data['keterangan']} ({$data['sumber']})</small><br>";
    
    $result = createKeuangan($data);
    
    if (isset($result['error'])) {
        echo "<div class='error'>❌ Error: " . ($result['message'] ?? 'Unknown error') . "</div>";
        echo "<pre>" . print_r($result, true) . "</pre>";
        $errorCount++;
    } else {
        echo "<div class='success'>✅ Success!</div>";
        $successCount++;
    }
    
    echo "</div>";
    
    // Delay untuk mencegah rate limit
    usleep(100000); // 0.1 second
}

echo "<hr>";
echo "<h2>✅ Results</h2>";
echo "<div class='success'>";
echo "<strong>✅ Success:</strong> $successCount transactions<br>";
echo "<strong>❌ Failed:</strong> $errorCount transactions<br>";
echo "</div>";

// Calculate summary
echo "<h2>💰 Financial Summary</h2>";
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

echo "<div style='display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin: 20px 0;'>";
echo "<div style='background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); padding: 20px; border-radius: 10px; color: white;'>";
echo "<h3 style='margin: 0;'>💰 Total Pemasukan</h3>";
echo "<p style='font-size: 24px; font-weight: bold; margin: 10px 0 0 0;'>Rp " . number_format($totalPemasukan, 0, ',', '.') . "</p>";
echo "</div>";

echo "<div style='background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); padding: 20px; border-radius: 10px; color: white;'>";
echo "<h3 style='margin: 0;'>💸 Total Pengeluaran</h3>";
echo "<p style='font-size: 24px; font-weight: bold; margin: 10px 0 0 0;'>Rp " . number_format($totalPengeluaran, 0, ',', '.') . "</p>";
echo "</div>";

echo "<div style='background: linear-gradient(135deg, #4776e6 0%, #8e54e9 100%); padding: 20px; border-radius: 10px; color: white;'>";
echo "<h3 style='margin: 0;'>💵 Saldo</h3>";
echo "<p style='font-size: 24px; font-weight: bold; margin: 10px 0 0 0;'>Rp " . number_format($saldo, 0, ',', '.') . "</p>";
echo "</div>";
echo "</div>";

echo "<h2>🔗 Quick Links</h2>";
echo "<a href='index.php?page=keuangan' class='btn'>✅ Buka Halaman Keuangan</a>";
echo "<a href='test_supabase_keuangan.php' class='btn'>🧪 Test API</a>";

echo "</div></body></html>";
?>
