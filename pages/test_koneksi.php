<?php
require_once __DIR__ . '/../config/supabase_helper.php';
?>

<section class="content" style="padding: 20px;">
    <header class="main-header" style="margin-bottom: 20px;">
        <div>
            <h2>🧪 Test Koneksi Supabase</h2>
            <p style="color: #666; font-size: 14px; margin-top: 5px;">Verifikasi koneksi ke semua tabel database</p>
        </div>
        <a href="index.php?page=tools" style="padding: 8px 16px; background: #3681ff; color: white; text-decoration: none; border-radius: 6px;">← Kembali</a>
    </header>

    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        
        <!-- Konfigurasi -->
        <div style="margin-bottom: 25px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
            <h3 style="color: #3681ff; margin-bottom: 15px;">📋 Konfigurasi</h3>
            <p><strong>URL:</strong> <?= SUPABASE_URL ?></p>
            <p><strong>API Key:</strong> <?= substr(SUPABASE_API_KEY, 0, 20) ?>...</p>
        </div>

        <!-- Test Kamar -->
        <div style="margin-bottom: 20px; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
            <h3 style="color: #333; margin-bottom: 15px;">🏠 Table KAMAR</h3>
            <?php
            $kamarList = getKamar();
            if (isset($kamarList['error'])) {
                echo '<p style="color: #ea4335; font-weight: bold;">❌ Error: ' . htmlspecialchars($kamarList['message']) . '</p>';
            } else {
                echo '<p style="color: #4CAF50; font-weight: bold;">✅ Koneksi berhasil!</p>';
                echo '<p style="color: #3681ff;">Total kamar: ' . count($kamarList) . '</p>';
                if (!empty($kamarList)) {
                    echo '<table style="width: 100%; border-collapse: collapse; margin-top: 10px;">';
                    echo '<tr style="background: #f8f9fa;"><th style="border: 1px solid #eee; padding: 10px;">ID</th><th style="border: 1px solid #eee; padding: 10px;">Nama</th><th style="border: 1px solid #eee; padding: 10px;">Status</th><th style="border: 1px solid #eee; padding: 10px;">Harga</th></tr>';
                    foreach (array_slice($kamarList, 0, 5) as $k) {
                        echo '<tr>';
                        echo '<td style="border: 1px solid #eee; padding: 10px;">' . $k['id_kamar'] . '</td>';
                        echo '<td style="border: 1px solid #eee; padding: 10px;">' . htmlspecialchars($k['nama']) . '</td>';
                        echo '<td style="border: 1px solid #eee; padding: 10px;">' . htmlspecialchars($k['status']) . '</td>';
                        echo '<td style="border: 1px solid #eee; padding: 10px;">Rp ' . number_format($k['harga'], 0, ',', '.') . '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                }
            }
            ?>
        </div>

        <!-- Test User -->
        <div style="margin-bottom: 20px; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
            <h3 style="color: #333; margin-bottom: 15px;">👤 Table USER</h3>
            <?php
            $userList = getUser();
            if (isset($userList['error'])) {
                echo '<p style="color: #ea4335; font-weight: bold;">❌ Error: ' . htmlspecialchars($userList['message']) . '</p>';
            } else {
                echo '<p style="color: #4CAF50; font-weight: bold;">✅ Koneksi berhasil!</p>';
                echo '<p style="color: #3681ff;">Total user: ' . count($userList) . '</p>';
                if (!empty($userList)) {
                    echo '<table style="width: 100%; border-collapse: collapse; margin-top: 10px;">';
                    echo '<tr style="background: #f8f9fa;"><th style="border: 1px solid #eee; padding: 10px;">ID</th><th style="border: 1px solid #eee; padding: 10px;">Nama</th><th style="border: 1px solid #eee; padding: 10px;">Email</th><th style="border: 1px solid #eee; padding: 10px;">Role</th></tr>';
                    foreach (array_slice($userList, 0, 5) as $u) {
                        echo '<tr>';
                        echo '<td style="border: 1px solid #eee; padding: 10px;">' . $u['id_user'] . '</td>';
                        echo '<td style="border: 1px solid #eee; padding: 10px;">' . htmlspecialchars($u['nama']) . '</td>';
                        echo '<td style="border: 1px solid #eee; padding: 10px;">' . htmlspecialchars($u['email']) . '</td>';
                        echo '<td style="border: 1px solid #eee; padding: 10px;">' . htmlspecialchars($u['role']) . '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                }
            }
            ?>
        </div>

        <!-- Test Laporan -->
        <div style="margin-bottom: 20px; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
            <h3 style="color: #333; margin-bottom: 15px;">📝 Table LAPORAN (dengan JOIN)</h3>
            <?php
            $laporanList = getLaporan();
            if (isset($laporanList['error'])) {
                echo '<p style="color: #ea4335; font-weight: bold;">❌ Error: ' . htmlspecialchars($laporanList['message']) . '</p>';
            } else {
                echo '<p style="color: #4CAF50; font-weight: bold;">✅ Koneksi berhasil!</p>';
                echo '<p style="color: #3681ff;">Total laporan: ' . count($laporanList) . '</p>';
                if (!empty($laporanList)) {
                    echo '<table style="width: 100%; border-collapse: collapse; margin-top: 10px;">';
                    echo '<tr style="background: #f8f9fa;"><th style="border: 1px solid #eee; padding: 10px;">ID</th><th style="border: 1px solid #eee; padding: 10px;">Judul</th><th style="border: 1px solid #eee; padding: 10px;">Pelapor</th><th style="border: 1px solid #eee; padding: 10px;">Status</th></tr>';
                    foreach (array_slice($laporanList, 0, 5) as $l) {
                        $pelapor = isset($l['user']['nama']) ? $l['user']['nama'] : 'Unknown';
                        echo '<tr>';
                        echo '<td style="border: 1px solid #eee; padding: 10px;">' . $l['id_laporan'] . '</td>';
                        echo '<td style="border: 1px solid #eee; padding: 10px;">' . htmlspecialchars($l['judul_laporan']) . '</td>';
                        echo '<td style="border: 1px solid #eee; padding: 10px;">' . htmlspecialchars($pelapor) . '</td>';
                        echo '<td style="border: 1px solid #eee; padding: 10px;">' . htmlspecialchars($l['status_laporan']) . '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                }
            }
            ?>
        </div>

        <!-- Test Notifikasi -->
        <div style="margin-bottom: 20px; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
            <h3 style="color: #333; margin-bottom: 15px;">🔔 Table NOTIFIKASI</h3>
            <?php
            $notifList = getNotifikasi();
            if (isset($notifList['error'])) {
                echo '<p style="color: #ea4335; font-weight: bold;">❌ Error: ' . htmlspecialchars($notifList['message']) . '</p>';
            } else {
                echo '<p style="color: #4CAF50; font-weight: bold;">✅ Koneksi berhasil!</p>';
                echo '<p style="color: #3681ff;">Total notifikasi: ' . count($notifList) . '</p>';
            }
            ?>
        </div>

        <!-- Kesimpulan -->
        <div style="padding: 20px; background: #d4edda; border-radius: 8px; border-left: 4px solid #4CAF50;">
            <h3 style="color: #155724; margin-bottom: 10px;">✅ Kesimpulan</h3>
            <p style="color: #155724;">
                Jika semua section menampilkan <strong>✅ Koneksi berhasil!</strong>, 
                maka website sudah terhubung dengan baik ke Supabase.
            </p>
        </div>

        <p style="text-align: center; color: #666; margin-top: 20px;">
            <small>Testing dilakukan pada: <?= date('Y-m-d H:i:s') ?></small>
        </p>
    </div>
</section>
