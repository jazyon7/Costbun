<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Column id_user - Kamar Table</title>
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
        .error-box {
            background: #f8d7da;
            border: 2px solid #f5c6cb;
            color: #721c24;
            padding: 20px;
            border-radius: 8px;
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
        .success-box {
            background: #d4edda;
            border: 2px solid #c3e6cb;
            color: #155724;
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
        <h1>🔧 Setup Column id_user di Table Kamar</h1>
        
        <div class="error-box">
            <h3>❌ Error yang Terjadi:</h3>
            <code>Could not find the 'id_user' column of 'kamar' in the schema cache</code>
            <p style="margin-top: 15px;"><strong>Penyebab:</strong> Column <code>id_user</code> belum ditambahkan ke table <code>kamar</code> di database Supabase.</p>
        </div>
        
        <div class="info-box">
            <h3>📋 Yang Perlu Dilakukan:</h3>
            <p>Anda perlu menambahkan column <code>id_user</code> ke table <code>kamar</code> di Supabase dengan menjalankan SQL query di bawah ini.</p>
        </div>
        
        <div class="step">
            <h3>Step 1: Buka Supabase Dashboard</h3>
            <ol>
                <li>Login ke <a href="https://supabase.com/dashboard" target="_blank" style="color: #667eea;">Supabase Dashboard</a></li>
                <li>Pilih project Anda (Costbun)</li>
                <li>Klik menu <strong>"SQL Editor"</strong> di sidebar kiri</li>
            </ol>
        </div>
        
        <div class="step">
            <h3>Step 2: Jalankan SQL Query Ini</h3>
            <p>Copy SQL query di bawah ini dan paste ke SQL Editor, lalu klik <strong>"Run"</strong>:</p>
            
            <div class="sql-box">
                <button class="copy-btn" onclick="copySql()">📋 Copy</button>
                <pre id="sqlQuery">-- Add column id_user to kamar table
ALTER TABLE public.kamar 
ADD COLUMN IF NOT EXISTS id_user BIGINT;

-- Add foreign key constraint to user table
ALTER TABLE public.kamar 
ADD CONSTRAINT kamar_id_user_fkey 
FOREIGN KEY (id_user) 
REFERENCES public.user(id_user) 
ON DELETE SET NULL;

-- Add comment to column
COMMENT ON COLUMN public.kamar.id_user IS 'ID penghuni yang menempati kamar ini';

-- Create index for better query performance
CREATE INDEX IF NOT EXISTS idx_kamar_id_user ON public.kamar(id_user);</pre>
            </div>
        </div>
        
        <div class="step">
            <h3>Step 3: Verify Column Berhasil Ditambahkan</h3>
            <ol>
                <li>Di Supabase Dashboard, klik menu <strong>"Table Editor"</strong></li>
                <li>Pilih table <strong>"kamar"</strong></li>
                <li>Pastikan kolom <strong>"id_user"</strong> sudah muncul di list kolom</li>
            </ol>
        </div>
        
        <div class="step">
            <h3>Step 4: Test Assign Penghuni</h3>
            <p>Setelah SQL berhasil dijalankan:</p>
            <ol>
                <li>Kembali ke halaman Data Kamar</li>
                <li>Coba assign penghuni ke kamar</li>
                <li>Seharusnya sudah berhasil tanpa error</li>
            </ol>
            
            <div style="margin-top: 20px;">
                <a href="index.php?page=data_kamar" class="btn btn-success">✅ Ke Halaman Data Kamar</a>
                <a href="test_update_kamar.php" class="btn">🧪 Test Update Kamar</a>
            </div>
        </div>
        
        <div class="info-box">
            <h3>ℹ️ Penjelasan SQL:</h3>
            <ul>
                <li><strong>ALTER TABLE ... ADD COLUMN:</strong> Menambahkan kolom id_user dengan tipe BIGINT</li>
                <li><strong>IF NOT EXISTS:</strong> Hanya tambahkan jika belum ada (aman untuk dijalankan ulang)</li>
                <li><strong>FOREIGN KEY:</strong> Membuat relasi ke table user</li>
                <li><strong>ON DELETE SET NULL:</strong> Jika user dihapus, id_user di kamar jadi NULL (kamar kosong)</li>
                <li><strong>CREATE INDEX:</strong> Membuat index untuk query lebih cepat</li>
            </ul>
        </div>
        
        <div class="success-box" style="display: none;" id="successBox">
            <h3>✅ Column Berhasil Ditambahkan!</h3>
            <p>Sekarang Anda bisa assign penghuni ke kamar tanpa error.</p>
            <a href="index.php?page=data_kamar" class="btn btn-success">Kembali ke Data Kamar</a>
        </div>
        
        <hr style="margin: 40px 0;">
        
        <div class="step">
            <h3>📹 Video Tutorial (Opsional)</h3>
            <p>Jika Anda kurang jelas, berikut step-by-step visual:</p>
            <ol>
                <li>Login Supabase → <a href="https://supabase.com/dashboard" target="_blank">https://supabase.com/dashboard</a></li>
                <li>Pilih project → Costbun</li>
                <li>Sidebar → SQL Editor</li>
                <li>Klik "New query"</li>
                <li>Paste SQL di atas</li>
                <li>Klik tombol "Run" atau tekan Ctrl+Enter</li>
                <li>Tunggu sampai muncul "Success"</li>
                <li>Verify di Table Editor → kamar → lihat kolom id_user</li>
            </ol>
        </div>
        
        <div style="background: #fff3cd; border: 2px solid #ffeaa7; color: #856404; padding: 20px; border-radius: 8px; margin-top: 30px;">
            <h3>⚠️ Troubleshooting</h3>
            <p><strong>Jika muncul error "column already exists":</strong></p>
            <ul>
                <li>Berarti kolom sudah ada, coba refresh cache Supabase</li>
                <li>Atau jalankan query tanpa "IF NOT EXISTS"</li>
            </ul>
            
            <p style="margin-top: 15px;"><strong>Jika muncul error "permission denied":</strong></p>
            <ul>
                <li>Pastikan Anda login sebagai owner project</li>
                <li>Atau gunakan service role key, bukan anon key</li>
            </ul>
            
            <p style="margin-top: 15px;"><strong>Jika masih error setelah add column:</strong></p>
            <ul>
                <li>Restart Supabase project (Settings → General → Restart project)</li>
                <li>Clear browser cache dan refresh halaman</li>
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
