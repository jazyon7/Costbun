<?php
require_once __DIR__ . '/config/supabase_helper.php';

// Reset kamar ID 1 untuk testing
$result = updateKamar(1, [
    'id_user' => null,
    'status' => 'kosong'
]);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Reset Kamar 001</title>
    <style>
        body { font-family: Arial; padding: 40px; background: #f5f5f5; }
        .box { background: white; padding: 30px; border-radius: 10px; max-width: 600px; margin: 0 auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #28a745; font-size: 24px; font-weight: bold; }
        .error { color: #dc3545; font-size: 24px; font-weight: bold; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
        button { background: #667eea; color: white; border: none; padding: 12px 24px; border-radius: 5px; cursor: pointer; font-size: 16px; margin: 10px 5px; }
        button:hover { background: #5568d3; }
    </style>
</head>
<body>
    <div class='box'>
        <h1>🔄 Reset Kamar 001</h1>";

if (isset($result['error'])) {
    echo "<p class='error'>❌ Gagal reset kamar</p>";
    echo "<pre>" . print_r($result, true) . "</pre>";
} else {
    echo "<p class='success'>✅ Kamar 001 berhasil direset!</p>";
    echo "<pre>" . print_r($result, true) . "</pre>";
    
    // Verify
    $kamar = getKamar(1);
    echo "<h3>Verify Data:</h3>";
    echo "<pre>" . print_r($kamar, true) . "</pre>";
    
    if ($kamar['id_user'] === null && $kamar['status'] === 'kosong') {
        echo "<p class='success'>✅ Verifikasi: Data berhasil direset</p>";
    } else {
        echo "<p class='error'>⚠️ Warning: Data belum direset dengan benar</p>";
    }
}

echo "
        <hr style='margin: 30px 0;'>
        <h3>Quick Actions:</h3>
        <button onclick=\"window.location.href='index.php?page=data_kamar'\">📋 Lihat Data Kamar</button>
        <button onclick=\"window.location.href='test_update_kamar.php'\">🧪 Test Update</button>
        <button onclick=\"window.location.href='debug_assign_penghuni.php'\">🔍 Debug Assign</button>
        <button onclick=\"window.location.reload()\">🔄 Reset Lagi</button>
    </div>
</body>
</html>";
?>
