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

      <button class="btn-add" onclick="addData()">
          <i class="fas fa-plus"></i> Tambah Penyewa
      </button>

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
                          // Status berdasarkan role dan no_kamar
                          $role = $row['role'] ?? 'penyewa';
                          $noKamar = $row['no_kamar'] ?? '-';
                          
                          if ($role == 'admin') {
                              $status = 'Admin';
                              $statusClass = 'status-admin';
                          } else if (!empty($noKamar) && $noKamar != '-') {
                              $status = 'Menempati';
                              $statusClass = 'status-active';
                          } else {
                              $status = 'Belum Kamar';
                              $statusClass = 'status-inactive';
                          }
                          
                          $noHp = $row['no_hp'] ?? '-';
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
                          <button class="btn-edit" onclick="editUser(<?= $row['id_user']; ?>)">
                              <i class="fas fa-edit"></i>
                          </button>
                          <?php if ($role != 'admin'): ?>
                          <button class="btn-delete" onclick="deleteUser(<?= $row['id_user']; ?>, '<?= htmlspecialchars($row['nama']); ?>')">
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

<script>
function addData() {
    window.location.href = 'index.php?page=tambah_data';
}

function detailUser(id) {
    // Redirect ke halaman profil dengan ID
    window.location.href = 'index.php?page=profile&id=' + id;
}

function editUser(id) {
    // Bisa redirect ke form edit atau buka modal
    alert('Fitur edit user ID: ' + id + '\nSegera hadir!');
    // window.location.href = 'index.php?page=edit_user&id=' + id;
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
