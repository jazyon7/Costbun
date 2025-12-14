<section class="data-kos-section">

  <header class="main-header">
      <h2>Data Kost</h2>
  </header>

  <div class="content">

      <button class="btn-add" onclick="addData()">+ Tambah Penyewa</button>

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

                  // Ambil penyewa dari tabel user
                  $no = 1;
                  $userList = getUser();

                  if (!empty($userList)) {
                      foreach($userList as $row) {
                          // Status contoh (aktif jika punya nomor, tidak aktif jika kosong)
                          $status = !empty($row['nomor']) ? 'Aktif' : 'Tidak Aktif';
                          $statusClass = ($status == 'Aktif') ? 'status-active' : 'status-inactive';

                          // Sementara kolom kamar belum ada, jadi tampilkan '-'
                          $kamar = "-";
                  ?>
                  
                  <tr>
                      <td><?= $no++; ?></td>
                      <td><?= htmlspecialchars($row['nama']); ?></td>
                      <td><?= $kamar; ?></td>
                      <td><?= htmlspecialchars($row['nomor']); ?></td>
                      <td><?= htmlspecialchars($row['email']); ?></td>

                      <td><span class="<?= $statusClass; ?>"><?= $status; ?></span></td>

                      <td>
                          <button class="btn-detail" onclick="detailUser(<?= $row['id_user']; ?>)">Detail Data</button>
                          <button class="btn-edit" onclick="editUser(<?= $row['id_user']; ?>)">Edit</button>
                          <button class="btn-delete" onclick="deleteUser(<?= $row['id_user']; ?>)">Hapus</button>
                      </td>
                  </tr>

                  <?php 
                      }
                  } 
                  ?>
              </tbody>
          </table>
      </div>

  </div>

</section>

<script>
function addData() {
    // Redirect ke form tambah atau buka modal
    alert('Fitur tambah penyewa - implementasi form sesuai kebutuhan');
    // window.location.href = 'form_tambah_penyewa.php';
}

function detailUser(id) {
    alert('Detail user ID: ' + id);
    // window.location.href = 'detail_user.php?id=' + id;
}

function editUser(id) {
    alert('Edit user ID: ' + id);
    // window.location.href = 'edit_user.php?id=' + id;
}

function deleteUser(id) {
    if (confirm('Yakin ingin menghapus penyewa ini?')) {
        window.location.href = 'api/user.php?action=delete&id=' + id;
    }
}
</script>
