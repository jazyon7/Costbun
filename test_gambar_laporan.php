<?php
require_once 'config/supabase_helper.php';

echo "<h2>🔍 Test Akses Gambar Laporan</h2>";

// Get all laporan with images
$laporanList = getLaporan();

if (empty($laporanList)) {
    echo "<p style='color: orange;'>⚠️ Tidak ada data laporan</p>";
    exit;
}

// Filter laporan yang punya gambar
$laporanWithImages = array_filter($laporanList, function($lap) {
    return !empty($lap['gambar_url']);
});

if (empty($laporanWithImages)) {
    echo "<p style='color: orange;'>⚠️ Tidak ada laporan dengan gambar</p>";
    exit;
}

echo "<div style='padding: 20px; background: #f5f5f5; border-radius: 8px; margin: 20px 0;'>";
echo "<table style='width: 100%; border-collapse: collapse;'>";
echo "<tr style='background: #2196F3; color: white;'>";
echo "<th style='padding: 10px; border: 1px solid #ddd;'>ID</th>";
echo "<th style='padding: 10px; border: 1px solid #ddd;'>Judul</th>";
echo "<th style='padding: 10px; border: 1px solid #ddd;'>URL Gambar</th>";
echo "<th style='padding: 10px; border: 1px solid #ddd;'>Status</th>";
echo "<th style='padding: 10px; border: 1px solid #ddd;'>Preview</th>";
echo "</tr>";

foreach ($laporanWithImages as $lap) {
    $url = $lap['gambar_url'];
    
    // Check if URL is accessible
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true); // HEAD request only
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $status = $httpCode === 200 ? 
        "<span style='color: green; font-weight: bold;'>✅ OK ($httpCode)</span>" : 
        "<span style='color: red; font-weight: bold;'>❌ Error ($httpCode)</span>";
    
    $preview = $httpCode === 200 ? 
        "<img src='$url' style='max-width: 100px; max-height: 100px; border-radius: 4px;'>" : 
        "<span style='color: red;'>Tidak bisa dimuat</span>";
    
    echo "<tr>";
    echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$lap['id_laporan']}</td>";
    echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . htmlspecialchars($lap['judul_laporan']) . "</td>";
    echo "<td style='padding: 10px; border: 1px solid #ddd; word-break: break-all; font-size: 11px;'>" . htmlspecialchars($url) . "</td>";
    echo "<td style='padding: 10px; border: 1px solid #ddd;'>$status</td>";
    echo "<td style='padding: 10px; border: 1px solid #ddd; text-align: center;'>$preview</td>";
    echo "</tr>";
}

echo "</table>";
echo "</div>";

echo "<hr>";
echo "<h3>💡 Solusi jika gambar error:</h3>";
echo "<ol style='line-height: 2;'>";
echo "<li>Pastikan bucket 'uploads' sudah dibuat di Supabase → <a href='create_storage_bucket.php'>Jalankan Create Bucket</a></li>";
echo "<li>Upload gambar ulang dari halaman Laporan</li>";
echo "<li>Atau update URL gambar di database jika file sudah ada di storage</li>";
echo "</ol>";

echo "<p><a href='index.php?page=laporan' style='padding: 10px 20px; background: #2196F3; color: white; text-decoration: none; border-radius: 5px;'>← Kembali ke Laporan</a></p>";
?>
