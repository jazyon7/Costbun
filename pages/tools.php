<section class="content" style="padding: 20px;">
    <header class="main-header" style="margin-bottom: 20px;">
        <h2>🚀 Tools & Utilities</h2>
    </header>

    <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <p style="color: #666; margin-bottom: 30px;">
            Tools untuk testing dan manajemen database Supabase
        </p>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
            
            <!-- Test Koneksi -->
            <a href="index.php?page=test_koneksi" style="text-decoration: none;">
                <div style="background: linear-gradient(135deg, #ffa726 0%, #fb8c00 100%); color: white; padding: 30px; border-radius: 12px; text-align: center; transition: transform 0.3s;">
                    <i class="fa-solid fa-plug" style="font-size: 48px; margin-bottom: 15px;"></i>
                    <h3 style="margin-bottom: 10px; font-size: 20px;">Test Koneksi</h3>
                    <p style="opacity: 0.9; font-size: 14px;">Cek status koneksi semua tabel Supabase</p>
                </div>
            </a>

            <!-- Demo API -->
            <a href="index.php?page=demo_api" style="text-decoration: none;">
                <div style="background: linear-gradient(135deg, #3681ff 0%, #2d6ad4 100%); color: white; padding: 30px; border-radius: 12px; text-align: center; transition: transform 0.3s;">
                    <i class="fa-solid fa-code" style="font-size: 48px; margin-bottom: 15px;"></i>
                    <h3 style="margin-bottom: 10px; font-size: 20px;">Demo API</h3>
                    <p style="opacity: 0.9; font-size: 14px;">Testing interaktif semua API endpoint</p>
                </div>
            </a>

            <!-- Tambah Data -->
            <a href="index.php?page=tambah_data" style="text-decoration: none;">
                <div style="background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); color: white; padding: 30px; border-radius: 12px; text-align: center; transition: transform 0.3s;">
                    <i class="fa-solid fa-plus-circle" style="font-size: 48px; margin-bottom: 15px;"></i>
                    <h3 style="margin-bottom: 10px; font-size: 20px;">Tambah Data</h3>
                    <p style="opacity: 0.9; font-size: 14px;">Form untuk menambah data ke semua tabel</p>
                </div>
            </a>

            <!-- Dokumentasi -->
            <a href="README_SUPABASE.md" target="_blank" style="text-decoration: none;">
                <div style="background: linear-gradient(135deg, #764ba2 0%, #667eea 100%); color: white; padding: 30px; border-radius: 12px; text-align: center; transition: transform 0.3s;">
                    <i class="fa-solid fa-book" style="font-size: 48px; margin-bottom: 15px;"></i>
                    <h3 style="margin-bottom: 10px; font-size: 20px;">Dokumentasi</h3>
                    <p style="opacity: 0.9; font-size: 14px;">Panduan lengkap API & helper functions</p>
                </div>
            </a>

        </div>

        <!-- Info Section -->
        <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #3681ff;">
            <h3 style="color: #3681ff; margin-bottom: 10px;">📊 Status Integrasi</h3>
            <ul style="list-style: none; padding-left: 0;">
                <li style="padding: 5px 0;"><span style="color: #4CAF50;">✓</span> Konfigurasi Supabase</li>
                <li style="padding: 5px 0;"><span style="color: #4CAF50;">✓</span> Helper Functions CRUD</li>
                <li style="padding: 5px 0;"><span style="color: #4CAF50;">✓</span> 6 API Endpoints</li>
                <li style="padding: 5px 0;"><span style="color: #4CAF50;">✓</span> Halaman Terintegrasi</li>
            </ul>
        </div>

        <!-- Quick Actions -->
        <div style="margin-top: 20px; padding: 20px; background: #fff3cd; border-radius: 8px; border-left: 4px solid #ffa726;">
            <h3 style="color: #856404; margin-bottom: 10px;">⚡ Quick Actions</h3>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="index.php?page=data_kamar" style="padding: 8px 16px; background: #3681ff; color: white; text-decoration: none; border-radius: 6px; font-size: 14px;">Data Kamar</a>
                <a href="index.php?page=data_kos" style="padding: 8px 16px; background: #3681ff; color: white; text-decoration: none; border-radius: 6px; font-size: 14px;">Data Kos</a>
                <a href="index.php?page=laporan" style="padding: 8px 16px; background: #3681ff; color: white; text-decoration: none; border-radius: 6px; font-size: 14px;">Laporan</a>
                <a href="index.php?page=notifikasi" style="padding: 8px 16px; background: #3681ff; color: white; text-decoration: none; border-radius: 6px; font-size: 14px;">Notifikasi</a>
            </div>
        </div>
    </div>
</section>

<style>
    section a div:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
</style>
