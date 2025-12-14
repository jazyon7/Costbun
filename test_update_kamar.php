<?php
require_once __DIR__ . '/config/supabase_helper.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Update Kamar</title>
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
        .success { color: #4ec9b0; }
        .error { color: #f48771; }
        .warning { color: #dcdcaa; }
        pre {
            background: #1e1e1e;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            border: 1px solid #3c3c3c;
        }
        button {
            background: #4ec9b0;
            color: #000;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin: 5px;
        }
        button:hover {
            background: #3da88a;
        }
    </style>
</head>
<body>
    <h1 style="color: #569cd6;">🔍 Test Update Kamar - Supabase</h1>
    
    <div class="section">
        <h2>1. Test Direct Update ke Kamar ID 1</h2>
        <?php
        // Get current data
        $kamar = getKamar(1);
        
        if ($kamar) {
            echo "<h3 class='warning'>Data Sebelum Update:</h3>";
            echo "<pre>";
            print_r($kamar);
            echo "</pre>";
            
            // Try update
            echo "<h3 class='warning'>Mencoba Update...</h3>";
            
            $updateData = [
                'id_user' => 2,
                'status' => 'terisi'
            ];
            
            echo "<p>Data yang akan di-update:</p>";
            echo "<pre>";
            print_r($updateData);
            echo "</pre>";
            
            $result = updateKamar(1, $updateData);
            
            echo "<h3 class='warning'>Result dari updateKamar():</h3>";
            echo "<pre>";
            print_r($result);
            echo "</pre>";
            
            if (isset($result['error'])) {
                echo "<p class='error'>❌ Update GAGAL: " . $result['error'] . "</p>";
            } else {
                echo "<p class='success'>✅ Update response received</p>";
                
                // Get updated data
                echo "<h3 class='warning'>Data Setelah Update:</h3>";
                $kamarUpdated = getKamar(1);
                echo "<pre>";
                print_r($kamarUpdated);
                echo "</pre>";
                
                // Compare
                if ($kamarUpdated['id_user'] == 2 && $kamarUpdated['status'] == 'terisi') {
                    echo "<p class='success'>✅ DATA BERHASIL TERSIMPAN DI DATABASE!</p>";
                } else {
                    echo "<p class='error'>❌ DATA TIDAK TERSIMPAN! Masih: id_user=" . ($kamarUpdated['id_user'] ?? 'NULL') . ", status=" . $kamarUpdated['status'] . "</p>";
                }
            }
        } else {
            echo "<p class='error'>❌ Kamar ID 1 tidak ditemukan</p>";
        }
        ?>
    </div>
    
    <div class="section">
        <h2>2. Test Supabase Request Function</h2>
        <?php
        require_once __DIR__ . '/config/supabase_request.php';
        
        echo "<h3 class='warning'>Test PATCH Request Langsung:</h3>";
        
        $testData = [
            'id_user' => 3,
            'status' => 'terisi'
        ];
        
        echo "<p>Data yang dikirim:</p>";
        echo "<pre>";
        print_r($testData);
        echo "</pre>";
        
        try {
            $directResult = supabase_request('PATCH', '/rest/v1/kamar?id_kamar=eq.1', $testData);
            
            echo "<h3 class='warning'>Response dari Supabase:</h3>";
            echo "<pre>";
            print_r($directResult);
            echo "</pre>";
            
            if (isset($directResult['error'])) {
                echo "<p class='error'>❌ Supabase Error: " . $directResult['error'] . "</p>";
            } else {
                echo "<p class='success'>✅ Supabase request berhasil</p>";
                
                // Verify
                $verify = supabase_request('GET', '/rest/v1/kamar?id_kamar=eq.1');
                echo "<h3 class='warning'>Verifikasi Data:</h3>";
                echo "<pre>";
                print_r($verify);
                echo "</pre>";
            }
        } catch (Exception $e) {
            echo "<p class='error'>❌ Exception: " . $e->getMessage() . "</p>";
        }
        ?>
    </div>
    
    <div class="section">
        <h2>3. Test API Endpoint</h2>
        <button onclick="testAPI()">Test api/kamar.php?action=assign_penghuni</button>
        <pre id="apiResult">Click button to test...</pre>
    </div>
    
    <div class="section">
        <h2>4. List Semua Kamar</h2>
        <?php
        $allKamar = getKamar();
        if ($allKamar) {
            echo "<table style='width:100%; color:#d4d4d4; border-collapse: collapse;'>";
            echo "<tr style='background:#3c3c3c;'>";
            echo "<th style='padding:10px; text-align:left;'>ID</th>";
            echo "<th style='padding:10px; text-align:left;'>Nama</th>";
            echo "<th style='padding:10px; text-align:left;'>ID User</th>";
            echo "<th style='padding:10px; text-align:left;'>Status</th>";
            echo "</tr>";
            
            foreach ($allKamar as $k) {
                echo "<tr style='border-bottom:1px solid #3c3c3c;'>";
                echo "<td style='padding:10px;'>{$k['id_kamar']}</td>";
                echo "<td style='padding:10px;'>{$k['nama']}</td>";
                echo "<td style='padding:10px;'>" . ($k['id_user'] ?? 'NULL') . "</td>";
                echo "<td style='padding:10px;'>{$k['status']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        ?>
    </div>
    
    <div class="section">
        <h2>5. Analisis Masalah</h2>
        <div style="padding: 15px; background: #3c3c3c; border-radius: 5px;">
            <h3 class="warning">Kemungkinan Penyebab:</h3>
            <ul>
                <li>✓ PATCH request tidak mengirim data dengan benar</li>
                <li>✓ Supabase API key tidak punya permission untuk UPDATE</li>
                <li>✓ Table kamar tidak punya column id_user (constraint issue)</li>
                <li>✓ Foreign key constraint error</li>
                <li>✓ Response sukses tapi data tidak ter-commit</li>
            </ul>
            
            <h3 class="warning" style="margin-top: 20px;">Action Items:</h3>
            <ol>
                <li>Cek hasil test di atas</li>
                <li>Jika test 1 gagal: Issue di updateKamar() function</li>
                <li>Jika test 2 gagal: Issue di Supabase request/permission</li>
                <li>Jika test 1&2 sukses tapi UI tidak update: Issue di frontend/cache</li>
            </ol>
        </div>
    </div>
    
    <script>
        function testAPI() {
            const resultEl = document.getElementById('apiResult');
            resultEl.textContent = '⏳ Testing API...';
            
            const params = new URLSearchParams();
            params.append('id_kamar', '1');
            params.append('id_user', '4');
            params.append('tanggal_mulai', '<?= date('Y-m-d') ?>');
            
            fetch('api/kamar.php?action=assign_penghuni', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: params.toString()
            })
            .then(response => response.text())
            .then(text => {
                resultEl.textContent = 'Response:\n\n' + text;
                
                // Try to parse as JSON
                try {
                    const data = JSON.parse(text);
                    resultEl.textContent += '\n\nParsed:\n' + JSON.stringify(data, null, 2);
                } catch (e) {
                    resultEl.textContent += '\n\n(Not valid JSON)';
                }
            })
            .catch(error => {
                resultEl.textContent = 'ERROR: ' + error.message;
            });
        }
    </script>
</body>
</html>
