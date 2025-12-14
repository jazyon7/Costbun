<?php
require_once __DIR__ . '/config/supabase_helper.php';

echo "<h1>🔍 Debug Insert Kamar</h1>";
echo "<style>body{font-family:Arial;padding:20px;} .success{color:green;} .error{color:red;} pre{background:#f5f5f5;padding:10px;border-radius:5px;}</style>";

// Test insert 1 kamar
$testData = [
    'nama' => 'TEST-01',
    'kasur' => 1,
    'kipas' => 1,
    'lemari' => 1,
    'keranjang_sampah' => 1,
    'ac' => 0,
    'harga' => 500000,
    'status' => 'kosong'
];

echo "<h2>Test Data:</h2>";
echo "<pre>" . print_r($testData, true) . "</pre>";

echo "<h2>Proses Insert:</h2>";
$result = createKamar($testData);

if ($result) {
    echo "<p class='success'>✓ Insert berhasil!</p>";
    echo "<pre>" . print_r($result, true) . "</pre>";
} else {
    echo "<p class='error'>✗ Insert gagal!</p>";
    echo "<p>Result: <pre>" . print_r($result, true) . "</pre></p>";
}

// Cek struktur yang dikirim ke API
echo "<h2>Debug API Request:</h2>";
echo "<p>Coba cek manual dengan curl atau Postman</p>";

// Test get kamar
echo "<h2>Test Get Kamar:</h2>";
$kamarList = getKamar();
if ($kamarList) {
    echo "<p class='success'>✓ Get kamar berhasil! Total: " . count($kamarList) . "</p>";
    if (count($kamarList) > 0) {
        echo "<pre>" . print_r($kamarList[0], true) . "</pre>";
    }
} else {
    echo "<p class='error'>✗ Get kamar gagal!</p>";
}

// Cek langsung ke API
echo "<h2>Test Direct API Call:</h2>";
$apiUrl = "http://localhost/Costbun/api/kamar.php?action=create&nama=DEBUG-01&kasur=1&kipas=1&lemari=1&keranjang_sampah=1&ac=0&harga=500000&status=kosong";
echo "<p>URL: <a href='$apiUrl' target='_blank'>Test API</a></p>";
?>
