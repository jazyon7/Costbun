<?php
require_once 'config/supabase_helper.php';

echo "<h2>🔧 Assign Kamar ke User</h2>";

// Get all users (penghuni only)
$userList = getUser();
$penghuniList = array_filter($userList, function($user) {
    return strtolower($user['role']) === 'penghuni kos';
});

// Get all kamar
$kamarList = getKamar();

if (empty($penghuniList)) {
    echo "<p style='color: red;'>❌ Tidak ada penghuni kos</p>";
    exit;
}

if (empty($kamarList)) {
    echo "<p style='color: red;'>❌ Tidak ada data kamar</p>";
    exit;
}

echo "<div style='padding: 20px; background: #f5f5f5; border-radius: 8px; margin: 20px 0;'>";
echo "<p>Total Penghuni: <strong>" . count($penghuniList) . "</strong></p>";
echo "<p>Total Kamar: <strong>" . count($kamarList) . "</strong></p>";
echo "</div>";

$success = 0;
$failed = 0;

echo "<h3>📋 Proses Assign Kamar:</h3>";
echo "<div style='padding: 20px; background: #fff; border-radius: 8px; margin: 20px 0;'>";

// Assign kamar ke penghuni secara random
$kamarIndex = 0;
foreach ($penghuniList as $penghuni) {
    // Skip if already has kamar
    if (!empty($penghuni['id_kamar'])) {
        echo "<p style='color: orange;'>⚠️ {$penghuni['nama']} sudah punya kamar (ID: {$penghuni['id_kamar']})</p>";
        continue;
    }
    
    // Get next available kamar
    if ($kamarIndex < count($kamarList)) {
        $kamar = $kamarList[$kamarIndex];
        
        // Update user dengan id_kamar
        $result = updateUser($penghuni['id_user'], [
            'id_kamar' => $kamar['id_kamar']
        ]);
        
        if (!isset($result['error'])) {
            $success++;
            echo "<p style='color: green;'>✅ {$penghuni['nama']} → <strong>{$kamar['nama']}</strong> (Rp " . number_format($kamar['harga'], 0, ',', '.') . ")</p>";
            $kamarIndex++;
        } else {
            $failed++;
            echo "<p style='color: red;'>❌ Gagal assign {$penghuni['nama']}: " . ($result['message'] ?? 'Unknown error') . "</p>";
        }
    } else {
        echo "<p style='color: orange;'>⚠️ Kamar habis, {$penghuni['nama']} belum dapat kamar</p>";
    }
}

echo "</div>";

echo "<div style='padding: 20px; background: #d4edda; border-radius: 8px; margin: 20px 0;'>";
echo "<h3 style='color: #155724;'>📊 Hasil:</h3>";
echo "<p><strong>Berhasil:</strong> $success user</p>";
echo "<p><strong>Gagal:</strong> $failed user</p>";
echo "</div>";

// Show hasil
echo "<h3>✅ Daftar User dengan Kamar:</h3>";
$updatedUserList = getUser();
$usersWithKamar = array_filter($updatedUserList, function($user) {
    return !empty($user['id_kamar']);
});

if (!empty($usersWithKamar)) {
    echo "<div style='padding: 20px; background: #fff; border-radius: 8px; margin: 20px 0; overflow-x: auto;'>";
    echo "<table style='width: 100%; border-collapse: collapse;'>";
    echo "<tr style='background: #2196F3; color: white;'>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Nama</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Kamar</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Role</th>";
    echo "<th style='padding: 10px; border: 1px solid #ddd;'>Email</th>";
    echo "</tr>";
    
    foreach ($usersWithKamar as $user) {
        $namaKamar = isset($user['kamar']['nama']) ? $user['kamar']['nama'] : 'ID: ' . $user['id_kamar'];
        echo "<tr>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$user['nama']}</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'><strong>$namaKamar</strong></td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$user['role']}</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$user['email']}</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    echo "</div>";
} else {
    echo "<p style='color: orange;'>⚠️ Belum ada user yang memiliki kamar</p>";
}

echo "<hr>";
echo "<p style='margin-top: 30px;'>";
echo "<a href='index.php?page=data_kos' style='padding: 10px 20px; background: #2196F3; color: white; text-decoration: none; border-radius: 5px;'>← Lihat Data Kos</a>";
echo "</p>";
?>
