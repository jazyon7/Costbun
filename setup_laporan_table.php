<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Table Laporan</title>
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
            margin-bottom: 30px;
        }
        .info-box {
            background: #d1ecf1;
            border: 2px solid #bee5eb;
            color: #0c5460;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .sql-box {
            background: #2d2d30;
            color: #d4d4d4;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            position: relative;
        }
        .sql-box pre {
            margin: 0;
            white-space: pre-wrap;
            font-family: 'Courier New', monospace;
        }
        .copy-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #4ec9b0;
            color: #000;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .copy-btn:hover {
            background: #3da88a;
        }
        .step {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .step h3 {
            margin-top: 0;
            color: #667eea;
        }
        ol {
            line-height: 2;
        }
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 5px;
            font-weight: 600;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background: #5568d3;
        }
        .btn-success {
            background: #28a745;
        }
        .btn-success:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Setup Table Laporan - Add id_user Column</h1>
        
        <div class="info-box">
            <h3>📋 Persiapan Database untuk Fitur Laporan</h3>
            <p>Sebelum fitur laporan bisa digunakan, table <code>laporan</code> harus memiliki kolom <code>id_user</code> untuk menyimpan ID pengguna yang membuat laporan.</p>
        </div>
        
        <div class="step">
            <h3>Step 1: Buka Supabase SQL Editor</h3>
            <ol>
                <li>Login ke <a href="https://supabase.com/dashboard" target="_blank" style="color: #667eea;">Supabase Dashboard</a></li>
                <li>Pilih project <strong>Costbun</strong></li>
                <li>Klik menu <strong>"SQL Editor"</strong> di sidebar kiri</li>
                <li>Klik <strong>"New query"</strong></li>
            </ol>
        </div>
        
        <div class="step">
            <h3>Step 2: Jalankan SQL Query Ini</h3>
            <p>Copy SQL query di bawah dan paste ke SQL Editor, lalu klik <strong>"Run"</strong>:</p>
            
            <div class="sql-box">
                <button class="copy-btn" onclick="copySql()">📋 Copy</button>
                <pre id="sqlQuery">-- ========================================
-- Setup Table Laporan untuk Fitur Laporan
-- ========================================

-- 1. Add column id_user (Foreign key ke table user)
ALTER TABLE public.laporan 
ADD COLUMN IF NOT EXISTS id_user BIGINT;

-- 2. Add foreign key constraint (ONLY if not exists)
DO $$ 
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM pg_constraint 
        WHERE conname = 'laporan_id_user_fkey'
    ) THEN
        ALTER TABLE public.laporan 
        ADD CONSTRAINT laporan_id_user_fkey 
        FOREIGN KEY (id_user) 
        REFERENCES public."user"(id_user) 
        ON DELETE CASCADE;
    END IF;
END $$;

-- Note: ON DELETE CASCADE berarti jika user dihapus, 
-- semua laporannya ikut terhapus

-- 3. Add comment untuk dokumentasi
COMMENT ON COLUMN public.laporan.id_user 
IS 'ID user yang membuat laporan (pelapor)';

-- 4. Create index untuk performa query
CREATE INDEX IF NOT EXISTS idx_laporan_id_user 
ON public.laporan(id_user);

-- 5. Create index untuk status
CREATE INDEX IF NOT EXISTS idx_laporan_status 
ON public.laporan(status_laporan);

-- 6. Verify struktur table
SELECT 
    column_name,
    data_type,
    is_nullable,
    column_default
FROM information_schema.columns
WHERE table_name = 'laporan'
ORDER BY ordinal_position;</pre>
            </div>
        </div>
        
        <div class="step">
            <h3>Step 3: Verify Results</h3>
            <p>Setelah query berhasil, Anda akan melihat hasil seperti ini di bawah SQL Editor:</p>
            <ul>
                <li>✅ <strong>id_laporan</strong> - bigint, NOT NULL</li>
                <li>✅ <strong>judul_laporan</strong> - character varying</li>
                <li>✅ <strong>deskripsi</strong> - text</li>
                <li>✅ <strong>status_laporan</strong> - character varying</li>
                <li>✅ <strong>created_at</strong> - timestamp with time zone</li>
                <li>✅ <strong>id_user</strong> - bigint, NULLABLE (kolom baru)</li>
            </ul>
        </div>
        
        <div class="step">
            <h3>Step 4: Verify di Table Editor (Optional)</h3>
            <ol>
                <li>Klik menu <strong>"Table Editor"</strong> di sidebar</li>
                <li>Pilih table <strong>"laporan"</strong></li>
                <li>Pastikan kolom <strong>"id_user"</strong> muncul di list kolom</li>
                <li>Check Foreign Key icon (🔗) muncul di kolom id_user</li>
            </ol>
        </div>
        
        <div class="step">
            <h3>Step 5: Test Fitur Laporan</h3>
            <p>Sekarang fitur laporan siap digunakan!</p>
            <div style="margin-top: 20px;">
                <a href="index.php?page=laporan" class="btn btn-success">
                    ✅ Buka Halaman Laporan
                </a>
                <a href="FITUR_LAPORAN.md" class="btn" target="_blank">
                    📖 Baca Dokumentasi
                </a>
            </div>
        </div>
        
        <div class="info-box">
            <h3>ℹ️ Penjelasan SQL</h3>
            <ul>
                <li><strong>ALTER TABLE ADD COLUMN:</strong> Menambahkan kolom id_user bertipe BIGINT</li>
                <li><strong>IF NOT EXISTS:</strong> Aman dijalankan ulang, tidak error jika sudah ada</li>
                <li><strong>FOREIGN KEY:</strong> Membuat relasi ke table user</li>
                <li><strong>ON DELETE CASCADE:</strong> Jika user dihapus, laporan ikut terhapus (auto cleanup)</li>
                <li><strong>CREATE INDEX:</strong> Mempercepat query filter by user dan status</li>
            </ul>
        </div>
        
        <div style="background: #fff3cd; border: 2px solid #ffeaa7; color: #856404; padding: 20px; border-radius: 8px; margin-top: 30px;">
            <h3>⚠️ Troubleshooting</h3>
            
            <p><strong>Error: "column already exists"</strong></p>
            <ul>
                <li>Kolom sudah ditambahkan sebelumnya → Skip ke Step 5</li>
            </ul>
            
            <p style="margin-top: 15px;"><strong>Error: "relation user does not exist"</strong></p>
            <ul>
                <li>Check nama table: bisa "user" atau "users"</li>
                <li>Ganti di SQL: <code>REFERENCES public.users(id_user)</code></li>
            </ul>
            
            <p style="margin-top: 15px;"><strong>Error: "permission denied"</strong></p>
            <ul>
                <li>Pastikan login sebagai owner project</li>
                <li>Atau gunakan service_role key di config</li>
            </ul>
        </div>
    </div>
    
    <script>
        function copySql() {
            const sqlText = document.getElementById('sqlQuery').textContent;
            navigator.clipboard.writeText(sqlText).then(() => {
                const btn = document.querySelector('.copy-btn');
                btn.textContent = '✅ Copied!';
                setTimeout(() => {
                    btn.textContent = '📋 Copy';
                }, 2000);
            });
        }
    </script>
</body>
</html>
