<?php
// Cek role user yang sedang login
$currentUserRole = $_SESSION['role'] ?? 'penghuni kos';
$isAdmin = ($currentUserRole === 'admin');
?>

<section class="data-kamar-section">

  <header class="main-header">
      <h2>Data Kamar</h2>
      <p style="color: #666; font-size: 14px;">Kelola data kamar dan penghuni kos</p>
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

  <div class="room-container">
      <div class="room-grid">

      <?php
      require_once __DIR__ . '/../config/supabase_helper.php';

      // Ambil data kamar dan user
      $kamarList = getKamar();
      
      // Ambil user tanpa JOIN untuk menghindari error jika id_kamar belum ada
      $userListRaw = supabase_request('GET', '/rest/v1/user?order=id_user.asc');
      
      // Buat mapping id_user ke nama untuk quick lookup
      $userMap = [];
      
      // Check if valid response
      if (is_array($userListRaw) && !isset($userListRaw['error'])) {
          foreach ($userListRaw as $user) {
              if (isset($user['id_user']) && isset($user['nama'])) {
                  $userMap[$user['id_user']] = $user['nama'];
              }
          }
      }
      
      // Check if kamarList is valid
      if (is_array($kamarList) && !isset($kamarList['error']) && !empty($kamarList)) {
          foreach($kamarList as $row) {
              // Ambil nama penghuni jika ada id_user
              $penghuniNama = null;
              $idUser = $row['id_user'] ?? null;
              
              if ($idUser && isset($userMap[$idUser])) {
                  $penghuniNama = $userMap[$idUser];
              }
              
              // Auto-update status berdasarkan ada tidaknya penghuni
              $statusClass = $penghuniNama ? 'terisi' : 'kosong';
              $statusText = $penghuniNama ? 'terisi' : 'kosong';
              
              // Update status di database jika tidak sesuai
              if ($row['status'] !== $statusText) {
                  // Silently update status
                  updateKamar($row['id_kamar'], ['status' => $statusText]);
              }
      ?>

          <div class="room-card <?= $statusClass ?>">
              <div class="room-number"><?= htmlspecialchars($row['nama']); ?></div>

              <div class="room-info">
                  <div class="info-item">
                      <i class="fas fa-bed"></i> Kasur: <?= $row['kasur'] ?>
                  </div>
                  <div class="info-item">
                      <i class="fas fa-wind"></i> <?= $row['ac'] > 0 ? 'AC' : 'Kipas' ?>: <?= $row['ac'] > 0 ? $row['ac'] : $row['kipas'] ?>
                  </div>
                  <div class="info-item">
                      <i class="fas fa-money-bill-wave"></i> Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                  </div>
              </div>

              <div class="room-status-badge <?= $statusClass ?>">
                  <?= ucfirst($statusText) ?>
              </div>

              <?php if ($penghuniNama): ?>
              <div class="room-occupant">
                  <i class="fas fa-user"></i> <?= htmlspecialchars($penghuniNama) ?>
              </div>
              <?php endif; ?>

              <?php if ($isAdmin): ?>
              <div class="room-actions">
                  <?php if (!$penghuniNama): ?>
                      <button class="btn-assign" onclick="assignPenghuni(<?= $row['id_kamar']; ?>, '<?= htmlspecialchars($row['nama']); ?>')">
                          <i class="fas fa-user-plus"></i> Assign Penghuni
                      </button>
                  <?php else: ?>
                      <button class="btn-remove" onclick="removePenghuni(<?= $row['id_kamar']; ?>, '<?= htmlspecialchars($row['nama']); ?>')">
                          <i class="fas fa-user-minus"></i> Kosongkan
                      </button>
                  <?php endif; ?>
                  <button class="btn-edit" onclick="editKamar(<?= $row['id_kamar']; ?>)">
                      <i class="fas fa-edit"></i>
                  </button>
              </div>
              <?php endif; ?>
          </div>

      <?php 
          }
      } else {
          // Tampilkan pesan jika tidak ada data atau error
          echo '<div style="grid-column: 1/-1; text-align: center; padding: 40px;">';
          
          if (isset($kamarList['error'])) {
              echo '<div style="color: #dc3545; background: #f8d7da; padding: 20px; border-radius: 8px; margin: 20px 0;">';
              echo '<i class="fas fa-exclamation-triangle"></i> ';
              echo '<strong>Error:</strong> ' . htmlspecialchars($kamarList['message'] ?? 'Terjadi kesalahan saat mengambil data kamar');
              echo '</div>';
          } else {
              echo '<div style="color: #666; font-size: 16px;">';
              echo '<i class="fas fa-info-circle" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>';
              echo 'Belum ada data kamar. ';
              if ($isAdmin) {
                  echo 'Klik tombol "+" untuk menambah kamar baru.';
              }
              echo '</div>';
          }
          
          echo '</div>';
      }
      ?>

          <?php if ($isAdmin): ?>
          <!-- Tombol Tambah -->
          <div class="room-card add-card" onclick="openAddKamarModal()">
              <div class="add-plus">+</div>
              <p class="add-text">Tambah<br>Kamar Baru</p>
          </div>
          <?php endif; ?>

      </div>
  </div>

</section>

<!-- Modal Assign Penghuni -->
<div id="assignModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAssignModal()">&times;</span>
        <h2 style="margin-top: 0; color: #333;">
            <i class="fas fa-user-plus"></i> Assign Penghuni ke Kamar <span id="assignKamarNama"></span>
        </h2>
        
        <form id="formAssignPenghuni" onsubmit="submitAssignPenghuni(event)">
            <input type="hidden" name="id_kamar" id="assign_id_kamar">
            
            <div class="form-group">
                <label>Pilih Penghuni *</label>
                <select name="id_user" id="assign_id_user" required>
                    <option value="">-- Pilih Penghuni --</option>
                    <?php
                    // Tampilkan penghuni yang belum punya kamar dan bukan admin
                    if (is_array($userListRaw) && !isset($userListRaw['error'])) {
                        foreach ($userListRaw as $user) {
                            if (isset($user['role']) && strtolower($user['role']) !== 'admin') {
                                echo '<option value="' . $user['id_user'] . '">' . htmlspecialchars($user['nama']) . ' (' . htmlspecialchars($user['email']) . ')</option>';
                            }
                        }
                    }
                    ?>
                </select>
                <small style="color: #666; display: block; margin-top: 5px;">
                    Pilih penghuni kos yang akan menempati kamar ini
                </small>
            </div>
            
            <div class="form-group">
                <label>Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" id="assign_tanggal_mulai" value="<?= date('Y-m-d') ?>">
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closeAssignModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Assign Penghuni
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Kamar -->
<div id="addKamarModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddKamarModal()">&times;</span>
        <h2 style="margin-top: 0; color: #333;">
            <i class="fas fa-door-open"></i> Tambah Kamar Baru
        </h2>
        
        <form id="formAddKamar" onsubmit="submitAddKamar(event)">
            <div class="form-row">
                <div class="form-group">
                    <label>Nama/No. Kamar *</label>
                    <input type="text" name="nama" required placeholder="Contoh: A-01">
                </div>
                
                <div class="form-group">
                    <label>Harga Sewa *</label>
                    <input type="number" name="harga" required placeholder="500000">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Jumlah Kasur</label>
                    <input type="number" name="kasur" value="1" min="0">
                </div>
                
                <div class="form-group">
                    <label>Jumlah Kipas</label>
                    <input type="number" name="kipas" value="1" min="0">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Jumlah Lemari</label>
                    <input type="number" name="lemari" value="1" min="0">
                </div>
                
                <div class="form-group">
                    <label>Jumlah AC</label>
                    <input type="number" name="ac" value="0" min="0">
                </div>
            </div>
            
            <div class="form-group">
                <label>Jumlah Keranjang Sampah</label>
                <input type="number" name="keranjang_sampah" value="1" min="0">
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closeAddKamarModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Simpan Kamar
                </button>
            </div>
        </form>
    </div>
</div>

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
    
    .room-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: all 0.3s;
        position: relative;
    }
    
    .room-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    
    .room-card.terisi {
        border-left: 4px solid #f44336;
    }
    
    .room-card.kosong {
        border-left: 4px solid #4CAF50;
    }
    
    .room-number {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
    }
    
    .room-info {
        margin: 15px 0;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 8px 0;
        color: #666;
        font-size: 14px;
    }
    
    .info-item i {
        color: #3681ff;
        width: 20px;
    }
    
    .room-status-badge {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        margin: 10px 0;
    }
    
    .room-status-badge.terisi {
        background: #ffebee;
        color: #c62828;
    }
    
    .room-status-badge.kosong {
        background: #e8f5e9;
        color: #2e7d32;
    }
    
    .room-occupant {
        background: #f5f5f5;
        padding: 10px;
        border-radius: 5px;
        margin: 10px 0;
        display: flex;
        align-items: center;
        gap: 8px;
        color: #333;
        font-weight: 500;
    }
    
    .room-actions {
        display: flex;
        gap: 5px;
        margin-top: 15px;
    }
    
    .btn-assign, .btn-remove, .btn-edit {
        flex: 1;
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }
    
    .btn-assign {
        background: #4CAF50;
        color: white;
    }
    
    .btn-assign:hover {
        background: #45a049;
    }
    
    .btn-remove {
        background: #f44336;
        color: white;
    }
    
    .btn-remove:hover {
        background: #da190b;
    }
    
    .btn-edit {
        background: #3681ff;
        color: white;
        flex: 0.3;
    }
    
    .btn-edit:hover {
        background: #2d6ad4;
    }
    
    .add-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 2px dashed #ccc;
        background: #f9f9f9;
    }
    
    .add-card:hover {
        border-color: #3681ff;
        background: #f0f7ff;
    }
    
    .add-plus {
        font-size: 48px;
        color: #3681ff;
        font-weight: 300;
    }
    
    .add-text {
        color: #666;
        margin-top: 10px;
        text-align: center;
        line-height: 1.5;
    }
    
    /* Modal Styles */
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
    
    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 30px;
        border-radius: 10px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.3);
        animation: slideDown 0.3s;
    }
    
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        line-height: 20px;
        cursor: pointer;
    }
    
    .close:hover {
        color: #000;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 15px;
    }
    
    .form-group label {
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
        font-size: 14px;
    }
    
    .form-group input,
    .form-group select {
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s;
    }
    
    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #3681ff;
        box-shadow: 0 0 0 3px rgba(54, 129, 255, 0.1);
    }
    
    .form-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
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
    }
    
    .btn-submit:disabled {
        background: #ccc;
        cursor: not-allowed;
    }
</style>

<script>
// Assign Penghuni Modal
function assignPenghuni(idKamar, namaKamar) {
    document.getElementById('assign_id_kamar').value = idKamar;
    document.getElementById('assignKamarNama').textContent = namaKamar;
    document.getElementById('assignModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeAssignModal() {
    document.getElementById('assignModal').style.display = 'none';
    document.getElementById('formAssignPenghuni').reset();
    document.body.style.overflow = 'auto';
}

function submitAssignPenghuni(event) {
    event.preventDefault();
    
    const form = event.target;
    const submitBtn = form.querySelector('.btn-submit');
    const formData = new FormData(form);
    
    // Debugging: Log form data
    console.log('=== Submit Assign Penghuni ===');
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }
    
    const id_kamar = formData.get('id_kamar');
    const id_user = formData.get('id_user');
    
    console.log('id_kamar:', id_kamar);
    console.log('id_user:', id_user);
    
    if (!id_user || id_user === '') {
        alert('❌ Silakan pilih penghuni terlebih dahulu!');
        return;
    }
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    
    const params = new URLSearchParams();
    for (let [key, value] of formData.entries()) {
        params.append(key, value);
    }
    
    console.log('Body yang dikirim:', params.toString());
    
    fetch('api/kamar.php?action=assign_penghuni', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: params.toString()
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.text();
    })
    .then(text => {
        console.log('Response text:', text);
        try {
            const data = JSON.parse(text);
            console.log('Response parsed:', data);
            
            if (data.success) {
                alert('✅ Penghuni berhasil di-assign ke kamar!');
                window.location.href = 'index.php?page=data_kamar&msg=' + encodeURIComponent(data.message);
            } else {
                alert('❌ Gagal assign penghuni: ' + data.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Assign Penghuni';
            }
        } catch (e) {
            console.error('JSON parse error:', e);
            console.error('Response was:', text);
            alert('❌ Terjadi kesalahan: Response tidak valid');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Assign Penghuni';
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert('❌ Terjadi kesalahan: ' + error.message);
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Assign Penghuni';
    });
}

// Remove Penghuni
function removePenghuni(idKamar, namaKamar) {
    if (confirm('Yakin ingin mengosongkan kamar ' + namaKamar + '?\n\nPenghuni akan dipindahkan dari kamar ini.')) {
        window.location.href = 'api/kamar.php?action=remove_penghuni&id=' + idKamar;
    }
}

// Edit Kamar
function editKamar(idKamar) {
    alert('Fitur edit kamar ID: ' + idKamar + '\nSegera hadir!');
}

// Add Kamar Modal
function openAddKamarModal() {
    document.getElementById('addKamarModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeAddKamarModal() {
    document.getElementById('addKamarModal').style.display = 'none';
    document.getElementById('formAddKamar').reset();
    document.body.style.overflow = 'auto';
}

function submitAddKamar(event) {
    event.preventDefault();
    
    const form = event.target;
    const submitBtn = form.querySelector('.btn-submit');
    const formData = new FormData(form);
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    
    const params = new URLSearchParams();
    for (let [key, value] of formData.entries()) {
        params.append(key, value);
    }
    params.append('status', 'kosong');
    
    fetch('api/kamar.php?action=create_kamar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: params.toString()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Kamar berhasil ditambahkan!');
            window.location.href = 'index.php?page=data_kamar&msg=' + encodeURIComponent(data.message);
        } else {
            alert('❌ Gagal tambah kamar: ' + data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Kamar';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Terjadi kesalahan');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Kamar';
    });
}

// Close modal when clicking outside
window.onclick = function(event) {
    const assignModal = document.getElementById('assignModal');
    const addModal = document.getElementById('addKamarModal');
    
    if (event.target == assignModal) {
        closeAssignModal();
    }
    if (event.target == addModal) {
        closeAddKamarModal();
    }
}
</script>
