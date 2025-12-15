<?php
require_once __DIR__ . '/../config/supabase_helper.php';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_SESSION['id_user'] ?? null;
    
    if (!$id_user) {
        echo "<script>alert('Session expired. Please login again.'); window.location.href='login.php';</script>";
        exit;
    }
    
    // Update profile
    if (isset($_POST['edit_profile'])) {
        $updateData = [
            'nama' => trim($_POST['nama']),
            'email' => trim($_POST['email']),
            'nomor' => trim($_POST['nomor']),
            'username' => trim($_POST['username'])
        ];
        
        error_log("Update profile - ID: $id_user, Data: " . json_encode($updateData));
        
        $result = updateUser($id_user, $updateData);
        
        if (isset($result['error'])) {
            $_SESSION['pesan_error'] = 'Gagal memperbarui profile: ' . ($result['message'] ?? 'Unknown error');
            echo "<script>window.location.href='index.php?page=profile';</script>";
            exit;
        } else {
            // Update session data
            $_SESSION['nama'] = $updateData['nama'];
            $_SESSION['email'] = $updateData['email'];
            $_SESSION['username'] = $updateData['username'];
            
            $_SESSION['pesan_sukses'] = 'Profile berhasil diperbarui!';
            echo "<script>window.location.href='index.php?page=profile';</script>";
            exit;
        }
    }
    
    // Change password
    if (isset($_POST['ganti_password'])) {
        $password_lama = $_POST['password_lama'] ?? '';
        $password_baru = $_POST['password_baru'] ?? '';
        $password_konfirmasi = $_POST['password_konfirmasi'] ?? '';
        
        // Validasi
        if (empty($password_lama) || empty($password_baru) || empty($password_konfirmasi)) {
            $_SESSION['pesan_error'] = 'Semua field password wajib diisi!';
        } else if ($password_baru !== $password_konfirmasi) {
            $_SESSION['pesan_error'] = 'Password baru dan konfirmasi tidak cocok!';
        } else if (strlen($password_baru) < 6) {
            $_SESSION['pesan_error'] = 'Password baru minimal 6 karakter!';
        } else {
            // Get current user data
            $currentUser = getUser($id_user);
            
            if (!$currentUser) {
                $_SESSION['pesan_error'] = 'User tidak ditemukan!';
            } else {
                // Verify old password
                if (password_verify($password_lama, $currentUser['password'])) {
                    // Hash new password
                    $hashedPassword = password_hash($password_baru, PASSWORD_DEFAULT);
                    
                    $result = updateUser($id_user, ['password' => $hashedPassword]);
                    
                    if (isset($result['error'])) {
                        $_SESSION['pesan_error'] = 'Gagal mengubah password';
                    } else {
                        $_SESSION['pesan_sukses'] = 'Password berhasil diubah!';
                    }
                } else {
                    $_SESSION['pesan_error'] = 'Password lama salah!';
                }
            }
        }
        
        echo "<script>window.location.href='index.php?page=profile';</script>";
        exit;
    }
}

// Ambil id_user dari session
$id_user = $_SESSION['id_user'] ?? null;

if (!$id_user) {
    echo "<script>alert('Session expired. Please login again.'); window.location.href='login.php';</script>";
    exit;
}

// Get data user dari Supabase
$data = getUser($id_user);
if (!$data) {
    echo "<script>alert('User not found!'); window.location.href='login.php';</script>";
    exit;
}

// Get tagihan untuk user ini (hanya jika bukan admin)
$tagihanList = [];
if ($data['role'] !== 'admin') {
    $allTagihan = getTagihan();
    // Filter tagihan by user
    if (is_array($allTagihan)) {
        foreach ($allTagihan as $tagihan) {
            if ($tagihan['id_user'] == $id_user) {
                $tagihanList[] = $tagihan;
            }
        }
    }
}
?>

<style>
  .profile-section {
    padding: 20px;
  }
  
  .profile-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 40px;
    border-radius: 15px;
    color: white;
    text-align: center;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
  }
  
  .profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: white;
    margin: 0 auto 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    font-weight: bold;
    color: #667eea;
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
  }
  
  .profile-name {
    font-size: 28px;
    font-weight: 700;
    margin: 10px 0 5px;
  }
  
  .profile-role {
    display: inline-block;
    padding: 8px 20px;
    background: rgba(255,255,255,0.2);
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
  }
  
  .profile-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
  }
  
  .profile-card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
  }
  
  .profile-card h3 {
    margin: 0 0 20px 0;
    color: #333;
    font-size: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  
  .profile-card h3 i {
    color: #667eea;
  }
  
  .info-item {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #f0f0f0;
  }
  
  .info-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
  }
  
  .info-item label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #999;
    margin-bottom: 5px;
    text-transform: uppercase;
  }
  
  .info-item p {
    margin: 0;
    font-size: 16px;
    color: #333;
    font-weight: 500;
  }
  
  .btn-action {
    width: 100%;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 10px;
  }
  
  .btn-edit-profile {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
  }
  
  .btn-edit-profile:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
  }
  
  .btn-change-password {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
  }
  
  .btn-change-password:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(240, 147, 251, 0.4);
  }
  
  /* Modal */
  .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    animation: fadeIn 0.3s;
  }
  
  .modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 0;
    border-radius: 15px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    animation: slideDown 0.3s;
  }
  
  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }
  
  @keyframes slideDown {
    from {
      transform: translateY(-50px);
      opacity: 0;
    }
    to {
      transform: translateY(0);
      opacity: 1;
    }
  }
  
  .modal-header {
    padding: 25px 30px;
    border-bottom: 2px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  
  .modal-header h3 {
    margin: 0;
    color: #333;
  }
  
  .close {
    font-size: 32px;
    font-weight: bold;
    color: #999;
    cursor: pointer;
    transition: color 0.3s;
  }
  
  .close:hover {
    color: #333;
  }
  
  .modal-body {
    padding: 30px;
  }
  
  .form-group {
    margin-bottom: 20px;
  }
  
  .form-group label {
    display: block;
    margin-bottom: 8px;
    color: #555;
    font-weight: 600;
    font-size: 14px;
  }
  
  .form-group input {
    width: 100%;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    box-sizing: border-box;
    transition: border-color 0.3s;
  }
  
  .form-group input:focus {
    outline: none;
    border-color: #667eea;
  }
  
  .modal-footer {
    padding: 20px 30px;
    border-top: 2px solid #f0f0f0;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
  }
  
  .btn-submit,
  .btn-cancel {
    padding: 12px 30px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
  }
  
  .btn-submit {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
  }
  
  .btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
  }
  
  .btn-cancel {
    background: #f0f0f0;
    color: #666;
  }
  
  .btn-cancel:hover {
    background: #e0e0e0;
  }
  
  /* Tagihan Section */
  .tagihan-section {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin-top: 25px;
  }
  
  .tagihan-section h3 {
    margin: 0 0 20px 0;
    color: #333;
    font-size: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  
  .tagihan-section h3 i {
    color: #667eea;
  }
  
  .tagihan-list {
    display: grid;
    gap: 15px;
  }
  
  .tagihan-item {
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    padding: 20px;
    transition: all 0.3s;
  }
  
  .tagihan-item:hover {
    border-color: #667eea;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
  }
  
  .tagihan-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
  }
  
  .tagihan-kamar {
    font-size: 18px;
    font-weight: bold;
    color: #333;
  }
  
  .tagihan-status {
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
  }
  
  .tagihan-status.belum_lunas {
    background: #f8d7da;
    color: #721c24;
  }
  
  .tagihan-status.pending {
    background: #fff3cd;
    color: #856404;
  }
  
  .tagihan-status.lunas {
    background: #d4edda;
    color: #155724;
  }
  
  .tagihan-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 10px;
    margin-bottom: 15px;
  }
  
  .tagihan-info-item {
    font-size: 13px;
  }
  
  .tagihan-info-item label {
    display: block;
    color: #999;
    font-weight: 600;
    margin-bottom: 3px;
  }
  
  .tagihan-info-item span {
    color: #333;
    font-weight: 500;
  }
  
  .tagihan-actions {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-top: 15px;
  }
  
  .btn-upload-bukti {
    flex: 1;
    padding: 10px 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 14px;
  }
  
  .btn-upload-bukti:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
  }
  
  .btn-upload-bukti:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
  }
  
  .btn-view-bukti {
    padding: 10px 20px;
    background: #28a745;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 14px;
  }
  
  .btn-view-bukti:hover {
    background: #218838;
    transform: translateY(-2px);
  }
  
  .no-tagihan {
    text-align: center;
    padding: 40px;
    color: #999;
  }
  
  .no-tagihan i {
    font-size: 48px;
    margin-bottom: 15px;
    display: block;
  }
  
  /* Upload Modal */
  .upload-preview {
    margin-top: 15px;
    text-align: center;
  }
  
  .upload-preview img {
    max-width: 100%;
    max-height: 300px;
    border-radius: 8px;
    border: 2px solid #e0e0e0;
  }
  
  .file-input-wrapper {
    position: relative;
    overflow: hidden;
    display: inline-block;
    width: 100%;
  }
  
  .file-input-wrapper input[type=file] {
    position: absolute;
    left: -9999px;
  }
  
  .file-input-label {
    display: block;
    padding: 12px;
    border: 2px dashed #667eea;
    border-radius: 8px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    background: #f8f9ff;
    color: #667eea;
    font-weight: 600;
  }
  
  .file-input-label:hover {
    background: #667eea;
    color: white;
  }
</style>

<section class="profile-section">
  
  <!-- Profile Header -->
  <div class="profile-header">
    <div class="profile-avatar">
      <?= strtoupper(substr($data['nama'], 0, 1)) ?>
    </div>
    <div class="profile-name"><?= htmlspecialchars($data['nama']) ?></div>
    <span class="profile-role"><?= htmlspecialchars($data['role']) ?></span>
  </div>
  
  <!-- Profile Cards -->
  <div class="profile-cards">
    
    <!-- Informasi Akun -->
    <div class="profile-card">
      <h3><i class="fa fa-user"></i> Informasi Akun</h3>
      
      <div class="info-item">
        <label>Nama Lengkap</label>
        <p><?= htmlspecialchars($data['nama']) ?></p>
      </div>
      
      <div class="info-item">
        <label>Username</label>
        <p><?= htmlspecialchars($data['username']) ?></p>
      </div>
      
      <div class="info-item">
        <label>Email</label>
        <p><?= htmlspecialchars($data['email']) ?></p>
      </div>
      
      <div class="info-item">
        <label>Nomor Telepon</label>
        <p><?= htmlspecialchars($data['nomor']) ?></p>
      </div>
      
      <button class="btn-action btn-edit-profile" onclick="openEditProfile()">
        <i class="fa fa-edit"></i> Edit Profile
      </button>
    </div>
    
    <!-- Keamanan -->
    <div class="profile-card">
      <h3><i class="fa fa-lock"></i> Keamanan</h3>
      
      <div class="info-item">
        <label>Password</label>
        <p>••••••••</p>
      </div>
      
      <div class="info-item">
        <label>Role</label>
        <p><?= htmlspecialchars($data['role']) ?></p>
      </div>
      
      <div class="info-item">
        <label>ID User</label>
        <p><?= $data['id_user'] ?></p>
      </div>
      
      <button class="btn-action btn-change-password" onclick="openChangePassword()">
        <i class="fa fa-key"></i> Ganti Password
      </button>
    </div>
    
  </div>
  
  <!-- Tagihan Section (only for non-admin) -->
  <?php if ($data['role'] !== 'admin'): ?>
  <div class="tagihan-section">
    <h3><i class="fa fa-money-bill-wave"></i> Tagihan Pembayaran Saya</h3>
    
    <?php if (empty($tagihanList)): ?>
      <div class="no-tagihan">
        <i class="fa fa-inbox"></i>
        <div>Tidak ada tagihan</div>
      </div>
    <?php else: ?>
      <div class="tagihan-list">
        <?php foreach ($tagihanList as $tagihan): 
          $statusClass = strtolower(str_replace(' ', '_', $tagihan['status_pembayaran']));
          $namaKamar = isset($tagihan['kamar']['nama']) ? $tagihan['kamar']['nama'] : 'Kamar #' . $tagihan['id_kamar'];
          $hasBukti = !empty($tagihan['bukti_pembayaran']);
          $isLunas = $statusClass === 'lunas';
        ?>
        <div class="tagihan-item">
          <div class="tagihan-header">
            <div class="tagihan-kamar"><?= htmlspecialchars($namaKamar) ?></div>
            <span class="tagihan-status <?= $statusClass ?>">
              <?= htmlspecialchars($tagihan['status_pembayaran']) ?>
            </span>
          </div>
          
          <div class="tagihan-info">
            <div class="tagihan-info-item">
              <label>Jumlah Tagihan</label>
              <span>Rp <?= number_format($tagihan['jumlah'], 0, ',', '.') ?></span>
            </div>
            <div class="tagihan-info-item">
              <label>Tanggal Tagihan</label>
              <span><?= date('d M Y', strtotime($tagihan['tgl_tagihan'])) ?></span>
            </div>
            <div class="tagihan-info-item">
              <label>Jatuh Tempo</label>
              <span><?= date('d M Y', strtotime($tagihan['tgl_tempo'])) ?></span>
            </div>
            <?php if ($tagihan['metode_pembayaran']): ?>
            <div class="tagihan-info-item">
              <label>Metode Pembayaran</label>
              <span><?= htmlspecialchars($tagihan['metode_pembayaran']) ?></span>
            </div>
            <?php endif; ?>
          </div>
          
          <div class="tagihan-actions">
            <?php if (!$isLunas): ?>
              <button class="btn-upload-bukti" onclick="openUploadBukti(<?= $tagihan['id_tagihan'] ?>, '<?= htmlspecialchars($namaKamar) ?>')">
                <i class="fa fa-upload"></i> 
                <?= $hasBukti ? 'Ganti Bukti Pembayaran' : 'Upload Bukti Pembayaran' ?>
              </button>
            <?php endif; ?>
            
            <?php if ($hasBukti): ?>
              <button class="btn-view-bukti" onclick="viewBukti('<?= htmlspecialchars($tagihan['bukti_pembayaran']) ?>')">
                <i class="fa fa-image"></i> Lihat Bukti
              </button>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>

</section>

<!-- Modal Edit Profile -->
<div id="modalEditProfile" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h3>Edit Profile</h3>
      <span class="close" onclick="closeEditProfile()">&times;</span>
    </div>
    <form method="POST" action="">
      <div class="modal-body">
        <input type="hidden" name="edit_profile" value="1">
        
        <div class="form-group">
          <label for="editNama">Nama Lengkap</label>
          <input type="text" id="editNama" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>
        </div>
        
        <div class="form-group">
          <label for="editUsername">Username</label>
          <input type="text" id="editUsername" name="username" value="<?= htmlspecialchars($data['username']) ?>" required>
        </div>
        
        <div class="form-group">
          <label for="editEmail">Email</label>
          <input type="email" id="editEmail" name="email" value="<?= htmlspecialchars($data['email']) ?>" required>
        </div>
        
        <div class="form-group">
          <label for="editNomor">Nomor Telepon</label>
          <input type="text" id="editNomor" name="nomor" value="<?= htmlspecialchars($data['nomor']) ?>" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="closeEditProfile()">Batal</button>
        <button type="submit" class="btn-submit">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Ganti Password -->
<div id="modalChangePassword" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h3>Ganti Password</h3>
      <span class="close" onclick="closeChangePassword()">&times;</span>
    </div>
    <form method="POST" action="">
      <div class="modal-body">
        <input type="hidden" name="ganti_password" value="1">
        
        <div class="form-group">
          <label for="passwordLama">Password Lama</label>
          <input type="password" id="passwordLama" name="password_lama" required>
        </div>
        
        <div class="form-group">
          <label for="passwordBaru">Password Baru (min. 6 karakter)</label>
          <input type="password" id="passwordBaru" name="password_baru" required minlength="6">
        </div>
        
        <div class="form-group">
          <label for="passwordKonfirmasi">Konfirmasi Password Baru</label>
          <input type="password" id="passwordKonfirmasi" name="password_konfirmasi" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="closeChangePassword()">Batal</button>
        <button type="submit" class="btn-submit">Ganti Password</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Upload Bukti -->
<div id="modalUploadBukti" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h3>Upload Bukti Pembayaran</h3>
      <span class="close" onclick="closeUploadBukti()">&times;</span>
    </div>
    <form id="formUploadBukti" enctype="multipart/form-data">
      <div class="modal-body">
        <input type="hidden" id="uploadTagihanId" name="id_tagihan">
        
        <div class="form-group">
          <label>Kamar</label>
          <input type="text" id="uploadKamarNama" readonly style="background: #f8f9fa;">
        </div>
        
        <div class="form-group">
          <label for="buktiBayar">File Bukti Pembayaran (JPG, PNG - Max 2MB)</label>
          <div class="file-input-wrapper">
            <input type="file" id="buktiBayar" name="bukti_pembayaran" accept="image/jpeg,image/jpg,image/png" onchange="previewImage(this)" required>
            <label for="buktiBayar" class="file-input-label">
              <i class="fa fa-cloud-upload-alt"></i> Pilih File Gambar
            </label>
          </div>
        </div>
        
        <div id="uploadPreview" class="upload-preview" style="display: none;">
          <img id="previewImg" src="" alt="Preview">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="closeUploadBukti()">Batal</button>
        <button type="submit" class="btn-submit">
          <i class="fa fa-upload"></i> Upload
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  // Modal Edit Profile
  function openEditProfile() {
    document.getElementById('modalEditProfile').style.display = 'block';
  }
  
  function closeEditProfile() {
    document.getElementById('modalEditProfile').style.display = 'none';
  }
  
  // Modal Change Password
  function openChangePassword() {
    document.getElementById('modalChangePassword').style.display = 'block';
  }
  
  function closeChangePassword() {
    document.getElementById('modalChangePassword').style.display = 'none';
  }
  
  // Close modal when clicking outside
  window.onclick = function(event) {
    const editModal = document.getElementById('modalEditProfile');
    const passwordModal = document.getElementById('modalChangePassword');
    
    if (event.target === editModal) {
      closeEditProfile();
    }
    if (event.target === passwordModal) {
      closeChangePassword();
    }
  }
  
  // Show success/error messages
  <?php if (isset($_SESSION['pesan_sukses'])): ?>
    alert('✅ <?= $_SESSION['pesan_sukses'] ?>');
    <?php unset($_SESSION['pesan_sukses']); ?>
  <?php endif; ?>
  
  <?php if (isset($_SESSION['pesan_error'])): ?>
    alert('❌ <?= $_SESSION['pesan_error'] ?>');
    <?php unset($_SESSION['pesan_error']); ?>
  <?php endif; ?>
  
  // Upload Bukti Functions
  function openUploadBukti(tagihanId, kamarNama) {
    document.getElementById('uploadTagihanId').value = tagihanId;
    document.getElementById('uploadKamarNama').value = kamarNama;
    document.getElementById('modalUploadBukti').style.display = 'block';
    // Reset form
    document.getElementById('formUploadBukti').reset();
    document.getElementById('uploadPreview').style.display = 'none';
  }
  
  function closeUploadBukti() {
    document.getElementById('modalUploadBukti').style.display = 'none';
  }
  
  function previewImage(input) {
    const preview = document.getElementById('uploadPreview');
    const previewImg = document.getElementById('previewImg');
    
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      
      reader.onload = function(e) {
        previewImg.src = e.target.result;
        preview.style.display = 'block';
      };
      
      reader.readAsDataURL(input.files[0]);
      
      // Update label text
      const label = input.parentElement.querySelector('label');
      label.innerHTML = '<i class="fa fa-check"></i> ' + input.files[0].name;
    }
  }
  
  function viewBukti(url) {
    window.open(url, '_blank');
  }
  
  // Handle upload form submission
  document.getElementById('formUploadBukti').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('.btn-submit');
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Uploading...';
    
    try {
      const response = await fetch('api/upload_bukti.php', {
        method: 'POST',
        body: formData
      });
      
      const result = await response.json();
      
      if (result.success) {
        alert('✅ ' + result.message);
        closeUploadBukti();
        // Reload page to show updated data
        window.location.reload();
      } else {
        alert('❌ ' + result.message);
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fa fa-upload"></i> Upload';
      }
    } catch (error) {
      console.error('Upload error:', error);
      alert('❌ Terjadi kesalahan saat upload. Silakan coba lagi.');
      submitBtn.disabled = false;
      submitBtn.innerHTML = '<i class="fa fa-upload"></i> Upload';
    }
  });
  
  // Close modal when clicking outside
  window.onclick = function(event) {
    const editModal = document.getElementById('modalEditProfile');
    const passwordModal = document.getElementById('modalChangePassword');
    const uploadModal = document.getElementById('modalUploadBukti');
    
    if (event.target === editModal) {
      closeEditProfile();
    }
    if (event.target === passwordModal) {
      closeChangePassword();
    }
    if (event.target === uploadModal) {
      closeUploadBukti();
    }
  }
</script>
