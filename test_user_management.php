<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Manajemen User</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .test-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        .test-section h3 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .test-item {
            background: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .test-label {
            font-weight: 600;
            color: #333;
        }
        .status {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-success { background: #4CAF50; color: white; }
        .status-warning { background: #ff9800; color: white; }
        .status-error { background: #f44336; color: white; }
        .status-info { background: #2196F3; color: white; }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .code-block {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            overflow-x: auto;
            margin: 10px 0;
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-info {
            background: #e3f2fd;
            color: #1976d2;
            border-left: 4px solid #2196F3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>
            🧪 Test Manajemen User
        </h1>
        <p class="subtitle">Verifikasi fitur manajemen user di halaman Data Kos</p>

        <?php
        session_start();
        require_once 'config/supabase_helper.php';

        // Cek login
        if (!isset($_SESSION['id_user'])) {
            echo '<div class="alert alert-info">
                ℹ️ Anda belum login. <a href="login.php">Klik disini untuk login</a>
            </div>';
        } else {
            echo '<div class="alert alert-info">
                ✅ Login sebagai: <strong>' . htmlspecialchars($_SESSION['nama']) . '</strong> 
                (Role: <strong>' . htmlspecialchars($_SESSION['role']) . '</strong>)
            </div>';
        }
        ?>

        <!-- Test 1: Session Check -->
        <div class="test-section">
            <h3>📋 Test 1: Session & Role</h3>
            <?php if (isset($_SESSION['id_user'])): ?>
            <div class="test-item">
                <span class="test-label">Session ID User</span>
                <span class="status status-success">✓ <?= $_SESSION['id_user'] ?></span>
            </div>
            <div class="test-item">
                <span class="test-label">Session Nama</span>
                <span class="status status-success">✓ <?= $_SESSION['nama'] ?></span>
            </div>
            <div class="test-item">
                <span class="test-label">Session Role</span>
                <span class="status <?= $_SESSION['role'] == 'admin' ? 'status-info' : 'status-warning' ?>">
                    <?= strtoupper($_SESSION['role']) ?>
                </span>
            </div>
            <div class="test-item">
                <span class="test-label">Akses Admin</span>
                <span class="status <?= $_SESSION['role'] == 'admin' ? 'status-success' : 'status-error' ?>">
                    <?= $_SESSION['role'] == 'admin' ? '✓ Granted' : '✗ Denied' ?>
                </span>
            </div>
            <?php else: ?>
            <div class="test-item">
                <span class="test-label">Status Login</span>
                <span class="status status-error">✗ Belum Login</span>
            </div>
            <?php endif; ?>
        </div>

        <!-- Test 2: Database Connection -->
        <div class="test-section">
            <h3>🔌 Test 2: Koneksi Database</h3>
            <?php
            $users = getUser();
            $isArray = is_array($users);
            $userCount = $isArray ? count($users) : 0;
            ?>
            <div class="test-item">
                <span class="test-label">Koneksi Supabase</span>
                <span class="status <?= $isArray ? 'status-success' : 'status-error' ?>">
                    <?= $isArray ? '✓ Connected' : '✗ Failed' ?>
                </span>
            </div>
            <div class="test-item">
                <span class="test-label">Total User di Database</span>
                <span class="status status-info"><?= $userCount ?> user</span>
            </div>
        </div>

        <!-- Test 3: User Data -->
        <div class="test-section">
            <h3>👥 Test 3: Data User</h3>
            <?php if ($isArray && $userCount > 0): ?>
            <?php
            $adminCount = 0;
            $penghuniCount = 0;
            foreach ($users as $u) {
                if (strtolower($u['role']) == 'admin') $adminCount++;
                else if (strtolower($u['role']) == 'penghuni kos') $penghuniCount++;
            }
            ?>
            <div class="test-item">
                <span class="test-label">Jumlah Admin</span>
                <span class="status status-info"><?= $adminCount ?> admin</span>
            </div>
            <div class="test-item">
                <span class="test-label">Jumlah Penghuni Kos</span>
                <span class="status status-info"><?= $penghuniCount ?> penghuni</span>
            </div>
            <div class="test-item">
                <span class="test-label">Role Unique</span>
                <span class="status status-success">
                    <?php
                    $roles = array_unique(array_column($users, 'role'));
                    echo implode(', ', $roles);
                    ?>
                </span>
            </div>
            <?php else: ?>
            <div class="test-item">
                <span class="test-label">Data User</span>
                <span class="status status-error">✗ Tidak ada data</span>
            </div>
            <?php endif; ?>
        </div>

        <!-- Test 4: Password Hash -->
        <div class="test-section">
            <h3>🔐 Test 4: Password Security</h3>
            <?php if ($isArray && $userCount > 0): ?>
            <?php
            $hashedCount = 0;
            $plainCount = 0;
            foreach ($users as $u) {
                if (strlen($u['password']) == 60 && substr($u['password'], 0, 4) == '$2y$') {
                    $hashedCount++;
                } else {
                    $plainCount++;
                }
            }
            ?>
            <div class="test-item">
                <span class="test-label">Password Bcrypt (Secure)</span>
                <span class="status <?= $hashedCount > 0 ? 'status-success' : 'status-warning' ?>">
                    <?= $hashedCount ?> user
                </span>
            </div>
            <div class="test-item">
                <span class="test-label">Password Plain Text (Not Secure)</span>
                <span class="status <?= $plainCount > 0 ? 'status-warning' : 'status-success' ?>">
                    <?= $plainCount ?> user
                </span>
            </div>
            <?php if ($plainCount > 0): ?>
            <div class="alert alert-info" style="margin-top: 10px;">
                ⚠️ Ada <?= $plainCount ?> user dengan password plain text. Sebaiknya update ke bcrypt.
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Test 5: API Endpoint -->
        <div class="test-section">
            <h3>🌐 Test 5: API Endpoint</h3>
            <div class="test-item">
                <span class="test-label">API User (GET)</span>
                <span class="status status-info">api/user.php?action=get</span>
            </div>
            <div class="test-item">
                <span class="test-label">API User (CREATE)</span>
                <span class="status status-info">api/user.php?action=create</span>
            </div>
            <div class="test-item">
                <span class="test-label">API User (DELETE)</span>
                <span class="status status-info">api/user.php?action=delete&id={id}</span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="btn-group">
            <a href="index.php?page=data_kos" class="btn">
                📊 Buka Data Kos
            </a>
            <?php if (!isset($_SESSION['id_user'])): ?>
            <a href="login.php" class="btn">
                🔐 Login
            </a>
            <?php else: ?>
            <a href="logout.php" class="btn">
                🚪 Logout
            </a>
            <?php endif; ?>
        </div>

        <!-- Test Credentials -->
        <?php if (!isset($_SESSION['id_user'])): ?>
        <div class="test-section" style="margin-top: 30px;">
            <h3>🔑 Test Credentials</h3>
            <div class="code-block">
Admin Test:<br>
Username: admin<br>
Password: admin123<br>
<br>
Penghuni Test:<br>
Username: budi<br>
Password: budi123
            </div>
            <small style="color: #666;">* Sesuaikan dengan data di database Anda</small>
        </div>
        <?php endif; ?>

    </div>
</body>
</html>
