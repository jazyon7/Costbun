<?php
require_once __DIR__ . '/config/supabase_helper.php';

echo "<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Insert Dummy Notifikasi - Costbun</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
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
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
        }
        
        .info-box {
            background: #e7f3ff;
            border: 2px solid #b3d9ff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .info-box h3 {
            color: #0066cc;
            margin-bottom: 10px;
        }
        
        .result {
            margin: 20px 0;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #4ecdc4;
        }
        
        .success {
            background: #d4edda;
            border-left-color: #28a745;
            color: #155724;
        }
        
        .error {
            background: #f8d7da;
            border-left-color: #dc3545;
            color: #721c24;
        }
        
        .loading {
            background: #fff3cd;
            border-left-color: #ffc107;
            color: #856404;
        }
        
        .notif-card {
            background: #f8f9fa;
            padding: 15px;
            margin: 10px 0;
            border-radius: 6px;
            border-left: 3px solid #667eea;
        }
        
        .notif-card strong {
            color: #667eea;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
        }
        
        .badge-pengumuman { background: #ff6b6b; color: white; }
        .badge-acara { background: #4ecdc4; color: white; }
        .badge-tagihan { background: #ffd93d; color: #333; }
        .badge-peringatan { background: #ff8c42; color: white; }
        .badge-maintenance { background: #6c5ce7; color: white; }
        .badge-info { background: #74b9ff; color: white; }
        
        .summary {
            background: #667eea;
            color: white;
            padding: 25px;
            border-radius: 8px;
            margin-top: 30px;
            text-align: center;
        }
        
        .summary h2 {
            margin-bottom: 15px;
        }
        
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔔 Insert Dummy Notifikasi</h1>
        
        <div class='info-box'>
            <h3>📋 Informasi</h3>
            <p>Script ini akan membuat data dummy notifikasi dengan berbagai tipe dan kondisi:</p>
            <ul style='margin-left: 20px; margin-top: 10px;'>
                <li>✅ 20 notifikasi dengan 6 tipe berbeda</li>
                <li>✅ Mix status: unread & read</li>
                <li>✅ Tanggal bervariasi (1-7 hari terakhir)</li>
                <li>✅ Assign ke penghuni yang sudah ada</li>
            </ul>
        </div>";

// Get all penghuni users
echo "<div class='result loading'>⏳ Mengambil data penghuni...</div>";
$users = getUser();
$penghuni = [];

foreach ($users as $user) {
    if ($user['role'] === 'penghuni kos') {
        $penghuni[] = $user;
    }
}

$totalPenghuni = count($penghuni);
echo "<div class='result success'>✅ Ditemukan <strong>$totalPenghuni penghuni kos</strong></div>";

if ($totalPenghuni === 0) {
    echo "<div class='result error'>❌ Tidak ada penghuni kos. Jalankan insert_penghuni_kos.php terlebih dahulu!</div>";
    echo "</div></body></html>";
    exit;
}

// Dummy notifications data
$notifications = [
    // Pengumuman
    [
        'tipe' => 'pengumuman',
        'judul' => 'Peraturan Baru Kost',
        'pesan' => 'Mulai bulan depan, jam malam untuk tamu akan diubah menjadi pukul 21:00 WIB. Mohon kerjasamanya.',
        'days_ago' => 7,
        'status' => 'read',
        'count' => 'all' // all or number
    ],
    [
        'tipe' => 'pengumuman',
        'judul' => 'Pemadaman Listrik',
        'pesan' => 'Akan ada pemadaman listrik pada hari Minggu, 17 Desember 2025 pukul 08:00-12:00 untuk maintenance panel listrik.',
        'days_ago' => 5,
        'status' => 'unread',
        'count' => 'all'
    ],
    [
        'tipe' => 'pengumuman',
        'judul' => 'Wifi Password Baru',
        'pesan' => 'Password WiFi telah diubah. Password baru: KostBahagia2025. Mohon update di perangkat Anda.',
        'days_ago' => 2,
        'status' => 'unread',
        'count' => 'all'
    ],
    
    // Acara
    [
        'tipe' => 'acara',
        'judul' => 'Arisan Bulanan Desember 2025',
        'pesan' => 'Arisan bulanan akan diadakan pada hari Sabtu, 21 Desember 2025 pukul 19:00 WIB di ruang bersama. Harap hadir tepat waktu! 🎉',
        'days_ago' => 6,
        'status' => 'read',
        'count' => 'all'
    ],
    [
        'tipe' => 'acara',
        'judul' => 'Gotong Royong Bulanan',
        'pesan' => 'Gotong royong rutin akan dilaksanakan hari Minggu besok pukul 07:00. Diharapkan semua penghuni berpartisipasi membersihkan area bersama.',
        'days_ago' => 1,
        'status' => 'unread',
        'count' => 'all'
    ],
    [
        'tipe' => 'acara',
        'judul' => 'Perayaan Tahun Baru 2026',
        'pesan' => 'Mari kita rayakan pergantian tahun bersama! Makan malam bersama pada tanggal 31 Desember 2025 pukul 20:00. Kontribusi sukarela.',
        'days_ago' => 3,
        'status' => 'unread',
        'count' => 'all'
    ],
    
    // Tagihan
    [
        'tipe' => 'tagihan',
        'judul' => 'Reminder Pembayaran Kos Desember 2025',
        'pesan' => 'Mohon segera lakukan pembayaran kos bulan Desember sebelum tanggal 20. Transfer ke BCA 1234567890 a.n. Ibu Siti. Terima kasih!',
        'days_ago' => 4,
        'status' => 'unread',
        'count' => 8
    ],
    [
        'tipe' => 'tagihan',
        'judul' => 'Tagihan Listrik Bulan November',
        'pesan' => 'Tagihan listrik kamar Anda bulan November: Rp 75.000. Mohon dibayarkan paling lambat tanggal 18 Desember.',
        'days_ago' => 3,
        'status' => 'unread',
        'count' => 5
    ],
    [
        'tipe' => 'tagihan',
        'judul' => 'Biaya Service AC',
        'pesan' => 'AC kamar Anda sudah di-service. Biaya service: Rp 150.000. Mohon ditransfer ke rekening kos.',
        'days_ago' => 5,
        'status' => 'read',
        'count' => 3
    ],
    [
        'tipe' => 'tagihan',
        'judul' => 'Pembayaran Internet Bulan Januari',
        'pesan' => 'Iuran internet bulan Januari 2026 sebesar Rp 50.000 per kamar. Mohon dibayarkan sebelum tanggal 25 Desember.',
        'days_ago' => 2,
        'status' => 'unread',
        'count' => 'all'
    ],
    
    // Peringatan
    [
        'tipe' => 'peringatan',
        'judul' => 'Peringatan Parkir Kendaraan',
        'pesan' => 'Harap parkir kendaraan dengan rapi dan tidak menghalangi akses jalan. Kendaraan yang parkir sembarangan akan ditindak tegas.',
        'days_ago' => 6,
        'status' => 'read',
        'count' => 'all'
    ],
    [
        'tipe' => 'peringatan',
        'judul' => 'Kebersihan Kamar Mandi Bersama',
        'pesan' => 'Mohon untuk selalu menjaga kebersihan kamar mandi bersama setelah digunakan. Ditemukan beberapa kamar mandi dalam kondisi kotor.',
        'days_ago' => 4,
        'status' => 'unread',
        'count' => 'all'
    ],
    [
        'tipe' => 'peringatan',
        'judul' => 'Kebisingan Malam Hari',
        'pesan' => 'Ada laporan kebisingan dari kamar 5 pada malam hari. Mohon untuk menjaga ketenangan setelah pukul 22:00.',
        'days_ago' => 2,
        'status' => 'unread',
        'count' => 1
    ],
    
    // Maintenance
    [
        'tipe' => 'maintenance',
        'judul' => 'Perbaikan Saluran Air',
        'pesan' => 'Akan dilakukan perbaikan saluran air pada hari Kamis, 19 Desember 2025. Air akan dimatikan sementara pukul 09:00-15:00.',
        'days_ago' => 5,
        'status' => 'unread',
        'count' => 'all'
    ],
    [
        'tipe' => 'maintenance',
        'judul' => 'Service AC Berkala',
        'pesan' => 'Service AC berkala akan dilakukan minggu depan. Mohon konfirmasi waktu yang sesuai untuk kamar Anda.',
        'days_ago' => 3,
        'status' => 'unread',
        'count' => 10
    ],
    [
        'tipe' => 'maintenance',
        'judul' => 'Pengecatan Dinding Luar',
        'pesan' => 'Akan dilakukan pengecatan dinding luar gedung kos mulai tanggal 22 Desember. Mohon tutup jendela dengan rapat.',
        'days_ago' => 1,
        'status' => 'unread',
        'count' => 'all'
    ],
    
    // Info
    [
        'tipe' => 'info',
        'judul' => 'Nomor Kontak Darurat',
        'pesan' => 'Untuk keadaan darurat, hubungi: Ibu Siti (0812-3456-7890), Security (0857-1234-5678), Ambulance (118), Polisi (110).',
        'days_ago' => 7,
        'status' => 'read',
        'count' => 'all'
    ],
    [
        'tipe' => 'info',
        'judul' => 'Jam Operasional Laundry',
        'pesan' => 'Laundry kos buka setiap hari Senin-Sabtu pukul 08:00-20:00. Harga: Rp 5.000/kg, minimal 3kg.',
        'days_ago' => 5,
        'status' => 'read',
        'count' => 'all'
    ],
    [
        'tipe' => 'info',
        'judul' => 'Fasilitas Dapur Bersama',
        'pesan' => 'Dapur bersama tersedia untuk memasak ringan. Mohon bersihkan setelah digunakan dan jangan lupa matikan kompor.',
        'days_ago' => 4,
        'status' => 'read',
        'count' => 'all'
    ],
    [
        'tipe' => 'info',
        'judul' => 'Prosedur Check-Out Sementara',
        'pesan' => 'Jika akan pulang kampung/liburan lebih dari 3 hari, mohon lapor ke pengelola untuk keamanan kamar Anda.',
        'days_ago' => 1,
        'status' => 'unread',
        'count' => 'all'
    ]
];

echo "<div class='result loading'>⏳ Mulai insert data notifikasi...</div>";

$successCount = 0;
$errorCount = 0;

foreach ($notifications as $index => $notif) {
    $num = $index + 1;
    echo "<div class='notif-card'>";
    echo "<strong>[$num] {$notif['judul']}</strong> ";
    echo "<span class='badge badge-{$notif['tipe']}'>{$notif['tipe']}</span><br>";
    
    // Calculate date
    $date = date('Y-m-d', strtotime("-{$notif['days_ago']} days"));
    
    // Determine recipients
    $recipients = [];
    if ($notif['count'] === 'all') {
        $recipients = $penghuni;
        echo "📤 Broadcast ke <strong>semua penghuni ($totalPenghuni orang)</strong><br>";
    } else {
        // Random selection
        $shuffled = $penghuni;
        shuffle($shuffled);
        $recipients = array_slice($shuffled, 0, $notif['count']);
        echo "📤 Kirim ke <strong>{$notif['count']} penghuni</strong><br>";
    }
    
    // Insert notification for each recipient
    $localSuccess = 0;
    $localError = 0;
    
    foreach ($recipients as $recipient) {
        $data = [
            'tipe' => $notif['tipe'],
            'judul' => $notif['judul'],
            'pesan' => $notif['pesan'],
            'tanggal_kirim' => $date,
            'status' => $notif['status'],
            'dikirim_n8n' => 'false',
            'id_user' => (int)$recipient['id_user']
        ];
        
        $result = createNotifikasi($data);
        
        if (isset($result['error'])) {
            $localError++;
            $errorCount++;
        } else {
            $localSuccess++;
            $successCount++;
        }
    }
    
    if ($localError === 0) {
        echo "✅ Berhasil insert <strong>$localSuccess notifikasi</strong><br>";
    } else {
        echo "⚠️ Berhasil: $localSuccess, Gagal: $localError<br>";
    }
    
    echo "</div>";
    
    // Delay to avoid rate limiting
    usleep(100000); // 0.1 second
}

// Summary
echo "<div class='summary'>";
echo "<h2>🎉 Proses Selesai!</h2>";
echo "<p style='font-size: 20px; margin: 10px 0;'><strong>$successCount</strong> notifikasi berhasil dibuat</p>";
if ($errorCount > 0) {
    echo "<p style='font-size: 16px;'><strong>$errorCount</strong> notifikasi gagal</p>";
}
echo "<p style='margin-top: 20px;'>Silakan cek halaman notifikasi untuk melihat hasilnya:</p>";
echo "<a href='http://costbun.test/index.php?page=notifikasi' class='btn' target='_blank'>📋 Lihat Halaman Notifikasi</a>";
echo "<a href='http://costbun.test/test_notifikasi.html' class='btn' target='_blank' style='background: #4ecdc4; margin-left: 10px;'>🧪 Halaman Testing</a>";
echo "</div>";

echo "</div></body></html>";
?>
