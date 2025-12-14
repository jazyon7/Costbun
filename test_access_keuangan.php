<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Access Control - Keuangan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #667eea;
            margin-bottom: 10px;
        }
        .test-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            margin: 20px 0;
            border-left: 5px solid #667eea;
        }
        .test-section h2 {
            margin-top: 0;
            color: #333;
        }
        .role-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-block;
            margin: 10px 5px;
        }
        .role-admin {
            background: #fff3e0;
            color: #f57c00;
            border: 2px solid #f57c00;
        }
        .role-penghuni {
            background: #e3f2fd;
            color: #1976d2;
            border: 2px solid #1976d2;
        }
        .checklist {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .checklist-item {
            padding: 12px;
            margin: 8px 0;
            border-left: 3px solid #dee2e6;
            padding-left: 15px;
            display: flex;
            align-items: center;
        }
        .checklist-item:hover {
            background: #f8f9fa;
            border-left-color: #667eea;
        }
        .icon {
            font-size: 20px;
            margin-right: 10px;
            width: 30px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 10px 5px;
            transition: all 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        }
        .info-box {
            background: #d1ecf1;
            border: 2px solid #bee5eb;
            color: #0c5460;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .success-box {
            background: #d4edda;
            border: 2px solid #c3e6cb;
            color: #155724;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .warning-box {
            background: #fff3cd;
            border: 2px solid #ffeaa7;
            color: #856404;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        th {
            background: #667eea;
            color: white;
            font-weight: 600;
        }
        tr:hover {
            background: #f8f9fa;
        }
        .status-allowed {
            color: #28a745;
            font-weight: bold;
        }
        .status-denied {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔒 Test Access Control - Halaman Keuangan</h1>
        <p style="color: #666; margin-bottom: 30px;">Testing role-based access untuk halaman keuangan (Admin Only)</p>
        
        <div class="info-box">
            <h3>📋 Policy</h3>
            <p><strong>Halaman Keuangan hanya dapat diakses oleh role ADMIN.</strong></p>
            <p>Penghuni kos tidak memiliki akses ke halaman ini untuk menjaga kerahasiaan data keuangan.</p>
        </div>
        
        <!-- Test Section 1: Sidebar Visibility -->
        <div class="test-section">
            <h2>👁️ Test 1: Visibilitas Menu di Sidebar</h2>
            
            <div class="success-box">
                <h3>✅ Expected Behavior:</h3>
                <ul>
                    <li><span class="role-badge role-admin">Admin</span> → Menu "Keuangan" <strong>MUNCUL</strong> di sidebar</li>
                    <li><span class="role-badge role-penghuni">Penghuni Kos</span> → Menu "Keuangan" <strong>TIDAK MUNCUL</strong> di sidebar</li>
                </ul>
            </div>
            
            <div class="checklist">
                <h3>✅ Testing Steps:</h3>
                <div class="checklist-item">
                    <span class="icon">1️⃣</span>
                    Login sebagai <strong>Admin</strong>
                </div>
                <div class="checklist-item">
                    <span class="icon">✓</span>
                    Check sidebar → Menu "Keuangan" harus muncul
                </div>
                <div class="checklist-item">
                    <span class="icon">2️⃣</span>
                    Logout dan login sebagai <strong>Penghuni Kos</strong>
                </div>
                <div class="checklist-item">
                    <span class="icon">✓</span>
                    Check sidebar → Menu "Keuangan" harus hilang
                </div>
            </div>
            
            <a href="index.php" class="btn">🧪 Test Sekarang</a>
        </div>
        
        <!-- Test Section 2: Direct URL Access -->
        <div class="test-section">
            <h2>🔗 Test 2: Direct URL Access</h2>
            
            <div class="warning-box">
                <h3>⚠️ Security Check:</h3>
                <p>Meskipun menu tidak muncul, user bisa saja akses langsung via URL.</p>
                <p>System harus tetap block akses dan tampilkan "Access Denied" message.</p>
            </div>
            
            <div class="checklist">
                <h3>✅ Testing Steps:</h3>
                <div class="checklist-item">
                    <span class="icon">1️⃣</span>
                    Login sebagai <strong>Penghuni Kos</strong>
                </div>
                <div class="checklist-item">
                    <span class="icon">🔗</span>
                    Akses langsung: <code>http://costbun.test/index.php?page=keuangan</code>
                </div>
                <div class="checklist-item">
                    <span class="icon">✓</span>
                    Harus tampil halaman "Akses Ditolak" dengan icon lock
                </div>
                <div class="checklist-item">
                    <span class="icon">✓</span>
                    Ada tombol "Kembali ke Dashboard"
                </div>
                <div class="checklist-item">
                    <span class="icon">2️⃣</span>
                    Login sebagai <strong>Admin</strong>
                </div>
                <div class="checklist-item">
                    <span class="icon">✓</span>
                    Akses URL yang sama → Halaman keuangan muncul normal
                </div>
            </div>
            
            <a href="index.php?page=keuangan" class="btn">🧪 Test Direct Access</a>
        </div>
        
        <!-- Test Section 3: API Endpoints -->
        <div class="test-section">
            <h2>🔌 Test 3: API Endpoint Security</h2>
            
            <div class="info-box">
                <h3>🛡️ API Protection:</h3>
                <p>Semua endpoint write operation (create, update, delete) harus protected.</p>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Endpoint</th>
                        <th>Method</th>
                        <th>Admin</th>
                        <th>Penghuni</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>?action=get</code></td>
                        <td>GET</td>
                        <td class="status-allowed">✅ Allowed</td>
                        <td class="status-denied">❌ Denied</td>
                    </tr>
                    <tr>
                        <td><code>?action=create</code></td>
                        <td>POST</td>
                        <td class="status-allowed">✅ Allowed</td>
                        <td class="status-denied">❌ Denied</td>
                    </tr>
                    <tr>
                        <td><code>?action=update</code></td>
                        <td>PATCH</td>
                        <td class="status-allowed">✅ Allowed</td>
                        <td class="status-denied">❌ Denied</td>
                    </tr>
                    <tr>
                        <td><code>?action=delete</code></td>
                        <td>DELETE</td>
                        <td class="status-allowed">✅ Allowed</td>
                        <td class="status-denied">❌ Denied</td>
                    </tr>
                </tbody>
            </table>
            
            <div class="checklist">
                <h3>✅ Testing Steps:</h3>
                <div class="checklist-item">
                    <span class="icon">1️⃣</span>
                    Login sebagai <strong>Penghuni Kos</strong>
                </div>
                <div class="checklist-item">
                    <span class="icon">🔧</span>
                    Coba POST ke <code>api/keuangan.php?action=create</code>
                </div>
                <div class="checklist-item">
                    <span class="icon">✓</span>
                    Response harus: <code>{"success": false, "message": "Akses ditolak"}</code>
                </div>
                <div class="checklist-item">
                    <span class="icon">2️⃣</span>
                    Login sebagai <strong>Admin</strong>
                </div>
                <div class="checklist-item">
                    <span class="icon">✓</span>
                    POST yang sama → Berhasil create data
                </div>
            </div>
        </div>
        
        <!-- Expected Results -->
        <div class="test-section">
            <h2>📊 Expected Results Summary</h2>
            
            <table>
                <thead>
                    <tr>
                        <th>Feature</th>
                        <th>Admin</th>
                        <th>Penghuni Kos</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Menu "Keuangan" di sidebar</td>
                        <td class="status-allowed">✅ Muncul</td>
                        <td class="status-denied">❌ Tidak muncul</td>
                    </tr>
                    <tr>
                        <td>Akses halaman keuangan</td>
                        <td class="status-allowed">✅ Bisa akses</td>
                        <td class="status-denied">❌ Access Denied</td>
                    </tr>
                    <tr>
                        <td>Lihat summary cards</td>
                        <td class="status-allowed">✅ Bisa lihat</td>
                        <td class="status-denied">❌ Tidak bisa</td>
                    </tr>
                    <tr>
                        <td>Lihat table transaksi</td>
                        <td class="status-allowed">✅ Bisa lihat</td>
                        <td class="status-denied">❌ Tidak bisa</td>
                    </tr>
                    <tr>
                        <td>Tambah transaksi</td>
                        <td class="status-allowed">✅ Bisa</td>
                        <td class="status-denied">❌ Tidak bisa</td>
                    </tr>
                    <tr>
                        <td>Edit transaksi</td>
                        <td class="status-allowed">✅ Bisa</td>
                        <td class="status-denied">❌ Tidak bisa</td>
                    </tr>
                    <tr>
                        <td>Delete transaksi</td>
                        <td class="status-allowed">✅ Bisa</td>
                        <td class="status-denied">❌ Tidak bisa</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Quick Links -->
        <div style="text-align: center; margin-top: 40px;">
            <a href="debug_session.php" class="btn">🔍 Check Current Session</a>
            <a href="index.php?page=keuangan" class="btn">💰 Halaman Keuangan</a>
            <a href="login.php" class="btn btn-secondary">🔑 Login</a>
        </div>
    </div>
</body>
</html>
