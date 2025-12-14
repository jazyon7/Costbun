<?php
require_once __DIR__ . '/config/supabase_helper.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Notifikasi - Costbun</title>
    <style>
        body {
            font-family: monospace;
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 20px;
            line-height: 1.6;
        }
        .section {
            background: #2d2d2d;
            border: 2px solid #3c3c3c;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        h2 {
            color: #4ec9b0;
            border-bottom: 2px solid #4ec9b0;
            padding-bottom: 10px;
        }
        .success {
            color: #4ec9b0;
        }
        .error {
            color: #f48771;
        }
        .warning {
            color: #dcdcaa;
        }
        pre {
            background: #1e1e1e;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            border: 1px solid #3c3c3c;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-success { background: #4ec9b0; color: #000; }
        .badge-error { background: #f48771; color: #000; }
        .badge-info { background: #569cd6; color: #000; }
    </style>
</head>
<body>
    <h1 style="color: #569cd6;">🔍 Debug Notifikasi System</h1>
    
    <div class="section">
        <h2>1. Test getNotifikasi() Function</h2>
        <?php
        try {
            $notifikasi = getNotifikasi();
            $count = is_array($notifikasi) ? count($notifikasi) : 0;
            
            if ($count > 0) {
                echo "<p class='success'>✅ Fungsi getNotifikasi() bekerja!</p>";
                echo "<p>Total notifikasi: <strong class='success'>$count</strong></p>";
                
                // Show first 3 records
                echo "<h3 class='warning'>Sample Data (3 pertama):</h3>";
                echo "<pre>";
                print_r(array_slice($notifikasi, 0, 3));
                echo "</pre>";
            } else {
                echo "<p class='error'>❌ Tidak ada data notifikasi atau fungsi tidak return array</p>";
                echo "<pre>";
                var_dump($notifikasi);
                echo "</pre>";
            }
        } catch (Exception $e) {
            echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
        }
        ?>
    </div>
    
    <div class="section">
        <h2>2. Test API Endpoint (api/notifikasi_data.php)</h2>
        <?php
        $apiUrl = 'http://costbun.test/api/notifikasi_data.php';
        
        try {
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            echo "<p>HTTP Status: <span class='badge badge-info'>$httpCode</span></p>";
            
            if ($httpCode === 200) {
                echo "<p class='success'>✅ API endpoint accessible</p>";
                
                $data = json_decode($response, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $apiCount = count($data);
                    echo "<p>Data dari API: <strong class='success'>$apiCount notifikasi</strong></p>";
                    
                    if ($apiCount > 0) {
                        echo "<h3 class='warning'>Sample Response (3 pertama):</h3>";
                        echo "<pre>";
                        print_r(array_slice($data, 0, 3));
                        echo "</pre>";
                    } else {
                        echo "<p class='warning'>⚠️ API return empty array</p>";
                    }
                } else {
                    echo "<p class='error'>❌ JSON Parse Error: " . json_last_error_msg() . "</p>";
                    echo "<pre>$response</pre>";
                }
            } else {
                echo "<p class='error'>❌ API tidak accessible (HTTP $httpCode)</p>";
                echo "<pre>$response</pre>";
            }
        } catch (Exception $e) {
            echo "<p class='error'>❌ Error testing API: " . $e->getMessage() . "</p>";
        }
        ?>
    </div>
    
    <div class="section">
        <h2>3. Test Struktur Data</h2>
        <?php
        if (isset($notifikasi) && !empty($notifikasi)) {
            $firstNotif = $notifikasi[0];
            echo "<h3 class='warning'>Struktur Field Notifikasi Pertama:</h3>";
            echo "<ul>";
            foreach ($firstNotif as $key => $value) {
                $type = gettype($value);
                $valuePreview = is_array($value) ? '[Array]' : (is_string($value) ? substr($value, 0, 50) : $value);
                echo "<li><strong>$key</strong> ($type): $valuePreview</li>";
            }
            echo "</ul>";
            
            // Check required fields
            $requiredFields = ['id_notif', 'tipe', 'judul', 'pesan', 'tanggal_kirim', 'status', 'id_user'];
            echo "<h3 class='warning'>Check Required Fields:</h3>";
            echo "<ul>";
            foreach ($requiredFields as $field) {
                if (isset($firstNotif[$field])) {
                    echo "<li class='success'>✅ $field: EXISTS</li>";
                } else {
                    echo "<li class='error'>❌ $field: MISSING</li>";
                }
            }
            echo "</ul>";
        }
        ?>
    </div>
    
    <div class="section">
        <h2>4. Test Filter by User</h2>
        <?php
        // Get a sample user
        $users = getUser();
        $penghuni = null;
        foreach ($users as $user) {
            if ($user['role'] === 'penghuni kos') {
                $penghuni = $user;
                break;
            }
        }
        
        if ($penghuni) {
            $userId = $penghuni['id_user'];
            echo "<p>Testing dengan user: <strong>{$penghuni['nama']}</strong> (ID: $userId)</p>";
            
            $filteredUrl = "http://costbun.test/api/notifikasi_data.php?user_id=$userId";
            
            try {
                $ch = curl_init($filteredUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                $response = curl_exec($ch);
                curl_close($ch);
                
                $data = json_decode($response, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $count = count($data);
                    echo "<p>Notifikasi untuk user ini: <strong class='success'>$count</strong></p>";
                    
                    if ($count > 0) {
                        echo "<pre>";
                        print_r(array_slice($data, 0, 2));
                        echo "</pre>";
                    } else {
                        echo "<p class='warning'>⚠️ User ini belum ada notifikasi</p>";
                    }
                } else {
                    echo "<p class='error'>❌ JSON Error: " . json_last_error_msg() . "</p>";
                }
            } catch (Exception $e) {
                echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p class='error'>❌ Tidak ada penghuni kos</p>";
        }
        ?>
    </div>
    
    <div class="section">
        <h2>5. JavaScript Console Test</h2>
        <p>Buka browser console (F12) dan jalankan:</p>
        <pre>
fetch('api/notifikasi_data.php')
  .then(r => r.json())
  .then(d => console.log('Data:', d))
  .catch(e => console.error('Error:', e));
        </pre>
        <button onclick="testFetch()" style="padding: 10px 20px; background: #4ec9b0; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
            🧪 Test Fetch API
        </button>
        <pre id="fetchResult" style="margin-top: 10px;"></pre>
    </div>
    
    <div class="section">
        <h2>6. Recommendations</h2>
        <div id="recommendations">
            <?php
            $issues = [];
            
            if (!isset($notifikasi) || empty($notifikasi)) {
                $issues[] = "❌ getNotifikasi() tidak return data - cek Supabase connection";
            }
            
            if (isset($apiCount) && $apiCount === 0) {
                $issues[] = "❌ API endpoint return empty - cek api/notifikasi_data.php";
            }
            
            if (empty($issues)) {
                echo "<p class='success'>✅ Semua test passed! Cek JavaScript di halaman notifikasi.</p>";
            } else {
                echo "<h3 class='error'>Issues Found:</h3>";
                echo "<ul>";
                foreach ($issues as $issue) {
                    echo "<li>$issue</li>";
                }
                echo "</ul>";
            }
            ?>
        </div>
    </div>
    
    <script>
        function testFetch() {
            const resultEl = document.getElementById('fetchResult');
            resultEl.textContent = '⏳ Fetching...';
            
            fetch('api/notifikasi_data.php')
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Data received:', data);
                    resultEl.textContent = 'Success! Data count: ' + data.length + '\n\n' + JSON.stringify(data.slice(0, 2), null, 2);
                    resultEl.style.color = '#4ec9b0';
                })
                .catch(error => {
                    console.error('Error:', error);
                    resultEl.textContent = 'Error: ' + error.message;
                    resultEl.style.color = '#f48771';
                });
        }
    </script>
</body>
</html>
