<?php
require_once 'config/supabase.php';

echo "<h2>🪣 Create Supabase Storage Bucket</h2>";

// Create bucket "uploads" with public access
$url = SUPABASE_URL . "/storage/v1/bucket";

$bucketData = [
    'id' => 'uploads',
    'name' => 'uploads',
    'public' => true,
    'file_size_limit' => 52428800, // 50MB
    'allowed_mime_types' => ['image/jpeg', 'image/jpg', 'image/png', 'image/gif']
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($bucketData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . SUPABASE_API_KEY,
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "<div style='padding: 20px; background: #f5f5f5; border-radius: 8px; margin: 20px 0;'>";

if ($httpCode === 200 || $httpCode === 201) {
    echo "<div style='color: green; font-size: 18px; font-weight: bold;'>✅ Bucket 'uploads' berhasil dibuat!</div>";
    echo "<pre>" . json_encode(json_decode($response), JSON_PRETTY_PRINT) . "</pre>";
} else if ($httpCode === 409) {
    echo "<div style='color: orange; font-size: 18px; font-weight: bold;'>⚠️ Bucket 'uploads' sudah ada</div>";
    echo "<pre>" . $response . "</pre>";
} else {
    echo "<div style='color: red; font-size: 18px; font-weight: bold;'>❌ Gagal membuat bucket</div>";
    echo "<p><strong>HTTP Code:</strong> $httpCode</p>";
    echo "<p><strong>Response:</strong></p>";
    echo "<pre>" . $response . "</pre>";
    if ($error) {
        echo "<p><strong>Error:</strong> $error</p>";
    }
}

echo "</div>";

echo "<p style='color: #666; font-style: italic; margin: 20px 0;'>💡 Catatan: Bucket 'uploads' akan memiliki 2 folder: <strong>laporan/</strong> dan <strong>pembayaran/</strong></p>";

// List all buckets
echo "<h3>📋 Daftar Bucket yang Ada:</h3>";

$listUrl = SUPABASE_URL . "/storage/v1/bucket";
$ch = curl_init($listUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . SUPABASE_API_KEY
]);

$listResponse = curl_exec($ch);
$listHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($listHttpCode === 200) {
    $buckets = json_decode($listResponse, true);
    if (!empty($buckets)) {
        echo "<div style='padding: 20px; background: #e8f5e9; border-radius: 8px; margin: 20px 0;'>";
        echo "<table style='width: 100%; border-collapse: collapse;'>";
        echo "<tr style='background: #4caf50; color: white;'>";
        echo "<th style='padding: 10px; border: 1px solid #ddd;'>ID</th>";
        echo "<th style='padding: 10px; border: 1px solid #ddd;'>Name</th>";
        echo "<th style='padding: 10px; border: 1px solid #ddd;'>Public</th>";
        echo "<th style='padding: 10px; border: 1px solid #ddd;'>Created</th>";
        echo "</tr>";
        foreach ($buckets as $bucket) {
            $isPublic = isset($bucket['public']) && $bucket['public'] ? '✅ Yes' : '❌ No';
            echo "<tr>";
            echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$bucket['id']}</td>";
            echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$bucket['name']}</td>";
            echo "<td style='padding: 10px; border: 1px solid #ddd;'>$isPublic</td>";
            echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$bucket['created_at']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    } else {
        echo "<p style='color: orange;'>⚠️ Belum ada bucket yang dibuat</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Gagal mengambil daftar bucket (HTTP $listHttpCode)</p>";
}

echo "<hr>";
echo "<p><a href='index.php?page=laporan' style='padding: 10px 20px; background: #2196F3; color: white; text-decoration: none; border-radius: 5px;'>← Kembali ke Laporan</a></p>";
?>
