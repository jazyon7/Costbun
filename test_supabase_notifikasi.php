<?php
require_once __DIR__ . '/config/supabase_request.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Direct Supabase - Notifikasi</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #667eea;
            margin-bottom: 30px;
        }
        .section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .success {
            color: #28a745;
            font-weight: bold;
        }
        .error {
            color: #dc3545;
            font-weight: bold;
        }
        pre {
            background: #2d2d30;
            color: #d4d4d4;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            max-height: 500px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #667eea;
            color: white;
        }
        tr:hover {
            background: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Test Direct Supabase API - Notifikasi</h1>
        
        <div class="section">
            <h2>1. Test Simple Query (Without Join)</h2>
            <?php
            try {
                $response = supabase_request('GET', '/rest/v1/notifikasi?order=id_notif.desc&limit=5');
                
                if (is_array($response) && !empty($response)) {
                    $count = count($response);
                    echo "<p class='success'>✅ Berhasil! Ditemukan $count notifikasi</p>";
                    
                    echo "<table>";
                    echo "<tr><th>ID</th><th>Tipe</th><th>Judul</th><th>ID User</th><th>Status</th><th>Tanggal</th></tr>";
                    foreach ($response as $notif) {
                        echo "<tr>";
                        echo "<td>{$notif['id_notif']}</td>";
                        echo "<td>{$notif['tipe']}</td>";
                        echo "<td>{$notif['judul']}</td>";
                        echo "<td>{$notif['id_user']}</td>";
                        echo "<td>{$notif['status']}</td>";
                        echo "<td>{$notif['tanggal_kirim']}</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    
                    echo "<h3>Raw Data:</h3>";
                    echo "<pre>" . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
                } else {
                    echo "<p class='error'>❌ Tidak ada data atau error</p>";
                    echo "<pre>" . print_r($response, true) . "</pre>";
                }
            } catch (Exception $e) {
                echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
            }
            ?>
        </div>
        
        <div class="section">
            <h2>2. Test Query With Join (user)</h2>
            <?php
            try {
                $response = supabase_request('GET', '/rest/v1/notifikasi?select=*,user(nama,role)&order=id_notif.desc&limit=5');
                
                if (is_array($response) && !empty($response)) {
                    $count = count($response);
                    echo "<p class='success'>✅ Berhasil! Ditemukan $count notifikasi dengan data user</p>";
                    
                    echo "<table>";
                    echo "<tr><th>ID</th><th>Tipe</th><th>Judul</th><th>Nama User</th><th>Role</th><th>Status</th></tr>";
                    foreach ($response as $notif) {
                        $userName = isset($notif['user']['nama']) ? $notif['user']['nama'] : 'N/A';
                        $userRole = isset($notif['user']['role']) ? $notif['user']['role'] : 'N/A';
                        echo "<tr>";
                        echo "<td>{$notif['id_notif']}</td>";
                        echo "<td>{$notif['tipe']}</td>";
                        echo "<td>{$notif['judul']}</td>";
                        echo "<td>$userName</td>";
                        echo "<td>$userRole</td>";
                        echo "<td>{$notif['status']}</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    
                    echo "<h3>Raw Data:</h3>";
                    echo "<pre>" . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
                } else {
                    echo "<p class='error'>❌ Tidak ada data atau error</p>";
                    echo "<pre>" . print_r($response, true) . "</pre>";
                }
            } catch (Exception $e) {
                echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
            }
            ?>
        </div>
        
        <div class="section">
            <h2>3. Count Total Notifikasi</h2>
            <?php
            try {
                $response = supabase_request('GET', '/rest/v1/notifikasi?select=count');
                echo "<pre>" . print_r($response, true) . "</pre>";
                
                // Alternative: get all and count
                $all = supabase_request('GET', '/rest/v1/notifikasi');
                if (is_array($all)) {
                    $total = count($all);
                    echo "<p class='success'>✅ Total notifikasi di database: <strong>$total</strong></p>";
                } else {
                    echo "<p class='error'>❌ Tidak bisa hitung total</p>";
                }
            } catch (Exception $e) {
                echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
            }
            ?>
        </div>
        
        <div class="section">
            <h2>4. Test Filter by User</h2>
            <?php
            try {
                // Get first penghuni
                $users = supabase_request('GET', '/rest/v1/user?role=eq.penghuni kos&limit=1');
                if (!empty($users)) {
                    $user = $users[0];
                    $userId = $user['id_user'];
                    $userName = $user['nama'];
                    
                    echo "<p>Testing untuk user: <strong>$userName</strong> (ID: $userId)</p>";
                    
                    $notifs = supabase_request('GET', "/rest/v1/notifikasi?id_user=eq.$userId&order=id_notif.desc&limit=5");
                    
                    if (is_array($notifs) && !empty($notifs)) {
                        $count = count($notifs);
                        echo "<p class='success'>✅ User ini punya $count notifikasi (showing top 5)</p>";
                        
                        echo "<table>";
                        echo "<tr><th>ID</th><th>Tipe</th><th>Judul</th><th>Status</th><th>Tanggal</th></tr>";
                        foreach ($notifs as $notif) {
                            echo "<tr>";
                            echo "<td>{$notif['id_notif']}</td>";
                            echo "<td>{$notif['tipe']}</td>";
                            echo "<td>{$notif['judul']}</td>";
                            echo "<td>{$notif['status']}</td>";
                            echo "<td>{$notif['tanggal_kirim']}</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p class='error'>❌ User ini belum punya notifikasi</p>";
                    }
                }
            } catch (Exception $e) {
                echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
            }
            ?>
        </div>
        
        <div class="section">
            <h2>5. Test API Endpoint</h2>
            <button onclick="testAPI()" style="padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
                🧪 Test api/notifikasi_data.php
            </button>
            <pre id="apiResult" style="margin-top: 15px; min-height: 100px;"></pre>
        </div>
        
        <div class="section">
            <h2>📊 Summary</h2>
            <p>Jika semua test di atas menunjukkan data, tetapi halaman notifikasi masih kosong, kemungkinan masalah ada di:</p>
            <ul>
                <li>JavaScript di pages/notifikasi.php tidak fetch data dengan benar</li>
                <li>Session id_user tidak tersimpan dengan benar</li>
                <li>Browser cache perlu di-clear</li>
            </ul>
            <p><strong>Action:</strong> Buka halaman notifikasi dan cek browser console (F12) untuk melihat error JavaScript</p>
        </div>
    </div>
    
    <script>
        function testAPI() {
            const resultEl = document.getElementById('apiResult');
            resultEl.textContent = '⏳ Fetching api/notifikasi_data.php...';
            
            fetch('api/notifikasi_data.php')
                .then(response => {
                    console.log('Status:', response.status);
                    if (!response.ok) {
                        throw new Error('HTTP ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data received:', data);
                    resultEl.textContent = '✅ Success!\n\nTotal: ' + data.length + ' notifikasi\n\n' + JSON.stringify(data.slice(0, 3), null, 2);
                    resultEl.style.color = '#28a745';
                })
                .catch(error => {
                    console.error('Error:', error);
                    resultEl.textContent = '❌ Error: ' + error.message;
                    resultEl.style.color = '#dc3545';
                });
        }
    </script>
</body>
</html>
