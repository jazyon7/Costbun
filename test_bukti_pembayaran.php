<?php
require_once 'config/supabase_helper.php';

echo "<h2>🔍 Test Akses Bukti Pembayaran</h2>";

// Get all tagihan with bukti pembayaran
$tagihanList = getTagihan();

if (empty($tagihanList)) {
    echo "<p style='color: orange;'>⚠️ Tidak ada data tagihan</p>";
    exit;
}

// Filter tagihan yang punya bukti pembayaran
$tagihanWithBukti = array_filter($tagihanList, function($tag) {
    return !empty($tag['bukti_pembayaran']);
});

if (empty($tagihanWithBukti)) {
    echo "<p style='color: orange;'>⚠️ Tidak ada tagihan dengan bukti pembayaran</p>";
    echo "<p>Total tagihan: " . count($tagihanList) . "</p>";
    exit;
}

echo "<p style='margin-bottom: 20px;'>Total tagihan dengan bukti: <strong>" . count($tagihanWithBukti) . "</strong> dari <strong>" . count($tagihanList) . "</strong></p>";

echo "<div style='padding: 20px; background: #f5f5f5; border-radius: 8px; margin: 20px 0;'>";
echo "<table style='width: 100%; border-collapse: collapse;'>";
echo "<tr style='background: #2196F3; color: white;'>";
echo "<th style='padding: 10px; border: 1px solid #ddd;'>ID</th>";
echo "<th style='padding: 10px; border: 1px solid #ddd;'>Penghuni</th>";
echo "<th style='padding: 10px; border: 1px solid #ddd;'>Jumlah</th>";
echo "<th style='padding: 10px; border: 1px solid #ddd;'>URL Bukti</th>";
echo "<th style='padding: 10px; border: 1px solid #ddd;'>Status URL</th>";
echo "<th style='padding: 10px; border: 1px solid #ddd;'>Preview</th>";
echo "</tr>";

foreach ($tagihanWithBukti as $tag) {
    $url = $tag['bukti_pembayaran'];
    $penghuni = isset($tag['user']['nama']) ? $tag['user']['nama'] : 'Unknown';
    
    // Check if URL is accessible
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true); // HEAD request only
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $status = $httpCode === 200 ? 
        "<span style='color: green; font-weight: bold;'>✅ OK ($httpCode)</span>" : 
        "<span style='color: red; font-weight: bold;'>❌ Error ($httpCode)</span>";
    
    $preview = $httpCode === 200 ? 
        "<img src='$url' style='max-width: 100px; max-height: 100px; border-radius: 4px;'>" : 
        "<span style='color: red;'>Tidak bisa dimuat</span>";
    
    // Check if URL pattern matches Supabase Storage
    $isSupabase = strpos($url, 'supabase.co/storage') !== false;
    $urlColor = $isSupabase ? 'color: blue;' : 'color: gray;';
    
    echo "<tr>";
    echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$tag['id_tagihan']}</td>";
    echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . htmlspecialchars($penghuni) . "</td>";
    echo "<td style='padding: 10px; border: 1px solid #ddd;'>Rp " . number_format($tag['jumlah'], 0, ',', '.') . "</td>";
    echo "<td style='padding: 10px; border: 1px solid #ddd; word-break: break-all; font-size: 11px; $urlColor'>";
    echo htmlspecialchars($url);
    if ($isSupabase) echo " <span style='color: green;'>✓ Supabase</span>";
    echo "</td>";
    echo "<td style='padding: 10px; border: 1px solid #ddd;'>$status</td>";
    echo "<td style='padding: 10px; border: 1px solid #ddd; text-align: center;'>$preview</td>";
    echo "</tr>";
}

echo "</table>";
echo "</div>";

echo "<hr>";
echo "<h3>💡 Informasi Storage Path:</h3>";
echo "<div style='background: #e3f2fd; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
echo "<p><strong>Path yang benar untuk Supabase Storage:</strong></p>";
echo "<code style='background: white; padding: 10px; display: block; border-radius: 4px; margin: 10px 0;'>";
echo "https://plngxhsvzrztfqnivztt.supabase.co/storage/v1/object/public/uploads/pembayaran/[filename].jpg";
echo "</code>";
echo "<p><strong>Folder struktur:</strong></p>";
echo "<ul style='margin-left: 20px;'>";
echo "<li>Bucket: <code>uploads</code></li>";
echo "<li>Folder laporan: <code>uploads/laporan/</code></li>";
echo "<li>Folder pembayaran: <code>uploads/pembayaran/</code></li>";
echo "</ul>";
echo "</div>";

echo "<h3>🔧 Solusi jika gambar error:</h3>";
echo "<ol style='line-height: 2;'>";
echo "<li>Pastikan bucket 'uploads' sudah dibuat → <a href='create_storage_bucket.php' style='color: #2196F3;'>Jalankan Create Bucket</a></li>";
echo "<li>Pastikan N8N upload ke path: <code>uploads/pembayaran/</code></li>";
echo "<li>Update URL di database jika path salah</li>";
echo "<li>Test ulang upload via Telegram/N8N</li>";
echo "</ol>";

echo "<p style='margin-top: 30px;'>";
echo "<a href='index.php?page=bukti_pembayaran' style='padding: 10px 20px; background: #2196F3; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;'>← Kembali ke Bukti Pembayaran</a>";
echo "<a href='test_gambar_laporan.php' style='padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px;'>Test Gambar Laporan</a>";
echo "</p>";
?>
