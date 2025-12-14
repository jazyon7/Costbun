<?php
require_once __DIR__ . '/config/supabase_helper.php';

// Set timezone
date_default_timezone_set('Asia/Jakarta');

echo "<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Insert Data Penghuni Kos</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        h1 {
            color: #667eea;
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
        .section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        .section h2 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .result {
            padding: 10px 15px;
            margin: 5px 0;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border-left: 3px solid #28a745;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border-left: 3px solid #dc3545;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            border-left: 3px solid #17a2b8;
        }
        .warning {
            background: #fff3cd;
            color: #856404;
            border-left: 3px solid #ffc107;
        }
        .summary {
            background: #667eea;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
        .summary h3 {
            margin-bottom: 10px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .stat-box {
            background: rgba(255,255,255,0.2);
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 14px;
            opacity: 0.9;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class='container'>";

echo "<h1>🏠 Insert Data Penghuni Kos</h1>";
echo "<p class='subtitle'>Menambahkan 20 data penghuni kos dummy ke database Supabase</p>";

// ============ DATA PENGHUNI KOS (20 data) ============
echo "<div class='section'>";
echo "<h2>📝 Proses Insert Data Penghuni Kos</h2>";

$penghuniData = [
    // Data Penghuni 1-5
    [
        'nama' => 'Rizki Ramadhan',
        'nomor' => '081234567001',
        'alamat' => 'Jl. Merdeka No. 12, Jakarta',
        'ktp_ktm' => '3174012801990001',
        'email' => 'rizki.ramadhan@email.com',
        'role' => 'penghuni kos',
        'username' => 'rizki_r',
        'password' => password_hash('rizki123', PASSWORD_BCRYPT),
        'telegram_id' => '123456001'
    ],
    [
        'nama' => 'Putri Amelia',
        'nomor' => '081234567002',
        'alamat' => 'Jl. Sudirman No. 45, Bandung',
        'ktp_ktm' => '3273056512000002',
        'email' => 'putri.amelia@email.com',
        'role' => 'penghuni kos',
        'username' => 'putri_a',
        'password' => password_hash('putri123', PASSWORD_BCRYPT),
        'telegram_id' => '123456002'
    ],
    [
        'nama' => 'Fajar Nugroho',
        'nomor' => '081234567003',
        'alamat' => 'Jl. Diponegoro No. 78, Semarang',
        'ktp_ktm' => '3374031209980003',
        'email' => 'fajar.nugroho@email.com',
        'role' => 'penghuni kos',
        'username' => 'fajar_n',
        'password' => password_hash('fajar123', PASSWORD_BCRYPT),
        'telegram_id' => '123456003'
    ],
    [
        'nama' => 'Sinta Dewi',
        'nomor' => '081234567004',
        'alamat' => 'Jl. Ahmad Yani No. 23, Surabaya',
        'ktp_ktm' => '3578047806010004',
        'email' => 'sinta.dewi@email.com',
        'role' => 'penghuni kos',
        'username' => 'sinta_d',
        'password' => password_hash('sinta123', PASSWORD_BCRYPT),
        'telegram_id' => '123456004'
    ],
    [
        'nama' => 'Arief Budiman',
        'nomor' => '081234567005',
        'alamat' => 'Jl. Gajah Mada No. 56, Yogyakarta',
        'ktp_ktm' => '3471052301990005',
        'email' => 'arief.budiman@email.com',
        'role' => 'penghuni kos',
        'username' => 'arief_b',
        'password' => password_hash('arief123', PASSWORD_BCRYPT),
        'telegram_id' => '123456005'
    ],
    // Data Penghuni 6-10
    [
        'nama' => 'Diana Permata',
        'nomor' => '081234567006',
        'alamat' => 'Jl. Veteran No. 89, Malang',
        'ktp_ktm' => '3573024411000006',
        'email' => 'diana.permata@email.com',
        'role' => 'penghuni kos',
        'username' => 'diana_p',
        'password' => password_hash('diana123', PASSWORD_BCRYPT),
        'telegram_id' => '123456006'
    ],
    [
        'nama' => 'Hendra Saputra',
        'nomor' => '081234567007',
        'alamat' => 'Jl. Hayam Wuruk No. 34, Solo',
        'ktp_ktm' => '3372011507980007',
        'email' => 'hendra.saputra@email.com',
        'role' => 'penghuni kos',
        'username' => 'hendra_s',
        'password' => password_hash('hendra123', PASSWORD_BCRYPT),
        'telegram_id' => '123456007'
    ],
    [
        'nama' => 'Indah Lestari',
        'nomor' => '081234567008',
        'alamat' => 'Jl. Panglima Sudirman No. 67, Medan',
        'ktp_ktm' => '1271065209990008',
        'email' => 'indah.lestari@email.com',
        'role' => 'penghuni kos',
        'username' => 'indah_l',
        'password' => password_hash('indah123', PASSWORD_BCRYPT),
        'telegram_id' => '123456008'
    ],
    [
        'nama' => 'Joko Susanto',
        'nomor' => '081234567009',
        'alamat' => 'Jl. Imam Bonjol No. 91, Palembang',
        'ktp_ktm' => '1671032806010009',
        'email' => 'joko.susanto@email.com',
        'role' => 'penghuni kos',
        'username' => 'joko_s',
        'password' => password_hash('joko123', PASSWORD_BCRYPT),
        'telegram_id' => '123456009'
    ],
    [
        'nama' => 'Kartika Sari',
        'nomor' => '081234567010',
        'alamat' => 'Jl. Gatot Subroto No. 45, Denpasar',
        'ktp_ktm' => '5171046701980010',
        'email' => 'kartika.sari@email.com',
        'role' => 'penghuni kos',
        'username' => 'kartika_s',
        'password' => password_hash('kartika123', PASSWORD_BCRYPT),
        'telegram_id' => '123456010'
    ],
    // Data Penghuni 11-15
    [
        'nama' => 'Lukman Hakim',
        'nomor' => '081234567011',
        'alamat' => 'Jl. Pahlawan No. 22, Makassar',
        'ktp_ktm' => '7371011204990011',
        'email' => 'lukman.hakim@email.com',
        'role' => 'penghuni kos',
        'username' => 'lukman_h',
        'password' => password_hash('lukman123', PASSWORD_BCRYPT),
        'telegram_id' => '123456011'
    ],
    [
        'nama' => 'Maya Anggraini',
        'nomor' => '081234567012',
        'alamat' => 'Jl. Proklamasi No. 88, Bogor',
        'ktp_ktm' => '3201055503000012',
        'email' => 'maya.anggraini@email.com',
        'role' => 'penghuni kos',
        'username' => 'maya_a',
        'password' => password_hash('maya123', PASSWORD_BCRYPT),
        'telegram_id' => '123456012'
    ],
    [
        'nama' => 'Nurdin Halim',
        'nomor' => '081234567013',
        'alamat' => 'Jl. Pemuda No. 77, Pontianak',
        'ktp_ktm' => '6171022309980013',
        'email' => 'nurdin.halim@email.com',
        'role' => 'penghuni kos',
        'username' => 'nurdin_h',
        'password' => password_hash('nurdin123', PASSWORD_BCRYPT),
        'telegram_id' => '123456013'
    ],
    [
        'nama' => 'Olivia Tan',
        'nomor' => '081234567014',
        'alamat' => 'Jl. Kartini No. 33, Batam',
        'ktp_ktm' => '2171066612010014',
        'email' => 'olivia.tan@email.com',
        'role' => 'penghuni kos',
        'username' => 'olivia_t',
        'password' => password_hash('olivia123', PASSWORD_BCRYPT),
        'telegram_id' => '123456014'
    ],
    [
        'nama' => 'Prasetyo Adi',
        'nomor' => '081234567015',
        'alamat' => 'Jl. Sutomo No. 55, Samarinda',
        'ktp_ktm' => '6472013108990015',
        'email' => 'prasetyo.adi@email.com',
        'role' => 'penghuni kos',
        'username' => 'prasetyo_a',
        'password' => password_hash('prasetyo123', PASSWORD_BCRYPT),
        'telegram_id' => '123456015'
    ],
    // Data Penghuni 16-20
    [
        'nama' => 'Qonita Zahira',
        'nomor' => '081234567016',
        'alamat' => 'Jl. Dewi Sartika No. 44, Tangerang',
        'ktp_ktm' => '3603044409000016',
        'email' => 'qonita.zahira@email.com',
        'role' => 'penghuni kos',
        'username' => 'qonita_z',
        'password' => password_hash('qonita123', PASSWORD_BCRYPT),
        'telegram_id' => '123456016'
    ],
    [
        'nama' => 'Rahmat Hidayat',
        'nomor' => '081234567017',
        'alamat' => 'Jl. Cendana No. 66, Bekasi',
        'ktp_ktm' => '3275021701980017',
        'email' => 'rahmat.hidayat@email.com',
        'role' => 'penghuni kos',
        'username' => 'rahmat_h',
        'password' => password_hash('rahmat123', PASSWORD_BCRYPT),
        'telegram_id' => '123456017'
    ],
    [
        'nama' => 'Sri Mulyani',
        'nomor' => '081234567018',
        'alamat' => 'Jl. Kenanga No. 99, Depok',
        'ktp_ktm' => '3276053302990018',
        'email' => 'sri.mulyani@email.com',
        'role' => 'penghuni kos',
        'username' => 'sri_m',
        'password' => password_hash('sri123', PASSWORD_BCRYPT),
        'telegram_id' => '123456018'
    ],
    [
        'nama' => 'Taufik Rahman',
        'nomor' => '081234567019',
        'alamat' => 'Jl. Melati No. 11, Cirebon',
        'ktp_ktm' => '3274012506010019',
        'email' => 'taufik.rahman@email.com',
        'role' => 'penghuni kos',
        'username' => 'taufik_r',
        'password' => password_hash('taufik123', PASSWORD_BCRYPT),
        'telegram_id' => '123456019'
    ],
    [
        'nama' => 'Vania Putri',
        'nomor' => '081234567020',
        'alamat' => 'Jl. Anggrek No. 28, Bandung',
        'ktp_ktm' => '3273067807000020',
        'email' => 'vania.putri@email.com',
        'role' => 'penghuni kos',
        'username' => 'vania_p',
        'password' => password_hash('vania123', PASSWORD_BCRYPT),
        'telegram_id' => '123456020'
    ],
];

$successCount = 0;
$errorCount = 0;
$errorMessages = [];

foreach ($penghuniData as $index => $penghuni) {
    $no = $index + 1;
    $result = createUser($penghuni);
    
    if ($result && !isset($result['error'])) {
        echo "<div class='result success'>✓ <strong>#{$no}</strong> - {$penghuni['nama']} berhasil ditambahkan</div>";
        $successCount++;
    } else {
        $errorMsg = isset($result['message']) ? $result['message'] : 'Unknown error';
        echo "<div class='result error'>✗ <strong>#{$no}</strong> - {$penghuni['nama']} gagal ditambahkan: {$errorMsg}</div>";
        $errorCount++;
        $errorMessages[] = "{$penghuni['nama']}: {$errorMsg}";
    }
    
    // Delay singkat untuk avoid rate limit
    usleep(200000); // 0.2 detik
}

echo "</div>";

// ============ SUMMARY ============
echo "<div class='summary'>";
echo "<h3>📊 Ringkasan Hasil</h3>";
echo "<div class='stats'>";
echo "<div class='stat-box'>";
echo "<div class='stat-number'>{$successCount}</div>";
echo "<div class='stat-label'>Berhasil</div>";
echo "</div>";
echo "<div class='stat-box'>";
echo "<div class='stat-number'>{$errorCount}</div>";
echo "<div class='stat-label'>Gagal</div>";
echo "</div>";
echo "<div class='stat-box'>";
echo "<div class='stat-number'>20</div>";
echo "<div class='stat-label'>Total Data</div>";
echo "</div>";
echo "<div class='stat-box'>";
$percentage = $successCount > 0 ? round(($successCount / 20) * 100) : 0;
echo "<div class='stat-number'>{$percentage}%</div>";
echo "<div class='stat-label'>Success Rate</div>";
echo "</div>";
echo "</div>";

if ($errorCount > 0) {
    echo "<div style='margin-top: 20px; padding: 15px; background: rgba(255,255,255,0.2); border-radius: 5px;'>";
    echo "<strong>⚠️ Detail Error:</strong><br>";
    foreach ($errorMessages as $errMsg) {
        echo "• {$errMsg}<br>";
    }
    echo "</div>";
}

echo "<a href='index.php?page=data_kos' class='btn'>👥 Lihat Data Penghuni Kos</a>";
echo "<a href='test_user_management.php' class='btn' style='margin-left: 10px;'>🧪 Test Management</a>";
echo "</div>";

// ============ INFO CREDENTIALS ============
echo "<div class='section'>";
echo "<h2>🔑 Info Login Credentials</h2>";
echo "<div class='info'>Semua penghuni kos memiliki password: <strong>[username]123</strong></div>";
echo "<div class='info'>Contoh: Username <strong>rizki_r</strong> → Password <strong>rizki123</strong></div>";
echo "<div class='warning'>⚠️ Password sudah di-hash dengan bcrypt untuk keamanan</div>";
echo "</div>";

echo "</div></body></html>";
?>
