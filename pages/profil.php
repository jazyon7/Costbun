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
</script>
