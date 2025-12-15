<!DOCTYPE html>
<html>
<head>
    <title>Test CRUD Data Penghuni</title>
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
        .status.info {
            background: #d1ecf1;
            color: #0c5460;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background: #f8f9fa;
            font-weight: bold;
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
        .btn-danger {
            background: #dc3545;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        img {
            max-width: 100px;
            border-radius: 5px;
        }
        .log {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 12px;
            margin: 10px 0;
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
</head>
<body>

<h1>🧪 Test CRUD Data Penghuni</h1>

<?php
require_once __DIR__ . '/config/supabase_helper.php';
session_start();

// Set session sebagai admin untuk testing
if (!isset($_SESSION['id_user'])) {
    $_SESSION['id_user'] = 1;
    $_SESSION['role'] = 'admin';
    $_SESSION['nama'] = 'Test Admin';
}

echo "<div class='test-section'>";
echo "<h2>📊 Session Info</h2>";
echo "<p><strong>User ID:</strong> " . $_SESSION['id_user'] . "</p>";
echo "<p><strong>Role:</strong> " . $_SESSION['role'] . "</p>";
echo "<p><strong>Nama:</strong> " . $_SESSION['nama'] . "</p>";
echo "</div>";

// Test 1: READ - Get All Users
echo "<div class='test-section'>";
echo "<h2>📋 Test 1: READ - Get All Users</h2>";

$userList = getUser();

if (is_array($userList) && !isset($userList['error'])) {
    echo "<p class='status success'>✅ Berhasil mengambil " . count($userList) . " user</p>";
    
    echo "<table>";
    echo "<thead><tr><th>ID</th><th>Foto</th><th>Nama</th><th>Email</th><th>Role</th><th>Kamar</th><th>Aksi</th></tr></thead>";
    echo "<tbody>";
    
    foreach ($userList as $user) {
        echo "<tr>";
        echo "<td>{$user['id_user']}</td>";
        echo "<td>";
        if (!empty($user['foto_url'])) {
            echo "<img src='{$user['foto_url']}' alt='Foto' onerror=\"this.src='https://ui-avatars.com/api/?name=" . urlencode($user['nama']) . "&background=667eea&color=fff'\">";
        } else {
            echo "<img src='https://ui-avatars.com/api/?name=" . urlencode($user['nama']) . "&background=667eea&color=fff' alt='Avatar'>";
        }
        echo "</td>";
        echo "<td>{$user['nama']}</td>";
        echo "<td>{$user['email']}</td>";
        echo "<td>{$user['role']}</td>";
        echo "<td>" . ($user['id_kamar'] ?? '-') . "</td>";
        echo "<td>";
        if ($user['role'] !== 'admin') {
            echo "<a href='?action=test_delete&id={$user['id_user']}' class='btn btn-danger' onclick='return confirm(\"Yakin hapus {$user['nama']}?\")'>🗑️ Test Delete</a>";
        }
        echo "</td>";
        echo "</tr>";
    }
    
    echo "</tbody></table>";
} else {
    echo "<p class='status error'>❌ Gagal mengambil data user</p>";
    if (isset($userList['error'])) {
        echo "<div class='log'>" . json_encode($userList, JSON_PRETTY_PRINT) . "</div>";
    }
}

echo "</div>";

// Test 2: CREATE - Test via API
echo "<div class='test-section'>";
echo "<h2>➕ Test 2: CREATE User</h2>";
echo "<p>Test create user bisa dilakukan melalui halaman data_kos dengan klik tombol 'Tambah User'</p>";
echo "<a href='index.php?page=data_kos' class='btn'>🔗 Buka Halaman Data Kos</a>";
echo "</div>";

// Test 3: UPDATE - Test via API
echo "<div class='test-section'>";
echo "<h2>✏️ Test 3: UPDATE User</h2>";
echo "<p>Test update user bisa dilakukan melalui halaman data_kos dengan klik tombol 'Edit' pada user</p>";
echo "<a href='index.php?page=data_kos' class='btn'>🔗 Buka Halaman Data Kos</a>";
echo "</div>";

// Test 4: DELETE - Real Test
if (isset($_GET['action']) && $_GET['action'] === 'test_delete' && isset($_GET['id'])) {
    echo "<div class='test-section'>";
    echo "<h2>🗑️ Test 4: DELETE User</h2>";
    
    $userId = $_GET['id'];
    
    // Get user data first
    $user = getUser($userId);
    
    if ($user) {
        echo "<p><strong>User yang akan dihapus:</strong></p>";
        echo "<div class='log'>" . json_encode($user, JSON_PRETTY_PRINT) . "</div>";
        
        // Delete foto from storage if exists
        $foto_url = $user['foto_url'] ?? '';
        if ($foto_url && strpos($foto_url, 'supabase.co') !== false) {
            preg_match('/uploads\/data_diri\/(.+)$/', $foto_url, $matches);
            if (isset($matches[0])) {
                $deleteStorageResult = deleteFromSupabaseStorage('uploads', $matches[0]);
                echo "<p class='status info'>📦 Delete foto from storage: " . ($deleteStorageResult ? 'Success' : 'Failed') . "</p>";
            }
        }
        
        // Get kamar
        $id_kamar = $user['id_kamar'] ?? null;
        
        // Delete user
        $result = deleteUser($userId);
        
        if (!isset($result['error'])) {
            echo "<p class='status success'>✅ User berhasil dihapus dari database</p>";
            
            // Update kamar
            if ($id_kamar) {
                $kamarUpdate = updateKamar($id_kamar, [
                    'id_user' => null,
                    'status' => 'kosong'
                ]);
                echo "<p class='status info'>🚪 Kamar #{$id_kamar} diupdate menjadi kosong</p>";
            }
            
            echo "<a href='test_crud_data_kos.php' class='btn'>🔄 Refresh</a>";
        } else {
            echo "<p class='status error'>❌ Gagal menghapus user</p>";
            echo "<div class='log'>" . json_encode($result, JSON_PRETTY_PRINT) . "</div>";
        }
    } else {
        echo "<p class='status error'>❌ User tidak ditemukan</p>";
    }
    
    echo "</div>";
}

// Test 5: Storage Test
echo "<div class='test-section'>";
echo "<h2>📦 Test 5: Supabase Storage</h2>";
echo "<p><strong>Bucket:</strong> uploads</p>";
echo "<p><strong>Folder:</strong> data_diri</p>";

// Cek foto yang ada di database
$usersWithPhoto = array_filter($userList, function($user) {
    return !empty($user['foto_url']);
});

echo "<p><strong>User dengan foto:</strong> " . count($usersWithPhoto) . "</p>";

if (!empty($usersWithPhoto)) {
    echo "<table>";
    echo "<thead><tr><th>Nama</th><th>URL Foto</th><th>Status</th></tr></thead>";
    echo "<tbody>";
    
    foreach ($usersWithPhoto as $user) {
        $url = $user['foto_url'];
        
        // Test URL accessibility
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $statusClass = ($httpCode == 200) ? 'success' : 'error';
        $statusText = ($httpCode == 200) ? "✅ OK ($httpCode)" : "❌ Error ($httpCode)";
        
        echo "<tr>";
        echo "<td>{$user['nama']}</td>";
        echo "<td><a href='$url' target='_blank'>" . basename($url) . "</a></td>";
        echo "<td><span class='status $statusClass'>$statusText</span></td>";
        echo "</tr>";
    }
    
    echo "</tbody></table>";
}

echo "</div>";

// Test 6: Integration dengan n8n
echo "<div class='test-section'>";
echo "<h2>🔄 Test 6: Integrasi n8n</h2>";
echo "<p>Untuk test integrasi dengan n8n:</p>";
echo "<ol>";
echo "<li>Pastikan n8n workflow sudah running</li>";
echo "<li>Kirim data user baru melalui n8n webhook</li>";
echo "<li>Data seharusnya langsung muncul di tabel user</li>";
echo "<li>Foto KTP/KTM seharusnya masuk ke Supabase Storage folder <code>uploads/data_diri</code></li>";
echo "</ol>";

echo "<p><strong>Endpoint API:</strong></p>";
echo "<div class='log'>";
echo "POST: " . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . "://" . $_SERVER['HTTP_HOST'] . "/Costbun/api/user.php?action=create\n\n";
echo "Headers:\n";
echo "Content-Type: multipart/form-data (untuk upload file)\n";
echo "atau\n";
echo "Content-Type: application/json (tanpa file)\n\n";
echo "Body (JSON):\n";
echo json_encode([
    'nama' => 'Nama User',
    'email' => 'email@example.com',
    'username' => 'username',
    'password' => 'password123',
    'nomor' => '08123456789',
    'role' => 'penghuni kos',
    'alamat' => 'Alamat lengkap',
    'ktp_ktm' => '1234567890123456',
    'telegram_id' => '@username',
    'id_kamar' => 1
], JSON_PRETTY_PRINT);
echo "</div>";

echo "</div>";
?>

<div class='test-section'>
    <h2>📚 Quick Links</h2>
    <a href='index.php?page=data_kos' class='btn'>🏠 Data Kos</a>
    <a href='test_storage_access.php' class='btn'>📦 Test Storage</a>
    <a href='test_crud_data_kos.php' class='btn'>🔄 Refresh Test</a>
</div>

</body>
</html>
