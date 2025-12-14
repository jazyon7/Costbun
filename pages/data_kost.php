<?php
// Cek role user yang sedang login
$currentUserRole = $_SESSION['role'] ?? 'penyewa';
$isAdmin = ($currentUserRole === 'admin');
?>

<section class="data-kos-section">

  <header class="main-header">
      <h2>Data Penyewa Kos</h2>
      <p style="color: #666; font-size: 14px;">Kelola data penghuni dan penyewa kos</p>
  </header>

  <?php if (isset($_GET['msg'])): ?>
  <div class="alert alert-success">
      <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_GET['msg']) ?>
  </div>
  <?php endif; ?>

  <?php if (isset($_GET['error'])): ?>
  <div class="alert alert-error">
      <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($_GET['error']) ?>
  </div>
  <?php endif; ?>

  <div class="content">

      <?php if ($isAdmin): ?>
      <button class="btn-add" onclick="openAddUserModal()">
          <i class="fas fa-plus"></i> Tambah User
      </button>
      <?php endif; ?>

      <div class="card">
          <table>
              <thead>
                  <tr>
                      <th>No</th>
                      <th>Nama Penyewa</th>
                      <th>No. Kamar</th>
                      <th>Kontak</th>
                      <th>Email</th>
                      <th>Status</th>
                      <th>Aksi</th>
                  </tr>
              </thead>

              <tbody>
                  <?php
                  require_once __DIR__ . '/../config/supabase_helper.php';

                  // Ambil semua user
                  $no = 1;
                  $userList = getUser();

                  // Debug: Cek tipe data
                  if (!is_array($userList)) {
                      echo "<tr><td colspan='7' style='text-align:center; color:red;'>";
                      echo "Error: Data tidak valid. ";
                      if (isset($userList['error'])) {
                          echo "Message: " . htmlspecialchars($userList['message'] ?? 'Unknown error');
                      }
                      echo "</td></tr>";
                  } else if (!empty($userList)) {
                      foreach($userList as $row) {
                          // Status berdasarkan role
                          $role = $row['role'] ?? 'penghuni kos';
                          
                          if ($role == 'admin') {
                              $status = 'Admin';
                              $statusClass = 'status-admin';
                          } else if ($role == 'penghuni kos') {
                              $status = 'Penghuni Kos';
                              $statusClass = 'status-active';
                          } else {
                              $status = 'User';
                              $statusClass = 'status-inactive';
                          }
                          
                          $noHp = $row['nomor'] ?? '-';
                          $noKamar = '-'; // Field no_kamar bisa ditambahkan nanti jika perlu
                  ?>
                  
                  <tr>
                      <td><?= $no++; ?></td>
                      <td><?= htmlspecialchars($row['nama']); ?></td>
                      <td><strong><?= htmlspecialchars($noKamar); ?></strong></td>
                      <td><?= htmlspecialchars($noHp); ?></td>
                      <td><?= htmlspecialchars($row['email']); ?></td>

                      <td><span class="<?= $statusClass; ?>"><?= $status; ?></span></td>

                      <td>
                          <button class="btn-detail" onclick="detailUser(<?= $row['id_user']; ?>)">
                              <i class="fas fa-eye"></i>
                          </button>
                          <?php if ($isAdmin): ?>
                          <button class="btn-edit" onclick="editUser(<?= $row['id_user']; ?>)">
                              <i class="fas fa-edit"></i>
                          </button>
                          <?php if ($role != 'admin'): ?>
                          <button class="btn-delete" onclick="deleteUser(<?= $row['id_user']; ?>, '<?= htmlspecialchars($row['nama']); ?>')">
                              <i class="fas fa-trash"></i>
                          </button>
                          <?php endif; ?>
                          <?php else: ?>
                          <button class="btn-edit" disabled style="opacity: 0.5; cursor: not-allowed;">
                              <i class="fas fa-edit"></i>
                          </button>
                          <button class="btn-delete" disabled style="opacity: 0.5; cursor: not-allowed;">
                              <i class="fas fa-trash"></i>
                          </button>
                          <?php endif; ?>
                      </td>
                  </tr>

                  <?php 
                      }
                  } else {
                      echo "<tr><td colspan='7' style='text-align:center;'>Tidak ada data penyewa</td></tr>";
                  }
                  ?>
              </tbody>
          </table>
      </div>

  </div>

</section>

<style>
    .alert {
        padding: 15px 20px;
        margin-bottom: 20px;
        border-radius: 8px;
        font-weight: 500;
        animation: slideDown 0.3s ease;
    }
    
    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .data-kos-section table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    
    .data-kos-section th {
        background: #3681ff;
        color: white;
        padding: 12px;
        text-align: left;
        font-weight: 600;
    }
    
    .data-kos-section td {
        padding: 12px;
        border-bottom: 1px solid #eee;
    }
    
    .data-kos-section tr:hover {
        background: #f5f5f5;
    }
    
    .status-active {
        background: #4CAF50;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    
    .status-inactive {
        background: #ffa726;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    
    .status-admin {
        background: #3681ff;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    
    .btn-detail, .btn-edit, .btn-delete {
        padding: 8px 12px;
        margin: 2px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-detail {
        background: #3681ff;
        color: white;
    }
    
    .btn-detail:hover {
        background: #2d6ad4;
    }
    
    .btn-edit {
        background: #4CAF50;
        color: white;
    }
    
    .btn-edit:hover {
        background: #45a049;
    }
    
    .btn-delete {
        background: #ea4335;
        color: white;
    }
    
    .btn-delete:hover {
        background: #c5372c;
    }
    
    .btn-add {
        background: #3681ff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-bottom: 15px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-add:hover {
        background: #2d6ad4;
    }
</style>

<!-- Modal Tambah User -->
<div id="addUserModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddUserModal()">&times;</span>
        <h2 style="margin-top: 0; color: #333;">
            <i class="fas fa-user-plus"></i> Tambah User Baru
        </h2>
        
        <form id="formAddUser" onsubmit="submitAddUser(event)">
            <div class="form-row">
                <div class="form-group">
                    <label>Nama Lengkap *</label>
                    <input type="text" name="nama" required>
                </div>
                
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>No. HP/WA *</label>
                    <input type="text" name="nomor" required>
                </div>
                
                <div class="form-group">
                    <label>Role *</label>
                    <select name="role" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="admin">Admin</option>
                        <option value="penghuni kos">Penghuni Kos</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Username *</label>
                    <input type="text" name="username" required>
                </div>
                
                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="password" required minlength="6">
                    <small style="color: #666;">Min. 6 karakter</small>
                </div>
            </div>
            
            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" rows="3"></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>KTP/KTM</label>
                    <input type="text" name="ktp_ktm">
                </div>
                
                <div class="form-group">
                    <label>Telegram ID</label>
                    <input type="text" name="telegram_id">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closeAddUserModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Simpan User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit User -->
<div id="editUserModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditUserModal()">&times;</span>
        <h2 style="margin-top: 0; color: #333;">
            <i class="fas fa-user-edit"></i> Edit User
        </h2>
        
        <form id="formEditUser" onsubmit="submitEditUser(event)">
            <input type="hidden" name="id_user" id="edit_id_user">
            
            <div class="form-row">
                <div class="form-group">
                    <label>Nama Lengkap *</label>
                    <input type="text" name="nama" id="edit_nama" required>
                </div>
                
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" id="edit_email" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>No. HP/WA *</label>
                    <input type="text" name="nomor" id="edit_nomor" required>
                </div>
                
                <div class="form-group">
                    <label>Role *</label>
                    <select name="role" id="edit_role" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="admin">Admin</option>
                        <option value="penghuni kos">Penghuni Kos</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Username *</label>
                    <input type="text" name="username" id="edit_username" required readonly style="background: #f5f5f5;">
                    <small style="color: #666;">Username tidak dapat diubah</small>
                </div>
                
                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" name="password" id="edit_password" minlength="6">
                    <small style="color: #666;">Kosongkan jika tidak ingin mengubah password</small>
                </div>
            </div>
            
            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" id="edit_alamat" rows="3"></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>KTP/KTM</label>
                    <input type="text" name="ktp_ktm" id="edit_ktp_ktm">
                </div>
                
                <div class="form-group">
                    <label>Telegram ID</label>
                    <input type="text" name="telegram_id" id="edit_telegram_id">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closeEditUserModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Update User
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5);
        animation: fadeIn 0.3s;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .modal-content {
        background-color: #fefefe;
        margin: 3% auto;
        padding: 30px;
        border-radius: 10px;
        width: 80%;
        max-width: 800px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.3);
        animation: slideDown 0.3s;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        line-height: 20px;
        cursor: pointer;
    }
    
    .close:hover,
    .close:focus {
        color: #000;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
    }
    
    .form-group label {
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
        font-size: 14px;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s;
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #3681ff;
        box-shadow: 0 0 0 3px rgba(54, 129, 255, 0.1);
    }
    
    .form-group textarea {
        resize: vertical;
    }
    
    .form-group small {
        margin-top: 5px;
        font-size: 12px;
    }
    
    .form-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
    
    .btn-cancel,
    .btn-submit {
        padding: 12px 24px;
        border: none;
        border-radius: 5px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 14px;
    }
    
    .btn-cancel {
        background: #f5f5f5;
        color: #666;
    }
    
    .btn-cancel:hover {
        background: #e0e0e0;
    }
    
    .btn-submit {
        background: #3681ff;
        color: white;
    }
    
    .btn-submit:hover {
        background: #2d6ad4;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(54, 129, 255, 0.3);
    }
    
    .btn-submit:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
    }
</style>

<script>
function openAddUserModal() {
    document.getElementById('addUserModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeAddUserModal() {
    document.getElementById('addUserModal').style.display = 'none';
    document.getElementById('formAddUser').reset();
    document.body.style.overflow = 'auto';
}

// Close modal jika klik di luar modal
window.onclick = function(event) {
    const addModal = document.getElementById('addUserModal');
    const editModal = document.getElementById('editUserModal');
    
    if (event.target == addModal) {
        closeAddUserModal();
    }
    if (event.target == editModal) {
        closeEditUserModal();
    }
}

function submitAddUser(event) {
    event.preventDefault();
    
    const form = event.target;
    const submitBtn = form.querySelector('.btn-submit');
    const formData = new FormData(form);
    
    // Validasi password
    const password = formData.get('password');
    if (password.length < 6) {
        alert('Password minimal 6 karakter!');
        return;
    }
    
    // Disable button dan tampilkan loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    
    // Convert FormData ke URLSearchParams
    const params = new URLSearchParams();
    for (let [key, value] of formData.entries()) {
        params.append(key, value);
    }
    
    // Kirim data via fetch
    fetch('api/user.php?action=create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: params.toString()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ User berhasil ditambahkan!');
            window.location.href = 'index.php?page=data_kos&msg=' + encodeURIComponent(data.message);
        } else {
            alert('❌ Gagal menambah user: ' + data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan User';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Terjadi kesalahan saat menambah user');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan User';
    });
}

function detailUser(id) {
    // Redirect ke halaman profil dengan ID
    window.location.href = 'index.php?page=profile&id=' + id;
}

function editUser(id) {
    // Ambil data user dari API
    fetch('api/user.php?action=get&id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data) {
                // Isi form edit dengan data user
                document.getElementById('edit_id_user').value = data.id_user;
                document.getElementById('edit_nama').value = data.nama || '';
                document.getElementById('edit_email').value = data.email || '';
                document.getElementById('edit_nomor').value = data.nomor || '';
                document.getElementById('edit_role').value = data.role || '';
                document.getElementById('edit_username').value = data.username || '';
                document.getElementById('edit_password').value = ''; // Kosongkan password
                document.getElementById('edit_alamat').value = data.alamat || '';
                document.getElementById('edit_ktp_ktm').value = data.ktp_ktm || '';
                document.getElementById('edit_telegram_id').value = data.telegram_id || '';
                
                // Buka modal
                openEditUserModal();
            } else {
                alert('❌ Data user tidak ditemukan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Gagal mengambil data user');
        });
}

function openEditUserModal() {
    document.getElementById('editUserModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeEditUserModal() {
    document.getElementById('editUserModal').style.display = 'none';
    document.getElementById('formEditUser').reset();
    document.body.style.overflow = 'auto';
}

function submitEditUser(event) {
    event.preventDefault();
    
    const form = event.target;
    const submitBtn = form.querySelector('.btn-submit');
    const formData = new FormData(form);
    const id = formData.get('id_user');
    
    // Disable button dan tampilkan loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    
    // Convert FormData ke URLSearchParams
    const params = new URLSearchParams();
    for (let [key, value] of formData.entries()) {
        // Skip id_user karena akan ada di URL
        if (key !== 'id_user') {
            // Skip password jika kosong
            if (key === 'password' && value === '') {
                continue;
            }
            params.append(key, value);
        }
    }
    
    // Kirim data via fetch
    fetch('api/user.php?action=update&id=' + id, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: params.toString()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ User berhasil diupdate!');
            window.location.href = 'index.php?page=data_kos&msg=' + encodeURIComponent(data.message);
        } else {
            alert('❌ Gagal update user: ' + data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Update User';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Terjadi kesalahan saat update user');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Update User';
    });
}

function deleteUser(id, nama) {
    if (confirm('Yakin ingin menghapus penyewa "' + nama + '"?\n\nData yang dihapus tidak dapat dikembalikan!')) {
        // Tampilkan loading
        const btn = event.target.closest('button');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        // Redirect ke API delete
        window.location.href = 'api/user.php?action=delete&id=' + id;
    }
}
</script>
