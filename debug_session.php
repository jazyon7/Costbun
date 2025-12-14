<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Session</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 40px;
            line-height: 1.6;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #252526;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
        }
        h1 {
            color: #4ec9b0;
            margin-bottom: 30px;
        }
        .session-box {
            background: #1e1e1e;
            padding: 20px;
            border-radius: 6px;
            border: 2px solid #3c3c3c;
            margin: 20px 0;
        }
        .key {
            color: #9cdcfe;
            font-weight: bold;
        }
        .value {
            color: #ce9178;
        }
        .role {
            color: #4ec9b0;
            font-size: 18px;
            font-weight: bold;
        }
        .btn {
            display: inline-block;
            background: #0e639c;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 5px 0 0;
            font-family: 'Segoe UI', sans-serif;
        }
        .btn:hover {
            background: #1177bb;
        }
        pre {
            background: #1e1e1e;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            border-left: 3px solid #4ec9b0;
        }
        .warning {
            background: #5a3914;
            border: 2px solid #ff8c00;
            color: #ffcc99;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .success {
            background: #1a3a1a;
            border: 2px solid #4ec9b0;
            color: #b5f7cc;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Debug Session Information</h1>
        
        <?php if (isset($_SESSION['id_user'])): ?>
        <div class="success">
            ✅ Session aktif - User sudah login
        </div>
        
        <div class="session-box">
            <h2>📋 Session Data:</h2>
            <p><span class="key">id_user:</span> <span class="value"><?= $_SESSION['id_user'] ?? 'not set' ?></span></p>
            <p><span class="key">nama:</span> <span class="value"><?= $_SESSION['nama'] ?? 'not set' ?></span></p>
            <p><span class="key">email:</span> <span class="value"><?= $_SESSION['email'] ?? 'not set' ?></span></p>
            <p><span class="key">role:</span> <span class="role"><?= $_SESSION['role'] ?? 'not set' ?></span></p>
        </div>
        
        <div class="session-box">
            <h2>🎭 Role Check:</h2>
            <?php 
            $role = $_SESSION['role'] ?? '';
            echo "<p>Current role: <span class='role'>\"$role\"</span></p>";
            
            if ($role === 'penghuni kos') {
                echo "<div class='success'>✅ Role = 'penghuni kos' → Tombol TAMBAH LAPORAN akan muncul</div>";
            } elseif ($role === 'admin') {
                echo "<div class='warning'>⚠️ Role = 'admin' → Tombol TAMBAH LAPORAN tidak akan muncul</div>";
            } else {
                echo "<div class='warning'>⚠️ Role tidak valid: '$role'</div>";
            }
            ?>
        </div>
        
        <div class="session-box">
            <h2>📦 Full $_SESSION Array:</h2>
            <pre><?php print_r($_SESSION); ?></pre>
        </div>
        
        <?php else: ?>
        <div class="warning">
            ❌ Session tidak aktif - User belum login
        </div>
        <?php endif; ?>
        
        <div>
            <a href="index.php?page=laporan" class="btn">🚀 Buka Halaman Laporan</a>
            <a href="login.php" class="btn">🔑 Login</a>
            <a href="logout.php" class="btn">🚪 Logout</a>
        </div>
        
        <div class="session-box" style="margin-top: 30px;">
            <h2>📝 Expected Roles:</h2>
            <ul>
                <li><strong>"penghuni kos"</strong> → Can create laporan, see only own reports</li>
                <li><strong>"admin"</strong> → Can update status, see all reports</li>
            </ul>
        </div>
    </div>
</body>
</html>
