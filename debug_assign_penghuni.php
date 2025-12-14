<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Assign Penghuni</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        button {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin: 5px;
        }
        button:hover {
            background: #5568d3;
        }
        pre {
            background: #2d2d30;
            color: #d4d4d4;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        select, input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h1>🔍 Debug Assign Penghuni</h1>
    
    <div class="box">
        <h2>1. Test API Endpoint Directly</h2>
        
        <div class="form-group">
            <label>ID Kamar:</label>
            <input type="number" id="test_id_kamar" value="1">
        </div>
        
        <div class="form-group">
            <label>ID User:</label>
            <input type="number" id="test_id_user" value="1">
        </div>
        
        <button onclick="testAssignAPI()">Test Assign API</button>
        
        <h3>Response:</h3>
        <pre id="apiResponse">Click button to test...</pre>
    </div>
    
    <div class="box">
        <h2>2. Test dengan Data Real</h2>
        <?php
        require_once __DIR__ . '/config/supabase_helper.php';
        
        $kamarList = getKamar();
        $userList = getUser();
        ?>
        
        <form id="realForm">
            <div class="form-group">
                <label>Pilih Kamar:</label>
                <select name="id_kamar" id="real_id_kamar" required>
                    <option value="">-- Pilih Kamar --</option>
                    <?php
                    if (is_array($kamarList)) {
                        foreach ($kamarList as $kamar) {
                            $status = $kamar['id_user'] ? ' (Terisi)' : ' (Kosong)';
                            echo '<option value="' . $kamar['id_kamar'] . '">' . htmlspecialchars($kamar['nama']) . $status . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Pilih Penghuni:</label>
                <select name="id_user" id="real_id_user" required>
                    <option value="">-- Pilih Penghuni --</option>
                    <?php
                    if (is_array($userList)) {
                        foreach ($userList as $user) {
                            if (strtolower($user['role']) !== 'admin') {
                                echo '<option value="' . $user['id_user'] . '">' . htmlspecialchars($user['nama']) . ' - ' . htmlspecialchars($user['email']) . '</option>';
                            }
                        }
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Tanggal Mulai:</label>
                <input type="date" name="tanggal_mulai" value="<?= date('Y-m-d') ?>">
            </div>
            
            <button type="button" onclick="testRealAssign()">Test Assign (Real Data)</button>
        </form>
        
        <h3>Response:</h3>
        <pre id="realResponse">Fill form and click button...</pre>
    </div>
    
    <div class="box">
        <h2>3. Lihat Data Kamar</h2>
        <button onclick="loadKamar()">Load Kamar Data</button>
        <pre id="kamarData">Click button to load...</pre>
    </div>
    
    <div class="box">
        <h2>4. Quick Links</h2>
        <button onclick="window.open('index.php?page=data_kamar', '_blank')">Open Data Kamar Page</button>
        <button onclick="window.location.reload()">Refresh Page</button>
    </div>
    
    <script>
        function testAssignAPI() {
            const resultEl = document.getElementById('apiResponse');
            const id_kamar = document.getElementById('test_id_kamar').value;
            const id_user = document.getElementById('test_id_user').value;
            
            resultEl.textContent = '⏳ Testing...';
            
            const params = new URLSearchParams();
            params.append('id_kamar', id_kamar);
            params.append('id_user', id_user);
            params.append('tanggal_mulai', '<?= date('Y-m-d') ?>');
            
            console.log('Testing with params:', params.toString());
            
            fetch('api/kamar.php?action=assign_penghuni', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: params.toString()
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.text();
            })
            .then(text => {
                console.log('Response text:', text);
                try {
                    const data = JSON.parse(text);
                    resultEl.textContent = 'SUCCESS!\n\n' + JSON.stringify(data, null, 2);
                    resultEl.style.color = data.success ? '#4ec9b0' : '#f48771';
                } catch (e) {
                    resultEl.textContent = 'Parse Error!\n\n' + text;
                    resultEl.style.color = '#f48771';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                resultEl.textContent = 'ERROR: ' + error.message;
                resultEl.style.color = '#f48771';
            });
        }
        
        function testRealAssign() {
            const resultEl = document.getElementById('realResponse');
            const form = document.getElementById('realForm');
            const formData = new FormData(form);
            
            // Validate
            if (!formData.get('id_kamar') || !formData.get('id_user')) {
                alert('Pilih kamar dan penghuni!');
                return;
            }
            
            resultEl.textContent = '⏳ Testing...';
            
            const params = new URLSearchParams();
            for (let [key, value] of formData.entries()) {
                params.append(key, value);
                console.log(key + ':', value);
            }
            
            fetch('api/kamar.php?action=assign_penghuni', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: params.toString()
            })
            .then(response => response.text())
            .then(text => {
                console.log('Response:', text);
                try {
                    const data = JSON.parse(text);
                    resultEl.textContent = JSON.stringify(data, null, 2);
                    resultEl.style.color = data.success ? '#4ec9b0' : '#f48771';
                    
                    if (data.success) {
                        alert('✅ Success! Reload page untuk lihat perubahan.');
                    }
                } catch (e) {
                    resultEl.textContent = 'Parse Error!\n\n' + text;
                    resultEl.style.color = '#f48771';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                resultEl.textContent = 'ERROR: ' + error.message;
                resultEl.style.color = '#f48771';
            });
        }
        
        function loadKamar() {
            const resultEl = document.getElementById('kamarData');
            resultEl.textContent = '⏳ Loading...';
            
            fetch('api/kamar.php?action=get')
                .then(response => response.json())
                .then(data => {
                    resultEl.textContent = JSON.stringify(data, null, 2);
                    resultEl.style.color = '#4ec9b0';
                })
                .catch(error => {
                    resultEl.textContent = 'ERROR: ' + error.message;
                    resultEl.style.color = '#f48771';
                });
        }
    </script>
</body>
</html>
