<?php
require_once __DIR__ . '/config/supabase_request.php';
require_once __DIR__ . '/config/supabase.php';

echo "<h1>🔍 Debug Supabase API - Kamar</h1>";
echo "<style>body{font-family:Arial;padding:20px;} .success{color:green;} .error{color:red;} pre{background:#f5f5f5;padding:10px;border-radius:5px;overflow-x:auto;}</style>";

// Test data
$testData = [
    'nama' => 'DEBUG-01',
    'kasur' => 1,
    'kipas' => 1,
    'lemari' => 1,
    'keranjang_sampah' => 1,
    'ac' => 0,
    'harga' => 500000,
    'status' => 'kosong'
];

echo "<h2>1. Test GET kamar (untuk lihat struktur):</h2>";
$getResult = supabase_request('GET', '/rest/v1/kamar?limit=1');
echo "<pre>" . print_r($getResult, true) . "</pre>";

echo "<h2>2. Test Data yang akan di-POST:</h2>";
echo "<pre>" . print_r($testData, true) . "</pre>";

echo "<h2>3. Test POST kamar:</h2>";
$postResult = supabase_request('POST', '/rest/v1/kamar', $testData);
echo "<pre>" . print_r($postResult, true) . "</pre>";

if (isset($postResult['error'])) {
    echo "<p class='error'>Error detected!</p>";
} else if (is_array($postResult) && count($postResult) > 0) {
    echo "<p class='success'>✓ Berhasil!</p>";
} else {
    echo "<p class='error'>✗ Gagal! Response kosong atau format salah</p>";
}

// Cek struktur tabel dari Supabase
echo "<h2>4. Info Supabase Config:</h2>";
echo "<p>URL: " . SUPABASE_URL . "</p>";
echo "<p>API Key: " . substr(SUPABASE_API_KEY, 0, 20) . "...</p>";

echo "<h2>5. Kemungkinan Masalah:</h2>";
echo "<ul>";
echo "<li>Nama kolom di tabel 'kamar' tidak sesuai</li>";
echo "<li>Ada kolom required yang belum diisi</li>";
echo "<li>Primary key 'id_kamar' mungkin perlu auto-generated</li>";
echo "<li>Permission RLS (Row Level Security) di Supabase</li>";
echo "</ul>";

echo "<h2>6. Solusi:</h2>";
echo "<p>Cek di Supabase Dashboard:</p>";
echo "<ol>";
echo "<li>Buka Table Editor → kamar</li>";
echo "<li>Lihat nama kolom yang benar</li>";
echo "<li>Pastikan 'id_kamar' adalah Primary Key dengan auto-increment</li>";
echo "<li>Cek RLS (Row Level Security) - disable dulu untuk testing</li>";
echo "</ol>";
?>
