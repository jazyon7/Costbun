<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Relasi Kamar-Penghuni</title>
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
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        .sql-block {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 20px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            overflow-x: auto;
            margin: 15px 0;
            line-height: 1.6;
        }
        .step {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        .step h3 {
            color: #667eea;
            margin-bottom: 10px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s;
            margin: 5px;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }
        .btn-success {
            background: #4CAF50;
        }
        .btn-success:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Setup Relasi Kamar-Penghuni</h1>
        <p class="subtitle">Menambahkan field id_user ke table kamar untuk relasi dengan penghuni</p>

        <div class="alert alert-warning">
            <strong>⚠️ Penting!</strong> Proses ini akan menambahkan kolom baru ke table kamar di Supabase.
            Pastikan Anda memiliki akses ke Supabase Dashboard.
        </div>

        <!-- Step 1 -->
        <div class="step">
            <h3>📋 Step 1: Buka Supabase SQL Editor</h3>
            <ol>
                <li>Login ke <a href="https://supabase.com/dashboard" target="_blank">Supabase Dashboard</a></li>
                <li>Pilih project Anda</li>
                <li>Buka menu <strong>SQL Editor</strong> di sidebar kiri</li>
                <li>Klik <strong>New Query</strong></li>
            </ol>
        </div>

        <!-- Step 2 -->
        <div class="step">
            <h3>🔨 Step 2: Jalankan SQL Query</h3>
            <p>Copy dan paste SQL berikut ke SQL Editor, lalu klik <strong>Run</strong>:</p>
            
            <div class="sql-block">
-- Tambah kolom id_user ke table kamar
ALTER TABLE public.kamar 
ADD COLUMN IF NOT EXISTS id_user BIGINT;

-- Tambah foreign key constraint ke table user
ALTER TABLE public.kamar 
ADD CONSTRAINT kamar_id_user_fkey 
FOREIGN KEY (id_user) 
REFERENCES public.user(id_user) 
ON DELETE SET NULL;

-- Tambah comment untuk dokumentasi
COMMENT ON COLUMN public.kamar.id_user IS 'ID penghuni yang menempati kamar ini';

-- Update status kamar yang sudah ada
UPDATE public.kamar 
SET id_user = NULL 
WHERE status = 'kosong';
            </div>
        </div>

        <!-- Step 3 -->
        <div class="step">
            <h3>✅ Step 3: Verifikasi Perubahan</h3>
            <p>Pastikan query berhasil dijalankan dengan melihat:</p>
            <ul style="margin-left: 20px; margin-top: 10px;">
                <li>Message: <code>Success. No rows returned</code></li>
                <li>Atau buka <strong>Table Editor</strong> → <strong>kamar</strong> → Cek ada kolom <code>id_user</code></li>
            </ul>
        </div>

        <!-- Step 4 -->
        <div class="step">
            <h3>🧪 Step 4: Test Fitur</h3>
            <p>Setelah SQL berhasil, test fitur assign penghuni:</p>
            <ol>
                <li>Buka halaman Data Kamar</li>
                <li>Klik tombol "Assign Penghuni" pada kamar kosong</li>
                <li>Pilih penghuni dari dropdown</li>
                <li>Klik "Assign Penghuni"</li>
                <li>Verifikasi nama penghuni muncul di kartu kamar</li>
                <li>Status kamar otomatis berubah jadi "Terisi"</li>
            </ol>
        </div>

        <?php
        // Cek apakah sudah ada kolom id_user
        require_once 'config/supabase_helper.php';
        
        $kamarList = getKamar();
        $hasIdUser = false;
        
        if (!empty($kamarList) && is_array($kamarList) && count($kamarList) > 0) {
            $firstKamar = $kamarList[0];
            $hasIdUser = array_key_exists('id_user', $firstKamar);
        }
        
        if ($hasIdUser) {
            echo '<div class="alert alert-success">
                ✅ <strong>Kolom id_user sudah ada!</strong> Anda bisa langsung menggunakan fitur assign penghuni.
            </div>';
        } else {
            echo '<div class="alert alert-info">
                ℹ️ <strong>Kolom id_user belum ada.</strong> Silakan jalankan SQL query di Step 2.
            </div>';
        }
        ?>

        <!-- Step 5 (Optional) -->
        <div class="step">
            <h3>🎨 Step 5 (Opsional): Assign Data Dummy</h3>
            <p>Jika Anda ingin assign penghuni dummy ke beberapa kamar untuk testing:</p>
            
            <div class="sql-block">
-- Contoh assign penghuni ke kamar (sesuaikan ID)
-- Cek ID user dan kamar terlebih dahulu

-- Assign user ID 2 ke kamar A-01
UPDATE public.kamar 
SET id_user = 2, status = 'terisi' 
WHERE nama = 'A-01';

-- Assign user ID 3 ke kamar A-02
UPDATE public.kamar 
SET id_user = 3, status = 'terisi' 
WHERE nama = 'A-02';

-- Assign user ID 4 ke kamar B-01
UPDATE public.kamar 
SET id_user = 4, status = 'terisi' 
WHERE nama = 'B-01';
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="index.php?page=data_kamar" class="btn btn-success">
                🚀 Buka Data Kamar
            </a>
            <a href="https://supabase.com/dashboard" target="_blank" class="btn">
                🔗 Buka Supabase Dashboard
            </a>
        </div>

        <!-- Info Schema -->
        <div class="step" style="margin-top: 30px;">
            <h3>📊 Struktur Table Kamar (Setelah Update)</h3>
            <div class="sql-block">
CREATE TABLE public.kamar (
  id_kamar INTEGER PRIMARY KEY,
  nama VARCHAR NOT NULL,
  kasur INTEGER NOT NULL,
  kipas INTEGER NOT NULL,
  lemari INTEGER NOT NULL,
  keranjang_sampah INTEGER NOT NULL,
  ac INTEGER NOT NULL,
  harga INTEGER NOT NULL,
  status VARCHAR NOT NULL,
  id_user BIGINT,  -- ← KOLOM BARU
  FOREIGN KEY (id_user) REFERENCES public.user(id_user) ON DELETE SET NULL
);
            </div>
        </div>
    </div>
</body>
</html>
