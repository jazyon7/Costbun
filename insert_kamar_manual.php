<?php
require_once __DIR__ . '/config/supabase_helper.php';

echo "<h1>🔧 Insert Kamar Manual (Alternatif)</h1>";
echo "<style>body{font-family:Arial;padding:20px;} .success{color:green;} .error{color:red;} .info{color:blue;}</style>";

echo "<p class='info'>💡 Karena fungsi createKamar() gagal, silakan tambahkan kamar langsung melalui:</p>";
echo "<ol>";
echo "<li><strong>Supabase Dashboard</strong> - Table Editor → kamar → Insert Row</li>";
echo "<li><strong>SQL Editor</strong> di Supabase (Recommended)</li>";
echo "</ol>";

echo "<h2>SQL Query untuk Insert 15 Kamar:</h2>";
echo "<textarea style='width:100%; height:400px; font-family:monospace; padding:10px;'>";
echo "-- Copy paste SQL ini ke Supabase SQL Editor\n\n";
echo "INSERT INTO kamar (nama, kasur, kipas, lemari, keranjang_sampah, ac, harga, status) VALUES\n";
echo "('A-01', 1, 1, 1, 1, 0, 500000, 'kosong'),\n";
echo "('A-02', 1, 1, 1, 1, 1, 750000, 'terisi'),\n";
echo "('A-03', 1, 1, 1, 1, 0, 500000, 'terisi'),\n";
echo "('A-04', 2, 2, 1, 1, 1, 900000, 'kosong'),\n";
echo "('A-05', 1, 1, 1, 1, 0, 500000, 'terisi'),\n";
echo "('B-01', 1, 1, 1, 1, 1, 750000, 'terisi'),\n";
echo "('B-02', 1, 1, 1, 1, 0, 500000, 'kosong'),\n";
echo "('B-03', 1, 1, 1, 1, 1, 750000, 'terisi'),\n";
echo "('B-04', 2, 2, 2, 1, 1, 1000000, 'kosong'),\n";
echo "('B-05', 1, 1, 1, 1, 0, 500000, 'terisi'),\n";
echo "('C-01', 1, 1, 1, 1, 1, 750000, 'kosong'),\n";
echo "('C-02', 1, 1, 1, 1, 0, 500000, 'terisi'),\n";
echo "('C-03', 1, 1, 1, 1, 1, 750000, 'kosong'),\n";
echo "('C-04', 1, 1, 1, 1, 0, 500000, 'terisi'),\n";
echo "('C-05', 2, 2, 1, 1, 1, 900000, 'terisi');\n";
echo "</textarea>";

echo "<h2>📋 Langkah-langkah:</h2>";
echo "<ol>";
echo "<li>Login ke <a href='https://supabase.com/dashboard' target='_blank'>Supabase Dashboard</a></li>";
echo "<li>Pilih project Anda</li>";
echo "<li>Klik <strong>SQL Editor</strong> di sidebar kiri</li>";
echo "<li>Copy-paste SQL query di atas</li>";
echo "<li>Klik <strong>Run</strong></li>";
echo "<li>Refresh halaman ini: <a href='index.php?page=dashboard'>Dashboard</a></li>";
echo "</ol>";

echo "<h2>🔍 Debug Info:</h2>";
echo "<p><a href='debug_supabase_api.php' target='_blank'>Lihat Detail Error</a></p>";

echo "<h2>✅ Data Lain yang Sudah Berhasil:</h2>";
echo "<ul class='success'>";
echo "<li>✓ 15 User</li>";
echo "<li>✓ 15 Laporan</li>";
echo "<li>✓ 15 Notifikasi</li>";
echo "<li>✓ 15 Tagihan</li>";
echo "<li>✓ 15 Keuangan</li>";
echo "</ul>";

echo "<p class='info'>Hanya data Kamar yang perlu ditambahkan manual via SQL di atas.</p>";

echo "<hr>";
echo "<p><a href='index.php' style='padding:10px 20px; background:#3681ff; color:white; text-decoration:none; border-radius:5px;'>Kembali ke Dashboard</a></p>";
?>
