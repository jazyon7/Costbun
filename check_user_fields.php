<?php
require_once 'config/supabase_helper.php';

echo "<h2>🔍 Cek Struktur Tabel User</h2>";

// Get sample user data WITHOUT JOIN (karena id_kamar mungkin belum ada)
$response = supabase_request('GET', '/rest/v1/user?order=id_user.asc&limit=1');

// Debug response
echo "<div style='padding: 20px; background: #e3f2fd; border-radius: 8px; margin: 20px 0;'>";
echo "<h3>🔍 Debug Response:</h3>";
echo "<pre>" . json_encode($response, JSON_PRETTY_PRINT) . "</pre>";
echo "</div>";

// Check if error
if (isset($response['error']) && $response['error'] === true) {
    echo "<div style='padding: 20px; background: #f8d7da; border-radius: 8px; margin: 20px 0;'>";
    echo "<p style='color: red;'><strong>❌ Error dari Supabase:</strong></p>";
    echo "<p>" . ($response['message'] ?? 'Unknown error') . "</p>";
    if (isset($response['details'])) {
        echo "<pre>" . json_encode($response['details'], JSON_PRETTY_PRINT) . "</pre>";
    }
    echo "</div>";
    exit;
}

// Check if empty or not array
if (empty($response) || !is_array($response)) {
    echo "<p style='color: orange;'>⚠️ Tidak ada data user atau response invalid</p>";
    exit;
}

$sampleUser = $response[0];

echo "<div style='padding: 20px; background: #f5f5f5; border-radius: 8px; margin: 20px 0;'>";
echo "<h3>📋 Field yang tersedia di tabel user:</h3>";
echo "<pre>" . json_encode($sampleUser, JSON_PRETTY_PRINT) . "</pre>";
echo "</div>";

echo "<h3>✅ Daftar Field:</h3>";
echo "<ul style='line-height: 2;'>";
foreach (array_keys($sampleUser) as $field) {
    echo "<li><strong>$field</strong></li>";
}
echo "</ul>";

// Check critical fields
echo "<h3>🔍 Pengecekan Field Penting:</h3>";

// Check foto field
if (isset($sampleUser['foto']) || isset($sampleUser['foto_url']) || isset($sampleUser['gambar']) || isset($sampleUser['image'])) {
    echo "<div style='padding: 15px; background: #d4edda; border-left: 4px solid #28a745; margin: 10px 0;'>";
    echo "<strong>✅ foto_url:</strong> Field ditemukan!";
    echo "</div>";
} else {
    echo "<div style='padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107; margin: 10px 0;'>";
    echo "<strong>⚠️ foto_url:</strong> Field tidak ditemukan. Perlu ditambahkan ke database.";
    echo "<p style='margin: 10px 0 0 0; font-size: 14px;'>SQL: <code>ALTER TABLE \"user\" ADD COLUMN foto_url TEXT NULL;</code></p>";
    echo "</div>";
}

// Check id_kamar field
if (isset($sampleUser['id_kamar'])) {
    echo "<div style='padding: 15px; background: #d4edda; border-left: 4px solid #28a745; margin: 10px 0;'>";
    echo "<strong>✅ id_kamar:</strong> Field ditemukan!";
    if ($sampleUser['id_kamar'] !== null) {
        echo " <span style='color: #28a745;'>(Terisi: {$sampleUser['id_kamar']})</span>";
    } else {
        echo " <span style='color: #ffc107;'>(Masih NULL)</span>";
    }
    echo "</div>";
} else {
    echo "<div style='padding: 15px; background: #f8d7da; border-left: 4px solid #dc3545; margin: 10px 0;'>";
    echo "<strong>❌ id_kamar:</strong> Field tidak ditemukan. <strong>HARUS</strong> ditambahkan!";
    echo "<p style='margin: 10px 0 0 0; font-size: 14px;'>";
    echo "SQL:<br>";
    echo "<code style='display: block; background: #2d2d2d; color: #f8f8f2; padding: 10px; margin-top: 5px;'>";
    echo "ALTER TABLE \"user\" ADD COLUMN id_kamar INTEGER NULL;<br>";
    echo "ALTER TABLE \"user\" ADD CONSTRAINT fk_user_kamar FOREIGN KEY (id_kamar) REFERENCES kamar(id_kamar) ON DELETE SET NULL;";
    echo "</code>";
    echo "</p>";
    echo "</div>";
}

echo "<hr>";
echo "<p style='margin-top: 30px;'>";
echo "<a href='add_id_kamar_column.php' style='padding: 10px 20px; background: #dc3545; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;'>🔧 Panduan Tambah id_kamar</a>";
echo "<a href='index.php?page=data_kos' style='padding: 10px 20px; background: #2196F3; color: white; text-decoration: none; border-radius: 5px;'>← Kembali ke Data Kos</a>";
echo "</p>";
?>
