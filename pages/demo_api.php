<section class="content">
<style>
    .demo-api-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 20px; margin-top: 20px; }
    .demo-card { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    .demo-card h2 { color: #333; margin-bottom: 15px; font-size: 18px; border-bottom: 2px solid #3681ff; padding-bottom: 8px; }
    .demo-btn { padding: 10px 20px; margin: 5px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; transition: all 0.3s; font-family: 'Montserrat', Arial, sans-serif; }
    .demo-btn-primary { background: #3681ff; color: white; }
    .demo-btn-primary:hover { background: #2d6ad4; }
    .demo-btn-success { background: #4CAF50; color: white; }
    .demo-btn-success:hover { background: #45a049; }
    .demo-btn-danger { background: #ea4335; color: white; }
    .demo-btn-danger:hover { background: #c5372c; }
    .demo-btn-warning { background: #ffa726; color: white; }
    .demo-btn-warning:hover { background: #fb8c00; }
    .demo-result { margin-top: 15px; padding: 15px; background: #f8f9fa; border-radius: 8px; max-height: 400px; overflow-y: auto; font-size: 13px; }
    .demo-result pre { white-space: pre-wrap; word-wrap: break-word; }
    .demo-loading { color: #3681ff; font-weight: bold; }
    .demo-success { color: #4CAF50; }
    .demo-error { color: #ea4335; }
    .demo-info-box { background: #e3f2fd; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #3681ff; }
</style>

<div class="back-link">
    <a href="index.php?page=tools" style="color: #3681ff; text-decoration: none; font-weight: 500;">
        <i class="fas fa-arrow-left"></i> Kembali ke Tools
    </a>
</div>

<div class="content-header">
    <h1><i class="fas fa-rocket"></i> Demo API Supabase</h1>
    <p style="color: #666; margin-top: 10px;">Testing semua API endpoint yang tersedia</p>
</div>

<div class="demo-info-box">
    <strong>ℹ️ Info:</strong> Halaman ini untuk testing API Supabase secara interaktif. 
    Klik tombol untuk melakukan operasi CRUD.
</div>

<div class="demo-api-grid">
    <!-- Kamar API -->
    <div class="demo-card">
        <h2>🏠 API Kamar</h2>
        <button class="demo-btn demo-btn-primary" onclick="getKamar()">Get Semua Kamar</button>
        <button class="demo-btn demo-btn-primary" onclick="getKamarById()">Get Kamar by ID</button>
        <button class="demo-btn demo-btn-success" onclick="createKamar()">Create Kamar</button>
        <button class="demo-btn demo-btn-warning" onclick="updateKamarStatus()">Update Status</button>
        <div id="result-kamar" class="demo-result"></div>
    </div>

    <!-- User API -->
    <div class="demo-card">
        <h2>👤 API User</h2>
        <button class="demo-btn demo-btn-primary" onclick="getUser()">Get Semua User</button>
        <button class="demo-btn demo-btn-primary" onclick="getUserById()">Get User by ID</button>
        <button class="demo-btn demo-btn-success" onclick="createUserDemo()">Create User Demo</button>
        <div id="result-user" class="demo-result"></div>
    </div>

    <!-- Laporan API -->
    <div class="demo-card">
        <h2>📝 API Laporan</h2>
        <button class="demo-btn demo-btn-primary" onclick="getLaporan()">Get Semua Laporan</button>
        <button class="demo-btn demo-btn-primary" onclick="getLaporanById()">Get Laporan by ID</button>
        <button class="demo-btn demo-btn-warning" onclick="updateLaporanStatus()">Update Status</button>
        <div id="result-laporan" class="demo-result"></div>
    </div>

    <!-- Notifikasi API -->
    <div class="demo-card">
        <h2>🔔 API Notifikasi</h2>
        <button class="demo-btn demo-btn-primary" onclick="getNotifikasi()">Get Semua Notifikasi</button>
        <button class="demo-btn demo-btn-primary" onclick="getNotifikasiJSON()">Get JSON Format</button>
        <button class="demo-btn demo-btn-success" onclick="createNotifikasiDemo()">Create Notifikasi</button>
        <div id="result-notifikasi" class="demo-result"></div>
    </div>

    <!-- Tagihan API -->
    <div class="demo-card">
        <h2>💰 API Tagihan</h2>
        <button class="demo-btn demo-btn-primary" onclick="getTagihan()">Get Semua Tagihan</button>
        <button class="demo-btn demo-btn-primary" onclick="getTagihanById()">Get Tagihan by ID</button>
        <button class="demo-btn demo-btn-success" onclick="createTagihanDemo()">Create Tagihan</button>
        <div id="result-tagihan" class="demo-result"></div>
    </div>

    <!-- Keuangan API -->
    <div class="demo-card">
        <h2>💵 API Keuangan</h2>
        <button class="demo-btn demo-btn-primary" onclick="getKeuangan()">Get Semua Keuangan</button>
        <button class="demo-btn demo-btn-primary" onclick="getKeuanganById()">Get Keuangan by ID</button>
        <button class="demo-btn demo-btn-success" onclick="createKeuanganDemo()">Create Transaksi</button>
        <div id="result-keuangan" class="demo-result"></div>
    </div>
</div>

    <script>
        // Helper function
        function showLoading(elementId) {
            document.getElementById(elementId).innerHTML = '<p class="loading">⏳ Loading...</p>';
        }

        function showResult(elementId, data, isError = false) {
            const className = isError ? 'error' : 'success';
            document.getElementById(elementId).innerHTML = 
                `<pre class="${className}">${JSON.stringify(data, null, 2)}</pre>`;
        }

        // ===== KAMAR API =====
        async function getKamar() {
            showLoading('result-kamar');
            try {
                const response = await fetch('api/kamar.php?action=get');
                const data = await response.json();
                showResult('result-kamar', data);
            } catch (error) {
                showResult('result-kamar', {error: error.message}, true);
            }
        }

        async function getKamarById() {
            const id = prompt('Masukkan ID Kamar:', '1');
            if (!id) return;
            
            showLoading('result-kamar');
            try {
                const response = await fetch(`api/kamar.php?action=get&id=${id}`);
                const data = await response.json();
                showResult('result-kamar', data);
            } catch (error) {
                showResult('result-kamar', {error: error.message}, true);
            }
        }

        function createKamar() {
            const nama = prompt('Nama Kamar:', 'Demo-01');
            if (!nama) return;
            const harga = prompt('Harga:', '500000');
            
            window.location.href = `api/kamar.php?action=create&nama=${nama}&kasur=1&kipas=1&lemari=1&keranjang_sampah=1&ac=0&harga=${harga}&status=kosong`;
        }

        function updateKamarStatus() {
            const id = prompt('ID Kamar:', '1');
            if (!id) return;
            const status = prompt('Status (kosong/terisi):', 'kosong');
            
            window.location.href = `api/kamar.php?action=update_status&id=${id}&status=${status}`;
        }

        // ===== USER API =====
        async function getUser() {
            showLoading('result-user');
            try {
                const response = await fetch('api/user.php?action=get');
                const data = await response.json();
                showResult('result-user', data);
            } catch (error) {
                showResult('result-user', {error: error.message}, true);
            }
        }

        async function getUserById() {
            const id = prompt('Masukkan ID User:', '1');
            if (!id) return;
            
            showLoading('result-user');
            try {
                const response = await fetch(`api/user.php?action=get&id=${id}`);
                const data = await response.json();
                showResult('result-user', data);
            } catch (error) {
                showResult('result-user', {error: error.message}, true);
            }
        }

        function createUserDemo() {
            alert('Silakan gunakan halaman Tambah Data untuk create user dengan lengkap');
            window.location.href = 'index.php?page=tambah_data';
        }

        // ===== LAPORAN API =====
        async function getLaporan() {
            showLoading('result-laporan');
            try {
                const response = await fetch('api/laporan.php?action=get');
                const data = await response.json();
                showResult('result-laporan', data);
            } catch (error) {
                showResult('result-laporan', {error: error.message}, true);
            }
        }

        async function getLaporanById() {
            const id = prompt('Masukkan ID Laporan:', '1');
            if (!id) return;
            
            showLoading('result-laporan');
            try {
                const response = await fetch(`api/laporan.php?action=get&id=${id}`);
                const data = await response.json();
                showResult('result-laporan', data);
            } catch (error) {
                showResult('result-laporan', {error: error.message}, true);
            }
        }

        function updateLaporanStatus() {
            const id = prompt('ID Laporan:', '1');
            if (!id) return;
            const status = prompt('Status (diproses/selesai):', 'selesai');
            
            window.location.href = `api/laporan.php?action=update_status&id=${id}&status=${status}`;
        }

        // ===== NOTIFIKASI API =====
        async function getNotifikasi() {
            showLoading('result-notifikasi');
            try {
                const response = await fetch('api/notifikasi.php?action=get');
                const data = await response.json();
                showResult('result-notifikasi', data);
            } catch (error) {
                showResult('result-notifikasi', {error: error.message}, true);
            }
        }

        async function getNotifikasiJSON() {
            showLoading('result-notifikasi');
            try {
                const response = await fetch('api/notifikasi_data.php');
                const data = await response.json();
                showResult('result-notifikasi', data);
            } catch (error) {
                showResult('result-notifikasi', {error: error.message}, true);
            }
        }

        function createNotifikasiDemo() {
            alert('Silakan gunakan halaman Tambah Data untuk create notifikasi dengan lengkap');
            window.location.href = 'index.php?page=tambah_data';
        }

        // ===== TAGIHAN API =====
        async function getTagihan() {
            showLoading('result-tagihan');
            try {
                const response = await fetch('api/tagihan.php?action=get');
                const data = await response.json();
                showResult('result-tagihan', data);
            } catch (error) {
                showResult('result-tagihan', {error: error.message}, true);
            }
        }

        async function getTagihanById() {
            const id = prompt('Masukkan ID Tagihan:', '1');
            if (!id) return;
            
            showLoading('result-tagihan');
            try {
                const response = await fetch(`api/tagihan.php?action=get&id=${id}`);
                const data = await response.json();
                showResult('result-tagihan', data);
            } catch (error) {
                showResult('result-tagihan', {error: error.message}, true);
            }
        }

        function createTagihanDemo() {
            alert('Silakan gunakan halaman Tambah Data untuk create tagihan dengan lengkap');
            window.location.href = 'index.php?page=tambah_data';
        }

        // ===== KEUANGAN API =====
        async function getKeuangan() {
            showLoading('result-keuangan');
            try {
                const response = await fetch('api/keuangan.php?action=get');
                const data = await response.json();
                showResult('result-keuangan', data);
            } catch (error) {
                showResult('result-keuangan', {error: error.message}, true);
            }
        }

        async function getKeuanganById() {
            const id = prompt('Masukkan ID Keuangan:', '1');
            if (!id) return;
            
            showLoading('result-keuangan');
            try {
                const response = await fetch(`api/keuangan.php?action=get&id=${id}`);
                const data = await response.json();
                showResult('result-keuangan', data);
            } catch (error) {
                showResult('result-keuangan', {error: error.message}, true);
            }
        }

        function createKeuanganDemo() {
            alert('Silakan gunakan halaman Tambah Data untuk create keuangan dengan lengkap');
            window.location.href = 'index.php?page=tambah_data';
        }
    </script>
</section>
