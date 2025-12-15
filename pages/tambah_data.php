<?php
require_once __DIR__ . '/../config/supabase_helper.php';

// Get data from database for dropdowns
$kamarList = getKamar();
$userList = getUser();

// Check for errors and convert to empty array if error
if (!is_array($kamarList) || (isset($kamarList['error']) && $kamarList['error'])) {
    $kamarError = isset($kamarList['message']) ? $kamarList['message'] : 'Gagal memuat data kamar';
    $kamarList = [];
} else {
    $kamarError = null;
}

if (!is_array($userList) || (isset($userList['error']) && $userList['error'])) {
    $userError = isset($userList['message']) ? $userList['message'] : 'Gagal memuat data user';
    $userList = [];
} else {
    $userError = null;
}

$kamarCount = count($kamarList);
$userCount = count($userList);
?>

<section class="content" style="padding: 20px;">
    <header class="main-header" style="margin-bottom: 20px;">
        <div>
            <h2>📝 Form Tambah Data</h2>
            <p style="color: #666; font-size: 14px; margin-top: 5px;">Tambah data ke database Supabase 
                <span style="font-size: 12px; color: #999;">(<?= $kamarCount ?> kamar, <?= $userCount ?> user loaded)</span>
            </p>
        </div>
        <a href="index.php?page=tools" style="padding: 8px 16px; background: #3681ff; color: white; text-decoration: none; border-radius: 6px;">← Kembali</a>
    </header>

    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        
        <?php
        if (isset($_GET['msg'])) {
            echo '<div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #4CAF50;">✅ ' . htmlspecialchars($_GET['msg']) . '</div>';
        }
        if (isset($_GET['error'])) {
            echo '<div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #ea4335;">❌ ' . htmlspecialchars($_GET['error']) . '</div>';
        }
        ?>

        <style>
            .tabs { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
            .tab-btn { padding: 10px 20px; border: none; background: #f0f0f0; cursor: pointer; border-radius: 8px; font-weight: 500; transition: all 0.3s; }
            .tab-btn.active { background: #3681ff; color: white; }
            .tab-btn:hover { background: #e8e8e8; }
            .tab-btn.active:hover { background: #2d6ad4; }
            .tab-content-form { display: none; padding: 20px; border: 1px solid #eee; border-radius: 8px; }
            .tab-content-form.active { display: block; }
            .tab-content-form h3 { color: #333; margin-bottom: 20px; border-bottom: 2px solid #3681ff; padding-bottom: 10px; }
            .form-group { margin-bottom: 15px; }
            .form-group label { display: block; margin-bottom: 5px; font-weight: 600; color: #333; }
            .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; }
            .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #3681ff; }
            .form-group textarea { resize: vertical; min-height: 80px; }
            .btn-submit { background: #3681ff; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.3s; }
            .btn-submit:hover { background: #2d6ad4; }
        </style>

        <div class="tabs">
            <button class="tab-btn active" onclick="showTab('kamar')">Kamar</button>
            <button class="tab-btn" onclick="showTab('user')">Penyewa</button>
            <button class="tab-btn" onclick="showTab('laporan')">Laporan</button>
            <button class="tab-btn" onclick="showTab('notifikasi')">Notifikasi</button>
            <button class="tab-btn" onclick="showTab('tagihan')">Tagihan</button>
            <button class="tab-btn" onclick="showTab('keuangan')">Keuangan</button>
        </div>

        <!-- Form Kamar -->
        <div id="kamar" class="tab-content-form active">
            <h3>Tambah Kamar</h3>
            
            <!-- Daftar Kamar Yang Sudah Ada -->
            <?php if ($kamarError): ?>
            <div style="background: #fff3cd; color: #856404; padding: 12px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #ffa726;">
                ⚠️ <?= htmlspecialchars($kamarError) ?>
            </div>
            <?php elseif (count($kamarList) > 0): ?>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #3681ff;">
                <h4 style="margin: 0 0 10px 0; color: #333; font-size: 14px;">📋 Kamar Yang Sudah Ada (<?= count($kamarList) ?> kamar):</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; max-height: 150px; overflow-y: auto;">
                    <?php foreach ($kamarList as $k): ?>
                        <?php if (is_array($k) && isset($k['nama'], $k['status'], $k['harga'])): ?>
                        <div style="background: white; padding: 8px 12px; border-radius: 6px; font-size: 13px; border: 1px solid #e0e0e0;">
                            <strong><?= htmlspecialchars($k['nama']) ?></strong> - 
                            <span style="color: <?= $k['status'] == 'kosong' ? '#4CAF50' : '#ffa726' ?>;">
                                <?= ucfirst($k['status']) ?>
                            </span>
                            <br>
                            <span style="color: #666;">Rp <?= number_format($k['harga'], 0, ',', '.') ?></span>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php else: ?>
            <div style="background: #e3f2fd; color: #1565c0; padding: 12px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #3681ff;">
                ℹ️ Belum ada data kamar. Silakan tambahkan kamar pertama Anda.
            </div>
            <?php endif; ?>
            
            <form action="api/kamar.php" method="GET">
                <input type="hidden" name="action" value="create">
                
                <div class="form-group">
                    <label>Nama Kamar * <small style="color: #999;">(Contoh: A-01, B-02, dll)</small></label>
                    <input type="text" name="nama" required placeholder="A-01">
                </div>
                
                <div class="form-group">
                    <label>Jumlah Kasur *</label>
                    <input type="number" name="kasur" value="1" required>
                </div>
                
                <div class="form-group">
                    <label>Jumlah Kipas *</label>
                    <input type="number" name="kipas" value="1" required>
                </div>
                
                <div class="form-group">
                    <label>Jumlah Lemari *</label>
                    <input type="number" name="lemari" value="1" required>
                </div>
                
                <div class="form-group">
                    <label>Keranjang Sampah *</label>
                    <input type="number" name="keranjang_sampah" value="1" required>
                </div>
                
                <div class="form-group">
                    <label>Jumlah AC *</label>
                    <input type="number" name="ac" value="0" required>
                </div>
                
                <div class="form-group">
                    <label>Harga Sewa (Rp) *</label>
                    <input type="number" name="harga" required placeholder="500000">
                </div>
                
                <div class="form-group">
                    <label>Status *</label>
                    <select name="status" required>
                        <option value="kosong">Kosong</option>
                        <option value="terisi">Terisi</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-submit">Tambah Kamar</button>
            </form>
        </div>

        <!-- Form User -->
        <div id="user" class="tab-content-form">
            <h2>Tambah Penyewa/User</h2>
            
            <!-- Daftar User Yang Sudah Ada -->
            <?php if ($userError): ?>
            <div style="background: #fff3cd; color: #856404; padding: 12px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #ffa726;">
                ⚠️ <?= htmlspecialchars($userError) ?>
            </div>
            <?php elseif (count($userList) > 0): ?>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #3681ff;">
                <h4 style="margin: 0 0 10px 0; color: #333; font-size: 14px;">👥 Penyewa Yang Sudah Ada (<?= count($userList) ?> user):</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 10px; max-height: 150px; overflow-y: auto;">
                    <?php foreach ($userList as $u): ?>
                        <?php if (is_array($u) && isset($u['nama'], $u['role'])): ?>
                        <div style="background: white; padding: 8px 12px; border-radius: 6px; font-size: 13px; border: 1px solid #e0e0e0;">
                            <strong><?= htmlspecialchars($u['nama']) ?></strong>
                            <br>
                            <span style="color: #666;"><?= htmlspecialchars($u['email'] ?? '-') ?></span>
                            <br>
                            <span style="color: <?= $u['role'] == 'admin' ? '#3681ff' : '#4CAF50' ?>;">
                                <?= ucfirst($u['role']) ?>
                            </span>
                            <?php if (!empty($u['id_kamar']) && isset($kamarMap[$u['id_kamar']])): ?>
                            - <strong><?= htmlspecialchars($kamarMap[$u['id_kamar']]) ?></strong>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php else: ?>
            <div style="background: #e3f2fd; color: #1565c0; padding: 12px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #3681ff;">
                ℹ️ Belum ada data user. Silakan tambahkan user pertama Anda.
            </div>
            <?php endif; ?>
            
            <form action="form_handler.php" method="POST">
                <input type="hidden" name="table" value="user">
                
                <div class="form-group">
                    <label>Nama Lengkap *</label>
                    <input type="text" name="nama" required placeholder="John Doe">
                </div>
                
                <div class="form-group">
                    <label>Nomor HP/WA *</label>
                    <input type="text" name="nomor" required placeholder="08123456789">
                </div>
                
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" required placeholder="user@email.com">
                </div>
                
                <div class="form-group">
                    <label>Alamat *</label>
                    <textarea name="alamat" required placeholder="Jl. Contoh No. 123"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Nomor KTP/KTM *</label>
                    <input type="text" name="ktp_ktm" required placeholder="1234567890123456">
                </div>
                
                <div class="form-group">
                    <label>Role *</label>
                    <select name="role" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="penghuni kos">Penghuni Kos</option>
                        <option value="admin">Admin</option>
                    </select>
                    <small style="color: #666;">Sesuai dengan database: "penghuni kos" atau "admin"</small>
                </div>
                
                <div class="form-group">
                    <label>Kamar (opsional)</label>
                    <select name="id_kamar">
                        <option value="">-- Pilih Kamar (Opsional) --</option>
                        <?php if (is_array($kamarList)) { 
                            foreach ($kamarList as $kamar) { 
                                if (is_array($kamar) && isset($kamar['id_kamar'], $kamar['nama'])) {
                                    $statusLabel = $kamar['status'] == 'kosong' ? '✓ Tersedia' : '✗ Terisi';
                                    $disabled = $kamar['status'] != 'kosong' ? 'disabled' : '';
                                    ?>
                                    <option value="<?= $kamar['id_kamar'] ?>" <?= $disabled ?>>
                                        <?= htmlspecialchars($kamar['nama']) ?> - Rp <?= number_format($kamar['harga'], 0, ',', '.') ?> (<?= $statusLabel ?>)
                                    </option>
                                <?php } 
                            } 
                        } ?>
                    </select>
                    <small style="color: #666;">Pilih kamar jika user langsung menempati kamar. Kamar akan otomatis ter-update.</small>
                </div>
                
                <div class="form-group">
                    <label>Username *</label>
                    <input type="text" name="username" required placeholder="username123">
                </div>
                
                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="password" required minlength="6" placeholder="Min. 6 karakter">
                </div>
                
                <div class="form-group">
                    <label>Telegram ID (opsional)</label>
                    <input type="text" name="telegram_id" placeholder="123456789">
                    <small style="color: #666;">Untuk notifikasi via Telegram (opsional)</small>
                </div>
                
                <button type="submit" class="btn-submit">Tambah Penyewa</button>
            </form>
        </div>

        <!-- Form Laporan -->
        <div id="laporan" class="tab-content-form">
            <h2>Tambah Laporan</h2>
            <form action="form_handler.php" method="POST">
                <input type="hidden" name="table" value="laporan">
                
                <div class="form-group">
                    <label>Judul Laporan *</label>
                    <input type="text" name="judul_laporan" required>
                </div>
                
                <div class="form-group">
                    <label>Deskripsi *</label>
                    <textarea name="deskripsi" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Penyewa/Pelapor *</label>
                    <select name="id_user" required>
                        <option value="">-- Pilih Penyewa --</option>
                        <?php if (is_array($userList)) { foreach ($userList as $user) { ?>
                            <option value="<?= $user['id_user'] ?>"><?= htmlspecialchars($user['nama']) ?> - <?= htmlspecialchars($user['email']) ?></option>
                        <?php }} ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Status *</label>
                    <select name="status_laporan" required>
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Sumber *</label>
                    <select name="source" required>
                        <option value="web">Web</option>
                        <option value="telegram">Telegram</option>
                        <option value="mobile">Mobile</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>URL Gambar (opsional)</label>
                    <input type="text" name="gambar_url" placeholder="https://...">
                </div>
                
                <button type="submit" class="btn-submit">Tambah Laporan</button>
            </form>
        </div>

        <!-- Form Notifikasi -->
        <div id="notifikasi" class="tab-content-form">
            <h2>Tambah Notifikasi</h2>
            <form action="form_handler.php" method="POST">
                <input type="hidden" name="table" value="notifikasi">
                
                <div class="form-group">
                    <label>Tipe *</label>
                    <select name="tipe" required>
                        <option value="pembayaran">Pembayaran</option>
                        <option value="laporan">Laporan</option>
                        <option value="tagihan">Tagihan</option>
                        <option value="pengumuman">Pengumuman</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Judul *</label>
                    <input type="text" name="judul" required>
                </div>
                
                <div class="form-group">
                    <label>Pesan *</label>
                    <textarea name="pesan" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Penyewa/User *</label>
                    <select name="id_user" required>
                        <option value="">-- Pilih User --</option>
                        <?php if (is_array($userList)) { foreach ($userList as $user) { ?>
                            <option value="<?= $user['id_user'] ?>"><?= htmlspecialchars($user['nama']) ?> - <?= htmlspecialchars($user['email']) ?></option>
                        <?php }} ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Tanggal Kirim *</label>
                    <input type="date" name="tanggal_kirim" value="<?= date('Y-m-d') ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Status *</label>
                    <select name="status" required>
                        <option value="unread">Unread</option>
                        <option value="read">Read</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Dikirim via n8n?</label>
                    <select name="dikirim_n8n">
                        <option value="false">Tidak</option>
                        <option value="true">Ya</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-submit">Tambah Notifikasi</button>
            </form>
        </div>

        <!-- Form Tagihan -->
        <div id="tagihan" class="tab-content-form">
            <h2>Tambah Tagihan</h2>
            <form action="form_handler.php" method="POST">
                <input type="hidden" name="table" value="tagihan">
                
                <div class="form-group">
                    <label>Penyewa *</label>
                    <select name="id_user" required>
                        <option value="">-- Pilih Penyewa --</option>
                        <?php if (is_array($userList)) { foreach ($userList as $user) { ?>
                            <option value="<?= $user['id_user'] ?>"><?= htmlspecialchars($user['nama']) ?> - <?= htmlspecialchars($user['email']) ?></option>
                        <?php }} ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Kamar *</label>
                    <select name="id_kamar" required>
                        <option value="">-- Pilih Kamar --</option>
                        <?php if (is_array($kamarList)) { foreach ($kamarList as $kamar) { ?>
                            <option value="<?= $kamar['id_kamar'] ?>"><?= htmlspecialchars($kamar['nama']) ?> - Rp <?= number_format($kamar['harga'], 0, ',', '.') ?> (<?= ucfirst($kamar['status']) ?>)</option>
                        <?php }} ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Jumlah (Rp) *</label>
                    <input type="number" name="jumlah" required>
                </div>
                
                <div class="form-group">
                    <label>Tanggal Tagihan *</label>
                    <input type="date" name="tgl_tagihan" value="<?= date('Y-m-d') ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Tanggal Tempo *</label>
                    <input type="date" name="tgl_tempo" required>
                </div>
                
                <div class="form-group">
                    <label>Status Pembayaran *</label>
                    <select name="status_pembayaran" required>
                        <option value="belum_lunas">Belum Lunas</option>
                        <option value="lunas">Lunas</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Metode Pembayaran</label>
                    <select name="metode_pembayaran">
                        <option value="">-</option>
                        <option value="transfer">Transfer</option>
                        <option value="cash">Cash</option>
                        <option value="e-wallet">E-Wallet</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Bukti Pembayaran (URL)</label>
                    <input type="text" name="bukti_pembayaran">
                </div>
                
                <button type="submit" class="btn-submit">Tambah Tagihan</button>
            </form>
        </div>

        <!-- Form Keuangan -->
        <div id="keuangan" class="tab-content-form">
            <h2>Tambah Keuangan</h2>
            <form action="form_handler.php" method="POST">
                <input type="hidden" name="table" value="keuangan">
                
                <div class="form-group">
                    <label>Tanggal Transaksi *</label>
                    <input type="date" name="tanggal_tranksaksi" value="<?= date('Y-m-d') ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Jenis *</label>
                    <select name="jenis" required>
                        <option value="pemasukan">Pemasukan</option>
                        <option value="pengeluaran">Pengeluaran</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Keterangan *</label>
                    <textarea name="keterangan" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Jumlah (Rp) *</label>
                    <input type="number" name="jumlah" required>
                </div>
                
                <div class="form-group">
                    <label>Sumber *</label>
                    <input type="text" name="sumber" required placeholder="Pembayaran sewa / Perbaikan fasilitas">
                </div>
                
                <button type="submit" class="btn-submit">Tambah Transaksi</button>
            </form>
        </div>

    </div>

        <script>
            function showTab(tabName) {
                // Hide all tabs
                document.querySelectorAll('.tab-content-form').forEach(tab => {
                    tab.classList.remove('active');
                });
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                
                // Show selected tab
                document.getElementById(tabName).classList.add('active');
                event.target.classList.add('active');
            }
        </script>
    </div>
</section>
