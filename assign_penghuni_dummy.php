<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Penghuni Dummy ke Kamar</title>
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
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
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
        .summary {
            background: #667eea;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
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
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏠 Assign Penghuni Dummy ke Kamar</h1>
        <p class="subtitle">Otomatis assign penghuni dari data dummy ke kamar yang tersedia</p>

        <?php
        require_once 'config/supabase_helper.php';
        require_once 'config/supabase_request.php';

        // Cek apakah kolom id_user sudah ada
        $kamarList = getKamar();
        if (empty($kamarList) || !is_array($kamarList)) {
            echo '<div class="result error">❌ Tidak ada data kamar</div>';
            exit;
        }

        $firstKamar = $kamarList[0];
        if (!array_key_exists('id_user', $firstKamar)) {
            echo '<div class="result error">
                ❌ Kolom id_user belum ada di table kamar. 
                Silakan jalankan <a href="setup_kamar_penghuni.php">Setup SQL</a> terlebih dahulu.
            </div>';
            exit;
        }

        echo '<div class="result success">✅ Kolom id_user sudah ada. Memulai assign penghuni...</div>';

        // Ambil semua user penghuni kos
        $userList = getUser();
        $penghuniList = [];
        
        if (is_array($userList)) {
            foreach ($userList as $user) {
                if (strtolower($user['role']) === 'penghuni kos') {
                    $penghuniList[] = $user;
                }
            }
        }

        echo '<div class="result info">
            📊 Ditemukan ' . count($penghuniList) . ' penghuni kos dan ' . count($kamarList) . ' kamar
        </div>';

        // Assign penghuni ke kamar kosong
        $successCount = 0;
        $errorCount = 0;
        $assignedList = [];

        // Shuffle penghuni agar random
        shuffle($penghuniList);
        
        $penghuniIndex = 0;
        
        foreach ($kamarList as $kamar) {
            // Skip jika sudah ada penghuni
            if (!empty($kamar['id_user'])) {
                echo '<div class="result info">
                    ℹ️ Kamar ' . htmlspecialchars($kamar['nama']) . ' sudah terisi - skip
                </div>';
                continue;
            }

            // Skip jika tidak ada penghuni lagi
            if ($penghuniIndex >= count($penghuniList)) {
                echo '<div class="result info">
                    ℹ️ Tidak ada penghuni tersisa untuk kamar ' . htmlspecialchars($kamar['nama']) . '
                </div>';
                break;
            }

            $penghuni = $penghuniList[$penghuniIndex];
            $penghuniIndex++;

            // Assign penghuni ke kamar
            $data = [
                'id_user' => (int)$penghuni['id_user'],
                'status' => 'terisi'
            ];

            $result = updateKamar($kamar['id_kamar'], $data);

            if (isset($result['error'])) {
                echo '<div class="result error">
                    ❌ Gagal assign ' . htmlspecialchars($penghuni['nama']) . ' ke kamar ' . htmlspecialchars($kamar['nama'])
                </div>';
                $errorCount++;
            } else {
                echo '<div class="result success">
                    ✅ <strong>' . htmlspecialchars($penghuni['nama']) . '</strong> → Kamar <strong>' . htmlspecialchars($kamar['nama']) . '</strong>
                </div>';
                $successCount++;
                $assignedList[] = [
                    'penghuni' => $penghuni['nama'],
                    'kamar' => $kamar['nama']
                ];
            }

            // Delay untuk avoid rate limit
            usleep(200000); // 0.2 detik
        }

        // Summary
        echo '<div class="summary">';
        echo '<h3>📊 Ringkasan Hasil</h3>';
        echo '<div class="stats">';
        echo '<div class="stat-box">';
        echo '<div class="stat-number">' . $successCount . '</div>';
        echo '<div>Berhasil</div>';
        echo '</div>';
        echo '<div class="stat-box">';
        echo '<div class="stat-number">' . $errorCount . '</div>';
        echo '<div>Gagal</div>';
        echo '</div>';
        echo '<div class="stat-box">';
        echo '<div class="stat-number">' . count($penghuniList) . '</div>';
        echo '<div>Total Penghuni</div>';
        echo '</div>';
        echo '<div class="stat-box">';
        echo '<div class="stat-number">' . count($kamarList) . '</div>';
        echo '<div>Total Kamar</div>';
        echo '</div>';
        echo '</div>';

        if ($successCount > 0) {
            echo '<div style="margin-top: 20px; padding: 15px; background: rgba(255,255,255,0.2); border-radius: 5px;">';
            echo '<strong>✅ Penghuni yang berhasil di-assign:</strong><br>';
            foreach ($assignedList as $assigned) {
                echo '• ' . htmlspecialchars($assigned['penghuni']) . ' → Kamar ' . htmlspecialchars($assigned['kamar']) . '<br>';
            }
            echo '</div>';
        }

        echo '<a href="index.php?page=data_kamar" class="btn">👁️ Lihat Data Kamar</a>';
        echo '</div>';
        ?>
    </div>
</body>
</html>
