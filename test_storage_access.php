<!DOCTYPE html>
<html>
<head>
    <title>Test Supabase Storage Access</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .test-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .test-section h2 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .image-test {
            margin: 15px 0;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
        }
        .image-test img {
            max-width: 400px;
            border: 2px solid #ddd;
            border-radius: 5px;
            display: block;
            margin: 10px 0;
        }
        .status {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            margin: 5px 0;
        }
        .status.success {
            background: #d4edda;
            color: #155724;
        }
        .status.error {
            background: #f8d7da;
            color: #721c24;
        }
        .url-display {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 12px;
            word-break: break-all;
            margin: 5px 0;
        }
        .btn {
            padding: 8px 16px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }
        .btn:hover {
            background: #5568d3;
        }
    </style>
</head>
<body>

<h1>🔍 Test Supabase Storage Access</h1>

<?php
require_once __DIR__ . '/config/supabase_helper.php';

echo "<div class='test-section'>";
echo "<h2>📊 Storage Configuration</h2>";
echo "<p><strong>Supabase URL:</strong> " . SUPABASE_URL . "</p>";
echo "<p><strong>Storage Bucket:</strong> uploads</p>";
echo "<p><strong>Folder:</strong> pembayaran</p>";
echo "</div>";

// Test 1: Get tagihan with bukti_pembayaran
echo "<div class='test-section'>";
echo "<h2>📋 Test 1: Data dari Database</h2>";
$tagihanList = getTagihan();

$tagihanWithBukti = [];
if (is_array($tagihanList)) {
    foreach ($tagihanList as $tag) {
        if (!empty($tag['bukti_pembayaran'])) {
            $tagihanWithBukti[] = $tag;
        }
    }
}

echo "<p><strong>Total tagihan dengan bukti:</strong> " . count($tagihanWithBukti) . "</p>";

if (empty($tagihanWithBukti)) {
    echo "<p style='color: orange;'>⚠️ Tidak ada tagihan dengan bukti pembayaran di database</p>";
} else {
    echo "<p style='color: green;'>✅ Ada " . count($tagihanWithBukti) . " tagihan dengan bukti pembayaran</p>";
    
    // Show first few
    $limit = min(3, count($tagihanWithBukti));
    for ($i = 0; $i < $limit; $i++) {
        $tag = $tagihanWithBukti[$i];
        echo "<div class='image-test'>";
        echo "<p><strong>Tagihan ID:</strong> " . $tag['id_tagihan'] . "</p>";
        echo "<p><strong>Status:</strong> " . $tag['status_pembayaran'] . "</p>";
        echo "<div class='url-display'>" . htmlspecialchars($tag['bukti_pembayaran']) . "</div>";
        echo "</div>";
    }
}
echo "</div>";

// Test 2: Image Loading Test
if (!empty($tagihanWithBukti)) {
    echo "<div class='test-section'>";
    echo "<h2>🖼️ Test 2: Loading Images</h2>";
    echo "<p>Mencoba load gambar langsung dari Supabase Storage:</p>";
    
    $limit = min(5, count($tagihanWithBukti));
    for ($i = 0; $i < $limit; $i++) {
        $tag = $tagihanWithBukti[$i];
        $url = $tag['bukti_pembayaran'];
        
        echo "<div class='image-test'>";
        echo "<p><strong>Tagihan #{$tag['id_tagihan']}</strong></p>";
        echo "<div class='url-display'>" . htmlspecialchars($url) . "</div>";
        
        // Try to load image
        echo "<p><strong>Test Load Image:</strong></p>";
        echo "<img src='" . htmlspecialchars($url) . "' alt='Bukti #{$tag['id_tagihan']}' ";
        echo "onerror=\"this.parentElement.querySelector('.img-status').className='status error'; this.parentElement.querySelector('.img-status').textContent='❌ Gagal Load';\" ";
        echo "onload=\"this.parentElement.querySelector('.img-status').className='status success'; this.parentElement.querySelector('.img-status').textContent='✅ Berhasil Load';\">";
        echo "<div class='img-status status'>⏳ Loading...</div>";
        
        echo "<a href='" . htmlspecialchars($url) . "' target='_blank' class='btn'>Buka di Tab Baru</a>";
        echo "</div>";
    }
    echo "</div>";
}

// Test 3: Check bucket accessibility
echo "<div class='test-section'>";
echo "<h2>🔐 Test 3: Bucket Policy Check</h2>";
echo "<p>Testing public access ke Supabase Storage bucket...</p>";

// Try to access a known URL
if (!empty($tagihanWithBukti)) {
    $testUrl = $tagihanWithBukti[0]['bukti_pembayaran'];
    
    echo "<div class='url-display'>" . htmlspecialchars($testUrl) . "</div>";
    
    // Test with curl
    $ch = curl_init($testUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "<p><strong>HTTP Response Code:</strong> $httpCode</p>";
    
    if ($httpCode == 200) {
        echo "<p class='status success'>✅ File accessible! Storage bucket is PUBLIC</p>";
    } else if ($httpCode == 403) {
        echo "<p class='status error'>❌ Access Denied (403) - Bucket might be PRIVATE</p>";
        echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin-top: 10px;'>";
        echo "<strong>⚠️ Action Required:</strong><br>";
        echo "1. Buka Supabase Dashboard → Storage<br>";
        echo "2. Pilih bucket 'uploads'<br>";
        echo "3. Klik Settings/Policies<br>";
        echo "4. Pastikan bucket policy: <code>PUBLIC</code> atau buat policy untuk read access<br>";
        echo "</div>";
    } else {
        echo "<p class='status error'>❌ HTTP $httpCode - Check URL or network</p>";
    }
} else {
    echo "<p style='color: orange;'>⚠️ Tidak ada data untuk test</p>";
}

echo "</div>";

// Test 4: Test URL patterns
echo "<div class='test-section'>";
echo "<h2>🔗 Test 4: URL Pattern Analysis</h2>";

if (!empty($tagihanWithBukti)) {
    $sampleUrl = $tagihanWithBukti[0]['bukti_pembayaran'];
    $expectedPattern = SUPABASE_URL . "/storage/v1/object/public/uploads/pembayaran/";
    
    echo "<p><strong>Expected Pattern:</strong></p>";
    echo "<div class='url-display'>" . htmlspecialchars($expectedPattern) . "FILENAME.jpg</div>";
    
    echo "<p><strong>Actual URL:</strong></p>";
    echo "<div class='url-display'>" . htmlspecialchars($sampleUrl) . "</div>";
    
    if (strpos($sampleUrl, '/storage/v1/object/public/') !== false) {
        echo "<p class='status success'>✅ URL pattern correct (using PUBLIC path)</p>";
    } else {
        echo "<p class='status error'>❌ URL pattern incorrect</p>";
    }
} else {
    echo "<p style='color: orange;'>⚠️ Tidak ada data untuk test</p>";
}

echo "</div>";
?>

<div class='test-section'>
    <h2>📚 Quick Actions</h2>
    <a href='index.php?page=bukti_pembayaran' class='btn'>← Bukti Pembayaran Page</a>
    <a href='index.php?page=profil' class='btn'>👤 Profil</a>
    <a href='debug_bukti_pembayaran.php' class='btn'>🔧 Debug Data</a>
</div>

</body>
</html>
