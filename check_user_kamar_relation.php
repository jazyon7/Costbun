<?php
require_once 'config/supabase_helper.php';

echo "<h2>🔍 Cek Relasi User dan Kamar</h2>";

// Get sample user data
$userList = getUser();

if (empty($userList)) {
    echo "<p style='color: orange;'>⚠️ Tidak ada data user</p>";
    exit;
}

$sampleUser = $userList[0];

echo "<div style='padding: 20px; background: #f5f5f5; border-radius: 8px; margin: 20px 0;'>";
echo "<h3>📋 Struktur Data User:</h3>";
echo "<pre>" . json_encode($sampleUser, JSON_PRETTY_PRINT) . "</pre>";
echo "</div>";

// Check if id_kamar field exists
if (isset($sampleUser['id_kamar'])) {
    echo "<div style='padding: 20px; background: #d4edda; border-radius: 8px; margin: 20px 0;'>";
    echo "<p style='color: green;'>✅ Field <strong>id_kamar</strong> ditemukan di tabel user!</p>";
    echo "<p>ID Kamar: <strong>" . ($sampleUser['id_kamar'] ?? 'NULL') . "</strong></p>";
    echo "</div>";
} else {
    echo "<div style='padding: 20px; background: #fff3cd; border-radius: 8px; margin: 20px 0;'>";
    echo "<p style='color: orange;'>⚠️ Field <strong>id_kamar</strong> tidak ditemukan di tabel user</p>";
    echo "<p><strong>Solusi:</strong></p>";
    echo "<ol>";
    echo "<li>Tambah kolom <code>id_kamar</code> di tabel user (INTEGER, nullable)</li>";
    echo "<li>Atau buat tabel relasi user_kamar untuk many-to-many</li>";
    echo "</ol>";
    echo "</div>";
}

// Get kamar list
echo "<h3>📋 Daftar Kamar:</h3>";
$kamarList = getKamar();

if (!empty($kamarList)) {
    echo "<div style='padding: 20px; background: #e3f2fd; border-radius: 8px; margin: 20px 0; overflow-x: auto;'>";
    echo "<table style='width: 100%; border-collapse: collapse;'>";
    echo "<tr style='background: #2196F3; color: white;'>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>ID</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Nama Kamar</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Harga</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Status</th>";
    echo "</tr>";
    
    foreach (array_slice($kamarList, 0, 10) as $kamar) {
        echo "<tr>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$kamar['id_kamar']}</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'><strong>{$kamar['nama']}</strong></td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>Rp " . number_format($kamar['harga'], 0, ',', '.') . "</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$kamar['status']}</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    echo "</div>";
} else {
    echo "<p style='color: orange;'>⚠️ Tidak ada data kamar</p>";
}

// Check user dengan kamar
echo "<h3>👥 Daftar User dengan Kamar:</h3>";
$usersWithKamar = array_filter($userList, function($user) {
    return !empty($user['id_kamar']);
});

if (!empty($usersWithKamar)) {
    echo "<div style='padding: 20px; background: #d4edda; border-radius: 8px; margin: 20px 0;'>";
    echo "<p><strong>" . count($usersWithKamar) . "</strong> user memiliki kamar</p>";
    echo "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>";
    echo "<tr style='background: #28a745; color: white;'>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Nama</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>ID Kamar</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Role</th>";
    echo "</tr>";
    
    foreach ($usersWithKamar as $user) {
        echo "<tr>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$user['nama']}</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'><strong>{$user['id_kamar']}</strong></td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$user['role']}</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    echo "</div>";
} else {
    echo "<div style='padding: 20px; background: #fff3cd; border-radius: 8px; margin: 20px 0;'>";
    echo "<p style='color: orange;'>⚠️ Belum ada user yang memiliki kamar</p>";
    echo "<p>Perlu update data user dengan id_kamar yang sesuai</p>";
    echo "</div>";
}

echo "<hr>";
echo "<p><a href='index.php?page=data_kos' style='padding: 10px 20px; background: #2196F3; color: white; text-decoration: none; border-radius: 5px;'>← Kembali ke Data Kos</a></p>";
?>
