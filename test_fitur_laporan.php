<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Fitur Laporan</title>
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
            margin-bottom: 10px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 40px;
        }
        .test-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            border-left: 5px solid #667eea;
        }
        .test-section h2 {
            margin-top: 0;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .role-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .role-penghuni {
            background: #e3f2fd;
            color: #1976d2;
        }
        .role-admin {
            background: #fff3e0;
            color: #f57c00;
        }
        .btn-group {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        }
        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
        }
        .checklist {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .checklist-item {
            padding: 10px;
            margin: 8px 0;
            border-left: 3px solid #dee2e6;
            padding-left: 15px;
        }
        .checklist-item:hover {
            background: #f8f9fa;
            border-left-color: #667eea;
        }
        .info-box {
            background: #d1ecf1;
            border: 2px solid #bee5eb;
            color: #0c5460;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .info-box h3 {
            margin-top: 0;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            color: #e83e8c;
            font-family: 'Courier New', monospace;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Test Fitur Laporan</h1>
        <p class="subtitle">Comprehensive testing guide untuk fitur laporan role-based</p>
        
        <!-- Test Section 1: Penghuni -->
        <div class="test-section">
            <h2>
                <span>👥 Test sebagai Penghuni</span>
                <span class="role-badge role-penghuni">Penghuni</span>
            </h2>
            
            <div class="info-box">
                <h3>🎯 Objective</h3>
                <p>Penghuni harus bisa membuat laporan baru dan hanya melihat laporan miliknya sendiri.</p>
            </div>
            
            <div class="checklist">
                <h3>✅ Checklist Testing</h3>
                <div class="checklist-item">
                    ☐ Login sebagai penghuni (email: <code>rizki.ramadhan@email.com</code>)
                </div>
                <div class="checklist-item">
                    ☐ Tombol "Tambah Laporan" muncul di kanan atas
                </div>
                <div class="checklist-item">
                    ☐ Klik tombol → Modal form terbuka dengan smooth animation
                </div>
                <div class="checklist-item">
                    ☐ Isi form: Judul = "AC Kamar Mati", Deskripsi = "AC tidak menyala..."
                </div>
                <div class="checklist-item">
                    ☐ Submit → Alert sukses muncul
                </div>
                <div class="checklist-item">
                    ☐ Page reload → Laporan baru muncul di tabel
                </div>
                <div class="checklist-item">
                    ☐ Status laporan = "diproses" (badge kuning)
                </div>
                <div class="checklist-item">
                    ☐ Nama pelapor = nama penghuni yang login
                </div>
                <div class="checklist-item">
                    ☐ Hanya melihat laporan sendiri (tidak ada laporan penghuni lain)
                </div>
                <div class="checklist-item">
                    ☐ Tombol "Ubah Status" TIDAK muncul
                </div>
                <div class="checklist-item">
                    ☐ Klik "Detail" → Popup alert detail laporan
                </div>
                <div class="checklist-item">
                    ☐ Filter by status: Diproses/Selesai berfungsi
                </div>
            </div>
            
            <div class="btn-group">
                <a href="index.php?page=laporan" class="btn" target="_blank">
                    🚀 Test sebagai Penghuni
                </a>
                <a href="logout.php" class="btn btn-secondary">
                    🔄 Logout (ganti user)
                </a>
            </div>
        </div>
        
        <!-- Test Section 2: Admin -->
        <div class="test-section">
            <h2>
                <span>👨‍💼 Test sebagai Admin</span>
                <span class="role-badge role-admin">Admin</span>
            </h2>
            
            <div class="info-box">
                <h3>🎯 Objective</h3>
                <p>Admin harus bisa melihat semua laporan dari seluruh penghuni dan mengubah status laporan.</p>
            </div>
            
            <div class="checklist">
                <h3>✅ Checklist Testing</h3>
                <div class="checklist-item">
                    ☐ Login sebagai admin (email: <code>admin@kos.com</code>)
                </div>
                <div class="checklist-item">
                    ☐ Tombol "Tambah Laporan" TIDAK muncul
                </div>
                <div class="checklist-item">
                    ☐ Melihat SEMUA laporan dari seluruh penghuni
                </div>
                <div class="checklist-item">
                    ☐ Nama pelapor berbeda-beda (dari berbagai penghuni)
                </div>
                <div class="checklist-item">
                    ☐ Tombol "Ubah Status" muncul di setiap row
                </div>
                <div class="checklist-item">
                    ☐ Klik "Ubah Status" pada laporan "diproses"
                </div>
                <div class="checklist-item">
                    ☐ Konfirmasi: "Ubah status menjadi selesai?" → OK
                </div>
                <div class="checklist-item">
                    ☐ Page reload → Status berubah jadi "selesai" (badge hijau)
                </div>
                <div class="checklist-item">
                    ☐ Klik "Ubah Status" lagi → Toggle kembali ke "diproses"
                </div>
                <div class="checklist-item">
                    ☐ Klik "Detail" → Popup detail laporan
                </div>
                <div class="checklist-item">
                    ☐ Filter by status: Semua/Diproses/Selesai berfungsi
                </div>
            </div>
            
            <div class="btn-group">
                <a href="index.php?page=laporan" class="btn" target="_blank">
                    🚀 Test sebagai Admin
                </a>
            </div>
        </div>
        
        <!-- Test Section 3: Database Check -->
        <div class="test-section">
            <h2>💾 Database Verification</h2>
            
            <div class="info-box">
                <h3>🎯 Objective</h3>
                <p>Verifikasi bahwa data tersimpan dengan benar di database Supabase.</p>
            </div>
            
            <div class="checklist">
                <h3>✅ Checklist Verification</h3>
                <div class="checklist-item">
                    ☐ Buka Supabase Dashboard → Table Editor → laporan
                </div>
                <div class="checklist-item">
                    ☐ Check kolom <code>id_user</code> terisi dengan ID pengguna
                </div>
                <div class="checklist-item">
                    ☐ Check <code>judul_laporan</code> sesuai input
                </div>
                <div class="checklist-item">
                    ☐ Check <code>deskripsi</code> sesuai input
                </div>
                <div class="checklist-item">
                    ☐ Check <code>status_laporan</code> awalnya "diproses"
                </div>
                <div class="checklist-item">
                    ☐ Check <code>created_at</code> timestamp otomatis terisi
                </div>
                <div class="checklist-item">
                    ☐ Foreign key icon (🔗) muncul di kolom id_user
                </div>
            </div>
            
            <div class="btn-group">
                <a href="https://supabase.com/dashboard" class="btn btn-success" target="_blank">
                    🔗 Buka Supabase Dashboard
                </a>
            </div>
        </div>
        
        <!-- Test Section 4: API Testing -->
        <div class="test-section">
            <h2>🔌 API Endpoint Testing</h2>
            
            <table>
                <thead>
                    <tr>
                        <th>Endpoint</th>
                        <th>Method</th>
                        <th>Role</th>
                        <th>Description</th>
                        <th>Test</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>?action=create</code></td>
                        <td>POST</td>
                        <td>Penghuni</td>
                        <td>Create new laporan</td>
                        <td>Via modal form</td>
                    </tr>
                    <tr>
                        <td><code>?action=update_status</code></td>
                        <td>GET</td>
                        <td>Admin</td>
                        <td>Update status laporan</td>
                        <td>Via button "Ubah Status"</td>
                    </tr>
                    <tr>
                        <td><code>?action=get&id=X</code></td>
                        <td>GET</td>
                        <td>All</td>
                        <td>Get single laporan</td>
                        <td>Via button "Detail"</td>
                    </tr>
                    <tr>
                        <td><code>?action=get</code></td>
                        <td>GET</td>
                        <td>All</td>
                        <td>Get all laporan</td>
                        <td>Page load</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Test Section 5: Security Check -->
        <div class="test-section">
            <h2>🔐 Security & Authorization Check</h2>
            
            <div class="info-box">
                <h3>🎯 Objective</h3>
                <p>Pastikan role-based access control bekerja dengan benar.</p>
            </div>
            
            <div class="checklist">
                <h3>✅ Security Checklist</h3>
                <div class="checklist-item">
                    ☐ <strong>Penghuni tidak bisa akses update_status:</strong><br>
                    Test: Coba akses manual <code>api/laporan.php?action=update_status&id=1&status=selesai</code> sebagai penghuni → Harus ditolak
                </div>
                <div class="checklist-item">
                    ☐ <strong>Admin tidak bisa create laporan:</strong><br>
                    Test: Coba POST ke <code>api/laporan.php?action=create</code> sebagai admin → Harus ditolak
                </div>
                <div class="checklist-item">
                    ☐ <strong>Penghuni hanya lihat laporan sendiri:</strong><br>
                    Login sebagai penghuni A, tidak boleh melihat laporan penghuni B
                </div>
                <div class="checklist-item">
                    ☐ <strong>XSS Prevention:</strong><br>
                    Input: <code>&lt;script&gt;alert('xss')&lt;/script&gt;</code> → Harus di-escape di output
                </div>
                <div class="checklist-item">
                    ☐ <strong>Required field validation:</strong><br>
                    Submit form kosong → Harus error "Field wajib diisi"
                </div>
            </div>
        </div>
        
        <!-- Test Section 6: UI/UX Check -->
        <div class="test-section">
            <h2>🎨 UI/UX Quality Check</h2>
            
            <div class="checklist">
                <h3>✅ UI/UX Checklist</h3>
                <div class="checklist-item">
                    ☐ Modal animation smooth (fade-in + slide-down)
                </div>
                <div class="checklist-item">
                    ☐ Modal close dengan klik X atau klik outside
                </div>
                <div class="checklist-item">
                    ☐ Button hover effect: transform + shadow
                </div>
                <div class="checklist-item">
                    ☐ Status badge warna berbeda: Diproses (kuning), Selesai (hijau)
                </div>
                <div class="checklist-item">
                    ☐ Form validation: required fields tidak bisa kosong
                </div>
                <div class="checklist-item">
                    ☐ Alert message informatif dan jelas
                </div>
                <div class="checklist-item">
                    ☐ Responsive: test di mobile view (F12 → toggle device)
                </div>
                <div class="checklist-item">
                    ☐ Loading state saat submit (bisa ditambahkan nanti)
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
                        <th>Penghuni</th>
                        <th>Admin</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tombol "Tambah Laporan"</td>
                        <td>✅ Muncul</td>
                        <td>❌ Tidak muncul</td>
                    </tr>
                    <tr>
                        <td>Lihat laporan</td>
                        <td>✅ Hanya miliknya</td>
                        <td>✅ Semua laporan</td>
                    </tr>
                    <tr>
                        <td>Buat laporan baru</td>
                        <td>✅ Bisa</td>
                        <td>❌ Tidak bisa</td>
                    </tr>
                    <tr>
                        <td>Ubah status laporan</td>
                        <td>❌ Tidak bisa</td>
                        <td>✅ Bisa</td>
                    </tr>
                    <tr>
                        <td>Lihat detail laporan</td>
                        <td>✅ Bisa</td>
                        <td>✅ Bisa</td>
                    </tr>
                    <tr>
                        <td>Filter by status</td>
                        <td>✅ Bisa</td>
                        <td>✅ Bisa</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Quick Links -->
        <div class="btn-group">
            <a href="index.php?page=laporan" class="btn btn-success" target="_blank">
                ✅ Buka Halaman Laporan
            </a>
            <a href="FITUR_LAPORAN.md" class="btn" target="_blank">
                📖 Dokumentasi Lengkap
            </a>
            <a href="setup_laporan_table.php" class="btn btn-secondary">
                🔧 Setup Database
            </a>
        </div>
    </div>
</body>
</html>
