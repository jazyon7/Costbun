<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan</title>
  <link rel="stylesheet" href="style.css">
  <script src="navigasi.js"></script>
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet">
  <style>
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
    
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    
    .modal-content {
      background-color: #fefefe;
      margin: 5% auto;
      padding: 30px;
      border-radius: 12px;
      width: 90%;
      max-width: 600px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.2);
      animation: slideDown 0.3s;
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
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
      padding-bottom: 15px;
      border-bottom: 2px solid #e0e0e0;
    }
    
    .modal-header h2 {
      margin: 0;
      color: #333;
      font-size: 24px;
    }
    
    .close {
      font-size: 32px;
      font-weight: bold;
      color: #999;
      cursor: pointer;
      transition: color 0.3s;
      line-height: 1;
    }
    
    .close:hover {
      color: #333;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: #555;
      font-weight: 600;
    }
    
    .form-group input[type="text"],
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 12px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 14px;
      transition: border-color 0.3s;
      box-sizing: border-box;
    }
    
    .form-group input[type="text"]:focus,
    .form-group textarea:focus,
    .form-group select:focus {
      outline: none;
      border-color: #667eea;
    }
    
    .form-group textarea {
      resize: vertical;
      min-height: 120px;
      font-family: inherit;
    }
    
    .modal-footer {
      display: flex;
      gap: 10px;
      justify-content: flex-end;
      margin-top: 25px;
      padding-top: 20px;
      border-top: 2px solid #e0e0e0;
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
    
    .btn-tambah-laporan {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s;
      margin-bottom: 20px;
    }
    
    .btn-tambah-laporan:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }
    
    .page-header-with-button {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
  </style>
</head>

      <!-- ✅ Diubah dari div menjadi section -->
      <section class="content">

        <!-- ✅ Header dengan tombol tambah laporan (hanya untuk penghuni) -->
        <section class="page-header-with-button">
          <div></div>
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'penghuni kos'): ?>
          <button class="btn-tambah-laporan" onclick="openModalLaporan()">
            <i class="fa fa-plus"></i> Tambah Laporan
          </button>
          <?php endif; ?>
        </section>

        <!-- ✅ Diubah menjadi section -->
        <section class="filter-box">
          <div class="filter-group">
            <label>Status</label>
            <select id="filterStatus">
              <option value="">Semua</option>
              <option value="pending">Pending</option>
              <option value="diproses">Diproses</option>
              <option value="selesai">Selesai</option>
            </select>
          </div>

          <button class="btn-filter" onclick="filterLaporan()">Filter</button>
        </section>

        <!-- ✅ Diubah menjadi section -->
        <section class="table-container">
          <table>
            <thead>
              <tr>
                <th>No</th>
                <th>Pelapor</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Gambar</th>
                <th>Tanggal Lapor</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>

            <tbody>
              <?php
              require_once __DIR__ . '/../config/supabase_helper.php';

              // Filter berdasarkan role
              $laporanList = getLaporan();
              $userRole = $_SESSION['role'] ?? 'admin';
              $userId = $_SESSION['id_user'] ?? 0;
              
              // Jika penghuni, hanya tampilkan laporannya sendiri
              if ($userRole === 'penghuni kos') {
                  $laporanList = array_filter($laporanList, function($laporan) use ($userId) {
                      return $laporan['id_user'] == $userId;
                  });
              }
              
              $no = 1;

              if (!empty($laporanList)) {
                  foreach($laporanList as $row) {
                      $status = strtolower($row['status_laporan']);
                      $statusClass = $status == 'selesai' ? 'success' : 'pending';
                      $pelapor = isset($row['user']['nama']) ? $row['user']['nama'] : 'Unknown';
                      $tanggal = date('d M Y H:i', strtotime($row['created_at']));
              ?>
              <tr data-status="<?= strtolower($row['status_laporan']); ?>">
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($pelapor); ?></td>
                <td><?= htmlspecialchars($row['judul_laporan']); ?></td>
                <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= htmlspecialchars($row['deskripsi']); ?></td>
                <td style="text-align: center;">
                  <?php if (!empty($row['gambar_url'])): ?>
                    <a href="<?= htmlspecialchars($row['gambar_url']) ?>" target="_blank" title="Lihat gambar">
                      <i class="fa fa-image" style="font-size: 20px; color: #3681ff;"></i>
                    </a>
                  <?php else: ?>
                    <span style="color: #ccc;">-</span>
                  <?php endif; ?>
                </td>
                <td><?= $tanggal; ?></td>
                <td><span class="status <?= $statusClass; ?>"><?= htmlspecialchars($row['status_laporan']); ?></span></td>
                <td>
                  <button class="btn-detail" onclick="detailLaporan(<?= $row['id_laporan']; ?>)">Detail</button>
                  
                  <?php if ($userRole === 'admin'): ?>
                    <button class="btn-edit" onclick="updateStatus(<?= $row['id_laporan']; ?>, '<?= $row['status_laporan']; ?>')">Ubah Status</button>
                  <?php endif; ?>
                  
                  <?php if ($userRole === 'penghuni kos' && $row['id_user'] == $userId): ?>
                    <button class="btn-edit" onclick="editLaporan(<?= $row['id_laporan']; ?>)">Edit</button>
                    <button class="btn-delete" onclick="deleteLaporan(<?= $row['id_laporan']; ?>)">Hapus</button>
                  <?php endif; ?>
                </td>
              </tr>
              <?php 
                  }
              } else {
              ?>
              <tr>
                <td colspan="8" style="text-align: center;">Tidak ada data laporan</td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </section>

      </section>

      <!-- Modal Tambah Laporan -->
      <div id="modalLaporan" class="modal">
        <div class="modal-content">
          <div class="modal-header">
            <h2><i class="fa fa-file-alt"></i> Buat Laporan Baru</h2>
            <span class="close" onclick="closeModalLaporan()">&times;</span>
          </div>
          
          <form id="formLaporan" onsubmit="submitLaporan(event)">
            <div class="form-group">
              <label for="judul_laporan">
                <i class="fa fa-heading"></i> Judul Laporan *
              </label>
              <input 
                type="text" 
                id="judul_laporan" 
                name="judul_laporan" 
                placeholder="Contoh: AC Kamar Mati"
                required
              >
            </div>
            
            <div class="form-group">
              <label for="deskripsi">
                <i class="fa fa-align-left"></i> Deskripsi Masalah *
              </label>
              <textarea 
                id="deskripsi" 
                name="deskripsi" 
                placeholder="Jelaskan masalah yang Anda alami secara detail..."
                required
              ></textarea>
            </div>
            
            <div class="form-group">
              <label for="gambar_laporan">
                <i class="fa fa-image"></i> Upload Gambar (Opsional)
              </label>
              <input 
                type="file" 
                id="gambar_laporan" 
                name="gambar_laporan" 
                accept="image/*"
                style="padding: 8px;"
              >
              <small style="color: #666; display: block; margin-top: 5px;">
                Format: JPG, PNG, GIF. Maksimal 5MB
              </small>
              <div id="preview_container" style="margin-top: 10px; display: none;">
                <img id="image_preview" src="" style="max-width: 100%; max-height: 200px; border-radius: 8px; border: 2px solid #e0e0e0;">
              </div>
            </div>
            
            <div class="modal-footer">
              <button type="button" class="btn-cancel" onclick="closeModalLaporan()">
                <i class="fa fa-times"></i> Batal
              </button>
              <button type="submit" class="btn-submit">
                <i class="fa fa-paper-plane"></i> Kirim Laporan
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Modal Edit Laporan -->
      <div id="modalEditLaporan" class="modal">
        <div class="modal-content">
          <div class="modal-header">
            <h2><i class="fa fa-edit"></i> Edit Laporan</h2>
            <span class="close" onclick="closeModalEditLaporan()">&times;</span>
          </div>
          
          <form id="formEditLaporan" onsubmit="submitEditLaporan(event)">
            <input type="hidden" id="edit_id_laporan" name="edit_id_laporan">
            <input type="hidden" id="edit_gambar_lama" name="edit_gambar_lama">
            
            <div class="form-group">
              <label for="edit_judul_laporan">
                <i class="fa fa-heading"></i> Judul Laporan *
              </label>
              <input 
                type="text" 
                id="edit_judul_laporan" 
                name="edit_judul_laporan" 
                placeholder="Contoh: AC Kamar Mati"
                required
              >
            </div>
            
            <div class="form-group">
              <label for="edit_deskripsi">
                <i class="fa fa-align-left"></i> Deskripsi Masalah *
              </label>
              <textarea 
                id="edit_deskripsi" 
                name="edit_deskripsi" 
                placeholder="Jelaskan masalah yang Anda alami secara detail..."
                required
              ></textarea>
            </div>
            
            <div class="form-group">
              <label for="edit_gambar_laporan">
                <i class="fa fa-image"></i> Ganti Gambar (Opsional)
              </label>
              <input 
                type="file" 
                id="edit_gambar_laporan" 
                name="edit_gambar_laporan" 
                accept="image/*"
                style="padding: 8px;"
              >
              <small style="color: #666; display: block; margin-top: 5px;">
                Format: JPG, PNG, GIF. Maksimal 5MB. Kosongkan jika tidak ingin mengganti.
              </small>
              <div id="edit_current_image" style="margin-top: 10px; display: none;">
                <div style="font-size: 12px; color: #666; margin-bottom: 5px;">Gambar Saat Ini:</div>
                <img id="edit_current_image_preview" src="" style="max-width: 200px; max-height: 150px; border-radius: 8px; border: 2px solid #e0e0e0;">
              </div>
              <div id="edit_preview_container" style="margin-top: 10px; display: none;">
                <div style="font-size: 12px; color: #666; margin-bottom: 5px;">Preview Gambar Baru:</div>
                <img id="edit_image_preview" src="" style="max-width: 100%; max-height: 200px; border-radius: 8px; border: 2px solid #e0e0e0;">
              </div>
            </div>
            
            <div class="modal-footer">
              <button type="button" class="btn-cancel" onclick="closeModalEditLaporan()">
                <i class="fa fa-times"></i> Batal
              </button>
              <button type="submit" class="btn-submit">
                <i class="fa fa-save"></i> Simpan Perubahan
              </button>
            </div>
          </form>
        </div>
      </div>

      <script>
      // Open Modal
      function openModalLaporan() {
          document.getElementById('modalLaporan').style.display = 'block';
      }
      
      // Close Modal
      function closeModalLaporan() {
          document.getElementById('modalLaporan').style.display = 'none';
          document.getElementById('formLaporan').reset();
      }
      
      // Open Modal Edit
      function openModalEditLaporan() {
          document.getElementById('modalEditLaporan').style.display = 'block';
      }
      
      // Close Modal Edit
      function closeModalEditLaporan() {
          document.getElementById('modalEditLaporan').style.display = 'none';
          document.getElementById('formEditLaporan').reset();
          document.getElementById('edit_preview_container').style.display = 'none';
          document.getElementById('edit_current_image').style.display = 'none';
      }
      
      // Close modal when clicking outside
      window.onclick = function(event) {
          const modal = document.getElementById('modalLaporan');
          const modalEdit = document.getElementById('modalEditLaporan');
          
          if (event.target == modal) {
              closeModalLaporan();
          }
          if (event.target == modalEdit) {
              closeModalEditLaporan();
          }
      }
      
      // Preview image
      document.getElementById('gambar_laporan')?.addEventListener('change', function(e) {
          const file = e.target.files[0];
          if (file) {
              // Check file size (5MB)
              if (file.size > 5 * 1024 * 1024) {
                  alert('❌ Ukuran file terlalu besar! Maksimal 5MB');
                  e.target.value = '';
                  return;
              }
              
              // Preview image
              const reader = new FileReader();
              reader.onload = function(event) {
                  document.getElementById('image_preview').src = event.target.result;
                  document.getElementById('preview_container').style.display = 'block';
              };
              reader.readAsDataURL(file);
          } else {
              document.getElementById('preview_container').style.display = 'none';
          }
      });
      
      // Preview image for edit
      document.getElementById('edit_gambar_laporan')?.addEventListener('change', function(e) {
          const file = e.target.files[0];
          if (file) {
              // Check file size (5MB)
              if (file.size > 5 * 1024 * 1024) {
                  alert('❌ Ukuran file terlalu besar! Maksimal 5MB');
                  e.target.value = '';
                  return;
              }
              
              // Preview image
              const reader = new FileReader();
              reader.onload = function(event) {
                  document.getElementById('edit_image_preview').src = event.target.result;
                  document.getElementById('edit_preview_container').style.display = 'block';
              };
              reader.readAsDataURL(file);
          } else {
              document.getElementById('edit_preview_container').style.display = 'none';
          }
      });
      
      // Submit Laporan
      async function submitLaporan(event) {
          event.preventDefault();
          
          const judul = document.getElementById('judul_laporan').value;
          const deskripsi = document.getElementById('deskripsi').value;
          const gambarFile = document.getElementById('gambar_laporan').files[0];
          
          if (!judul || !deskripsi) {
              alert('Mohon lengkapi semua field yang wajib diisi');
              return;
          }
          
          console.log('Submitting laporan:', { judul, deskripsi, hasImage: !!gambarFile });
          
          // Use FormData for file upload
          const formData = new FormData();
          formData.append('judul_laporan', judul);
          formData.append('deskripsi', deskripsi);
          formData.append('status_laporan', 'pending');
          formData.append('source', 'web');
          formData.append('id_user', <?= $_SESSION['id_user'] ?? 0 ?>);
          
          if (gambarFile) {
              formData.append('gambar', gambarFile);
          }
          
          try {
              const response = await fetch('api/laporan.php?action=create', {
                  method: 'POST',
                  body: formData // Don't set Content-Type, browser will set it with boundary
              });
              
              const result = await response.json();
              console.log('Response:', result);
              
              if (result.success) {
                  alert('✅ Laporan berhasil dikirim!\n\nLaporan Anda akan segera ditindaklanjuti oleh admin.');
                  closeModalLaporan();
                  location.reload();
              } else {
                  alert('❌ Gagal mengirim laporan: ' + (result.message || 'Unknown error'));
              }
          } catch (error) {
              console.error('Error:', error);
              alert('❌ Terjadi kesalahan saat mengirim laporan');
          }
      }
      
      function filterLaporan() {
          const status = document.getElementById('filterStatus').value.toLowerCase();
          const rows = document.querySelectorAll('tbody tr[data-status]');
          
          rows.forEach(row => {
              if (status === '' || row.getAttribute('data-status') === status) {
                  row.style.display = '';
              } else {
                  row.style.display = 'none';
              }
          });
      }

      function detailLaporan(id) {
          // Fetch detail laporan
          fetch('api/laporan.php?action=get&id=' + id)
              .then(res => res.json())
              .then(data => {
                  if (data) {
                      const pelapor = data.user?.nama || 'Unknown';
                      const tanggal = new Date(data.created_at).toLocaleString('id-ID');
                      
                      // Create modal for detail with image support
                      const modalHtml = `
                          <div id="modalDetail" style="display: block; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); overflow: auto;">
                              <div style="background-color: #fefefe; margin: 5% auto; padding: 30px; border-radius: 12px; width: 90%; max-width: 600px; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
                                  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #e0e0e0;">
                                      <h2 style="margin: 0; color: #333; font-size: 24px;">
                                          <i class="fa fa-file-alt"></i> Detail Laporan
                                      </h2>
                                      <span onclick="document.getElementById('modalDetail').remove()" style="font-size: 32px; font-weight: bold; color: #999; cursor: pointer; line-height: 1;">&times;</span>
                                  </div>
                                  
                                  <div style="margin-bottom: 15px;">
                                      <div style="font-size: 12px; color: #666; margin-bottom: 5px; font-weight: 600;">PELAPOR</div>
                                      <div style="font-size: 16px; color: #333;">👤 ${pelapor}</div>
                                  </div>
                                  
                                  <div style="margin-bottom: 15px;">
                                      <div style="font-size: 12px; color: #666; margin-bottom: 5px; font-weight: 600;">TANGGAL</div>
                                      <div style="font-size: 16px; color: #333;">📅 ${tanggal}</div>
                                  </div>
                                  
                                  <div style="margin-bottom: 15px;">
                                      <div style="font-size: 12px; color: #666; margin-bottom: 5px; font-weight: 600;">JUDUL</div>
                                      <div style="font-size: 16px; color: #333; font-weight: bold;">📝 ${data.judul_laporan}</div>
                                  </div>
                                  
                                  <div style="margin-bottom: 15px;">
                                      <div style="font-size: 12px; color: #666; margin-bottom: 5px; font-weight: 600;">DESKRIPSI</div>
                                      <div style="font-size: 14px; color: #555; line-height: 1.6; padding: 12px; background: #f8f9fa; border-radius: 8px;">${data.deskripsi}</div>
                                  </div>
                                  
                                  ${data.gambar_url ? `
                                  <div style="margin-bottom: 15px;">
                                      <div style="font-size: 12px; color: #666; margin-bottom: 5px; font-weight: 600;">GAMBAR</div>
                                      <a href="${data.gambar_url}" target="_blank">
                                          <img src="${data.gambar_url}" style="max-width: 100%; border-radius: 8px; border: 2px solid #e0e0e0; cursor: pointer;" alt="Gambar Laporan">
                                      </a>
                                      <div style="font-size: 11px; color: #999; margin-top: 5px;">Klik gambar untuk melihat ukuran penuh</div>
                                  </div>
                                  ` : ''}
                                  
                                  <div style="margin-bottom: 0;">
                                      <div style="font-size: 12px; color: #666; margin-bottom: 5px; font-weight: 600;">STATUS</div>
                                      <span style="display: inline-block; padding: 6px 16px; border-radius: 20px; font-size: 14px; font-weight: bold; 
                                          ${data.status_laporan === 'selesai' ? 'background: #d4edda; color: #155724;' : 
                                            data.status_laporan === 'diproses' ? 'background: #fff3cd; color: #856404;' : 
                                            'background: #f8d7da; color: #721c24;'}">
                                          ${data.status_laporan.toUpperCase()}
                                      </span>
                                  </div>
                              </div>
                          </div>
                      `;
                      
                      // Append modal to body
                      document.body.insertAdjacentHTML('beforeend', modalHtml);
                      
                      // Close on outside click
                      document.getElementById('modalDetail').addEventListener('click', function(e) {
                          if (e.target.id === 'modalDetail') {
                              this.remove();
                          }
                      });
                  }
              })
              .catch(err => {
                  console.error('Error:', err);
                  alert('Gagal memuat detail laporan');
              });
      }

      function updateStatus(id, currentStatus) {
          const current = currentStatus.toLowerCase();
          let newStatus;
          
          // Cycle: pending -> diproses -> selesai -> pending
          if (current === 'pending') {
              newStatus = 'diproses';
          } else if (current === 'diproses') {
              newStatus = 'selesai';
          } else {
              newStatus = 'pending';
          }
          
          if (confirm('Ubah status laporan menjadi "' + newStatus.toUpperCase() + '"?')) {
              window.location.href = 'api/laporan.php?action=update_status&id=' + id + '&status=' + newStatus;
          }
      }
      
      // Edit Laporan (Penghuni only)
      async function editLaporan(id) {
          try {
              const response = await fetch('api/laporan.php?action=get&id=' + id);
              const data = await response.json();
              
              if (data) {
                  // Populate form
                  document.getElementById('edit_id_laporan').value = data.id_laporan;
                  document.getElementById('edit_judul_laporan').value = data.judul_laporan;
                  document.getElementById('edit_deskripsi').value = data.deskripsi;
                  document.getElementById('edit_gambar_lama').value = data.gambar_url || '';
                  
                  // Show current image if exists
                  if (data.gambar_url) {
                      document.getElementById('edit_current_image_preview').src = data.gambar_url;
                      document.getElementById('edit_current_image').style.display = 'block';
                  } else {
                      document.getElementById('edit_current_image').style.display = 'none';
                  }
                  
                  // Open modal
                  openModalEditLaporan();
              }
          } catch (error) {
              console.error('Error:', error);
              alert('❌ Gagal memuat data laporan');
          }
      }
      
      // Submit Edit Laporan
      async function submitEditLaporan(event) {
          event.preventDefault();
          
          const id = document.getElementById('edit_id_laporan').value;
          const judul = document.getElementById('edit_judul_laporan').value;
          const deskripsi = document.getElementById('edit_deskripsi').value;
          const gambarFile = document.getElementById('edit_gambar_laporan').files[0];
          const gambarLama = document.getElementById('edit_gambar_lama').value;
          
          if (!judul || !deskripsi) {
              alert('❌ Mohon lengkapi semua field yang wajib diisi');
              return;
          }
          
          console.log('Updating laporan:', { id, judul, deskripsi, hasNewImage: !!gambarFile });
          
          // Use FormData for file upload
          const formData = new FormData();
          formData.append('judul_laporan', judul);
          formData.append('deskripsi', deskripsi);
          formData.append('gambar_lama', gambarLama);
          
          if (gambarFile) {
              formData.append('gambar', gambarFile);
          }
          
          try {
              const response = await fetch('api/laporan.php?action=update&id=' + id, {
                  method: 'POST',
                  body: formData
              });
              
              const result = await response.json();
              console.log('Response:', result);
              
              if (result.success) {
                  alert('✅ Laporan berhasil diperbarui!');
                  closeModalEditLaporan();
                  location.reload();
              } else {
                  alert('❌ Gagal memperbarui laporan: ' + (result.message || 'Unknown error'));
              }
          } catch (error) {
              console.error('Error:', error);
              alert('❌ Terjadi kesalahan saat memperbarui laporan');
          }
      }
      
      // Delete Laporan (Penghuni only)
      async function deleteLaporan(id) {
          if (!confirm('⚠️ Apakah Anda yakin ingin menghapus laporan ini?\\n\\nData yang dihapus tidak dapat dikembalikan!')) {
              return;
          }
          
          try {
              const response = await fetch('api/laporan.php?action=delete&id=' + id, {
                  method: 'DELETE'
              });
              
              const result = await response.json();
              
              if (result.success) {
                  alert('✅ Laporan berhasil dihapus!');
                  location.reload();
              } else {
                  alert('❌ Gagal menghapus laporan: ' + (result.message || 'Unknown error'));
              }
          } catch (error) {
              console.error('Error:', error);
              alert('❌ Terjadi kesalahan saat menghapus laporan');
          }
      }
      </script>