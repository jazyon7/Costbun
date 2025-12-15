<?php
require_once 'config/supabase_helper.php';

echo "<h2>🔍 Debug Halaman Bukti Pembayaran</h2>";

// Test 1: Check if getTagihan() works
echo "<h3>Test 1: Ambil Data Tagihan</h3>";
echo "<div style='padding: 20px; background: #f5f5f5; border-radius: 8px; margin: 20px 0;'>";

$tagihanList = getTagihan();

if ($tagihanList === null) {
    echo "<p style='color: red;'>❌ getTagihan() mengembalikan NULL</p>";
} else if (empty($tagihanList)) {
    echo "<p style='color: orange;'>⚠️ getTagihan() mengembalikan array kosong</p>";
} else if (is_array($tagihanList)) {
    echo "<p style='color: green;'>✅ getTagihan() mengembalikan " . count($tagihanList) . " data</p>";
    echo "<pre>" . json_encode(array_slice($tagihanList, 0, 2), JSON_PRETTY_PRINT) . "</pre>";
} else {
    echo "<p style='color: red;'>❌ getTagihan() mengembalikan tipe yang tidak valid: " . gettype($tagihanList) . "</p>";
    echo "<pre>" . json_encode($tagihanList, JSON_PRETTY_PRINT) . "</pre>";
}

echo "</div>";

// Test 2: Check data structure
if (!empty($tagihanList) && is_array($tagihanList)) {
    echo "<h3>Test 2: Struktur Data</h3>";
    echo "<div style='padding: 20px; background: #e3f2fd; border-radius: 8px; margin: 20px 0;'>";
    
    $sample = $tagihanList[0];
    echo "<p><strong>Contoh data pertama:</strong></p>";
    echo "<pre>" . json_encode($sample, JSON_PRETTY_PRINT) . "</pre>";
    
    // Check required fields
    $requiredFields = ['id_tagihan', 'jumlah', 'tgl_tagihan', 'tgl_tempo', 'status_pembayaran', 'id_user', 'id_kamar'];
    $missingFields = [];
    
    foreach ($requiredFields as $field) {
        if (!isset($sample[$field])) {
            $missingFields[] = $field;
        }
    }
    
    if (empty($missingFields)) {
        echo "<p style='color: green;'>✅ Semua field yang diperlukan ada</p>";
    } else {
        echo "<p style='color: red;'>❌ Field yang hilang: " . implode(', ', $missingFields) . "</p>";
    }
    
    // Check JOIN data
    if (isset($sample['user'])) {
        echo "<p style='color: green;'>✅ Data user (JOIN) tersedia</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Data user (JOIN) tidak ada</p>";
    }
    
    if (isset($sample['kamar'])) {
        echo "<p style='color: green;'>✅ Data kamar (JOIN) tersedia</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Data kamar (JOIN) tidak ada</p>";
    }
    
    echo "</div>";
    
    // Test 3: Count by status
    echo "<h3>Test 3: Statistik Data</h3>";
    echo "<div style='padding: 20px; background: #fff3cd; border-radius: 8px; margin: 20px 0;'>";
    
    $stats = [
        'total' => count($tagihanList),
        'lunas' => 0,
        'belum_lunas' => 0,
        'pending' => 0,
        'dengan_bukti' => 0,
        'tanpa_bukti' => 0
    ];
    
    foreach ($tagihanList as $tag) {
        $status = strtolower(str_replace(' ', '_', $tag['status_pembayaran']));
        if ($status == 'lunas') $stats['lunas']++;
        else if ($status == 'belum_lunas') $stats['belum_lunas']++;
        else if ($status == 'pending') $stats['pending']++;
        
        if (!empty($tag['bukti_pembayaran'])) {
            $stats['dengan_bukti']++;
        } else {
            $stats['tanpa_bukti']++;
        }
    }
    
    echo "<ul style='list-style: none; padding: 0;'>";
    echo "<li><strong>Total:</strong> {$stats['total']}</li>";
    echo "<li><strong>Lunas:</strong> {$stats['lunas']}</li>";
    echo "<li><strong>Belum Lunas:</strong> {$stats['belum_lunas']}</li>";
    echo "<li><strong>Pending:</strong> {$stats['pending']}</li>";
    echo "<li><strong>Dengan Bukti:</strong> {$stats['dengan_bukti']}</li>";
    echo "<li><strong>Tanpa Bukti:</strong> {$stats['tanpa_bukti']}</li>";
    echo "</ul>";
    
    echo "</div>";
    
    // Test 4: List semua tagihan dengan bukti
    if ($stats['dengan_bukti'] > 0) {
        echo "<h3>Test 4: Tagihan dengan Bukti Pembayaran</h3>";
        echo "<div style='padding: 20px; background: #d4edda; border-radius: 8px; margin: 20px 0;'>";
        echo "<table style='width: 100%; border-collapse: collapse;'>";
        echo "<tr style='background: #28a745; color: white;'>";
        echo "<th style='padding: 10px; border: 1px solid #ddd;'>ID</th>";
        echo "<th style='padding: 10px; border: 1px solid #ddd;'>Penghuni</th>";
        echo "<th style='padding: 10px; border: 1px solid #ddd;'>Jumlah</th>";
        echo "<th style='padding: 10px; border: 1px solid #ddd;'>Status</th>";
        echo "<th style='padding: 10px; border: 1px solid #ddd;'>URL Bukti</th>";
        echo "</tr>";
        
        foreach ($tagihanList as $tag) {
            if (!empty($tag['bukti_pembayaran'])) {
                $namaUser = isset($tag['user']['nama']) ? $tag['user']['nama'] : 'Unknown';
                echo "<tr>";
                echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$tag['id_tagihan']}</td>";
                echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . htmlspecialchars($namaUser) . "</td>";
                echo "<td style='padding: 10px; border: 1px solid #ddd;'>Rp " . number_format($tag['jumlah'], 0, ',', '.') . "</td>";
                echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$tag['status_pembayaran']}</td>";
                echo "<td style='padding: 10px; border: 1px solid #ddd; word-break: break-all; font-size: 11px;'>" . htmlspecialchars($tag['bukti_pembayaran']) . "</td>";
                echo "</tr>";
            }
        }
        
        echo "</table>";
        echo "</div>";
    }
}

echo "<hr>";
echo "<h3>🔧 Langkah Selanjutnya:</h3>";
echo "<ol style='line-height: 2;'>";
echo "<li>Jika tidak ada data, buat dummy data tagihan → <a href='insert_tagihan_dummy.php' style='color: #2196F3;'>Create Dummy</a></li>";
echo "<li>Jika ada data tapi tidak ada bukti, upload via N8N atau manual update database</li>";
echo "<li>Test halaman bukti pembayaran → <a href='index.php?page=bukti_pembayaran' style='color: #2196F3;'>Buka Halaman</a></li>";
echo "</ol>";

echo "<p style='margin-top: 30px;'>";
echo "<a href='index.php?page=bukti_pembayaran' style='padding: 10px 20px; background: #2196F3; color: white; text-decoration: none; border-radius: 5px;'>← Kembali ke Bukti Pembayaran</a>";
echo "</p>";
?>
