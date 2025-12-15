<?php
require_once __DIR__ . '/../config/supabase_helper.php';

// Check if user is admin
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

if (!$isAdmin) {
    echo '<div style="padding: 40px; text-align: center;">';
    echo '<i class="fa fa-lock" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>';
    echo '<h2 style="color: #666;">Akses Ditolak</h2>';
    echo '<p style="color: #999;">Hanya admin yang dapat mengakses halaman ini.</p>';
    echo '</div>';
    return;
}

// Get tagihan list with bukti pembayaran
$tagihanList = getTagihan();
?>

<style>
  .bukti-container {
    padding: 20px;
  }
  
  .filter-section {
    background: white;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    display: flex;
    gap: 15px;
    align-items: end;
    flex-wrap: wrap;
  }
  
  .form-group {
    flex: 1;
    min-width: 200px;
  }
  
  .form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #555;
    font-size: 14px;
  }
  
  .form-group select,
  .form-group input {
    width: 100%;
    padding: 10px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    box-sizing: border-box;
  }
  
  .btn-filter {
    padding: 10px 24px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s;
  }
  
  .btn-filter:hover {
    transform: translateY(-2px);
  }
  
  .table-container {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    overflow-x: auto;
  }
  
  table {
    width: 100%;
    border-collapse: collapse;
  }
  
  table thead {
    background: #f8f9fa;
  }
  
  table th {
    padding: 12px;
    text-align: left;
    font-weight: 600;
    color: #333;
    border-bottom: 2px solid #e0e0e0;
  }
  
  table td {
    padding: 12px;
    border-bottom: 1px solid #f0f0f0;
    color: #555;
  }
  
  table tbody tr:hover {
    background: #f8f9fa;
  }
  
  .status {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
  }
  
  .status.lunas {
    background: #d4edda;
    color: #155724;
  }
  
  .status.belum {
    background: #f8d7da;
    color: #721c24;
  }
  
  .status.pending {
    background: #fff3cd;
    color: #856404;
  }
  
  .btn-action {
    padding: 6px 12px;
    margin: 2px;
    border: none;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
  }
  
  .btn-detail {
    background: #17a2b8;
    color: white;
  }
  
  .btn-detail:hover {
    background: #138496;
  }
  
  .btn-approve {
    background: #28a745;
    color: white;
  }
  
  .btn-approve:hover {
    background: #218838;
  }
  
  .btn-reject {
    background: #dc3545;
    color: white;
  }
  
  .btn-reject:hover {
    background: #c82333;
  }
  
  .no-data {
    text-align: center;
    padding: 40px;
    color: #999;
  }
  
  .no-data i {
    font-size: 48px;
    margin-bottom: 10px;
    display: block;
  }
</style>

<main>
  <header class="main-header">
    <div>
      <h2>Bukti Pembayaran</h2>
      <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">Kelola dan verifikasi bukti pembayaran penghuni</p>
    </div>
  </header>

  <section class="content">
    <div class="bukti-container">
      
      <!-- Filter Section -->
      <section class="filter-section">
        <div class="form-group">
          <label>Status Pembayaran</label>
          <select id="filterStatus">
            <option value="">Semua Status</option>
            <option value="pending">Pending Verifikasi</option>
            <option value="belum_lunas">Belum Lunas</option>
            <option value="lunas">Lunas</option>
          </select>
        </div>
        
        <div class="form-group">
          <label>Bukti Pembayaran</label>
          <select id="filterBukti">
            <option value="">Semua</option>
            <option value="ada">Ada Bukti</option>
            <option value="kosong">Belum Upload</option>
          </select>
        </div>
        
        <button class="btn-filter" onclick="filterTagihan()">
          <i class="fa fa-filter"></i> Filter
        </button>
      </section>

      <!-- Table Section -->
      <section class="table-container">
        <table>
          <thead>
            <tr>
              <th>No</th>
              <th>Penghuni</th>
              <th>Kamar</th>
              <th>Jumlah</th>
              <th>Tgl Tagihan</th>
              <th>Tgl Tempo</th>
              <th>Status</th>
              <th>Bukti</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            if (!empty($tagihanList) && is_array($tagihanList)) {
                foreach($tagihanList as $row) {
                    $status = strtolower(str_replace(' ', '_', $row['status_pembayaran']));
                    $statusClass = $status == 'lunas' ? 'lunas' : ($status == 'pending' ? 'pending' : 'belum');
                    
                    $namaUser = isset($row['user']['nama']) ? $row['user']['nama'] : 'Unknown';
                    $namaKamar = isset($row['kamar']['nama']) ? $row['kamar']['nama'] : 'Unknown';
                    
                    $tglTagihan = date('d M Y', strtotime($row['tgl_tagihan']));
                    $tglTempo = date('d M Y', strtotime($row['tgl_tempo']));
                    
                    $hasBukti = !empty($row['bukti_pembayaran']);
            ?>
            <tr data-status="<?= $status ?>" data-bukti="<?= $hasBukti ? 'ada' : 'kosong' ?>">
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($namaUser) ?></td>
              <td><?= htmlspecialchars($namaKamar) ?></td>
              <td>Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
              <td><?= $tglTagihan ?></td>
              <td><?= $tglTempo ?></td>
              <td>
                <span class="status <?= $statusClass ?>">
                  <?= htmlspecialchars($row['status_pembayaran']) ?>
                </span>
              </td>
              <td style="text-align: center;">
                <?php if ($hasBukti): ?>
                  <a href="<?= htmlspecialchars($row['bukti_pembayaran']) ?>" target="_blank" title="Lihat bukti">
                    <i class="fa fa-image" style="font-size: 20px; color: #28a745;"></i>
                  </a>
                  <div style="display: none; width: 200px; margin: 10px auto;">
                    <img src="<?= htmlspecialchars($row['bukti_pembayaran']) ?>" 
                         style="max-width: 100%; border-radius: 5px; border: 1px solid #ddd;"
                         onerror="this.parentElement.style.display='none'; this.closest('td').querySelector('.fa-image').style.color='#dc3545'; this.closest('td').querySelector('.fa-image').title='Gagal memuat gambar - Cek Supabase Storage';">
                  </div>
                <?php else: ?>
                  <span style="color: #ccc;">-</span>
                <?php endif; ?>
              </td>
              <td>
                <button class="btn-action btn-detail" onclick="detailTagihan(<?= $row['id_tagihan'] ?>)">
                  <i class="fa fa-eye"></i> Detail
                </button>
                
                <?php if ($hasBukti && $status != 'lunas'): ?>
                <button class="btn-action btn-approve" onclick="approvePayment(<?= $row['id_tagihan'] ?>)">
                  <i class="fa fa-check"></i> Approve
                </button>
                <button class="btn-action btn-reject" onclick="rejectPayment(<?= $row['id_tagihan'] ?>)">
                  <i class="fa fa-times"></i> Reject
                </button>
                <?php endif; ?>
              </td>
            </tr>
            <?php 
                }
            } else {
            ?>
            <tr>
              <td colspan="9" class="no-data">
                <i class="fa fa-inbox"></i>
                <div>Tidak ada data tagihan</div>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </section>
      
    </div>
  </section>
</main>

<script>
  function filterTagihan() {
      const status = document.getElementById('filterStatus').value.toLowerCase();
      const bukti = document.getElementById('filterBukti').value.toLowerCase();
      const rows = document.querySelectorAll('tbody tr[data-status]');
      
      rows.forEach(row => {
          const rowStatus = row.getAttribute('data-status');
          const rowBukti = row.getAttribute('data-bukti');
          
          let showStatus = (status === '' || rowStatus === status);
          let showBukti = (bukti === '' || rowBukti === bukti);
          
          if (showStatus && showBukti) {
              row.style.display = '';
          } else {
              row.style.display = 'none';
          }
      });
  }
  
  async function detailTagihan(id) {
      try {
          const response = await fetch('api/tagihan.php?action=get&id=' + id);
          const data = await response.json();
          
          if (data) {
              const namaUser = data.user?.nama || 'Unknown';
              const namaKamar = data.kamar?.nama || 'Unknown';
              const tglTagihan = new Date(data.tgl_tagihan).toLocaleDateString('id-ID');
              const tglTempo = new Date(data.tgl_tempo).toLocaleDateString('id-ID');
              
              const modalHtml = `
                  <div id="modalDetail" style="display: block; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); overflow: auto;">
                      <div style="background-color: #fefefe; margin: 5% auto; padding: 30px; border-radius: 12px; width: 90%; max-width: 600px; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
                          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #e0e0e0;">
                              <h2 style="margin: 0; color: #333; font-size: 24px;">
                                  <i class="fa fa-receipt"></i> Detail Tagihan
                              </h2>
                              <span onclick="document.getElementById('modalDetail').remove()" style="font-size: 32px; font-weight: bold; color: #999; cursor: pointer; line-height: 1;">&times;</span>
                          </div>
                          
                          <div style="margin-bottom: 15px;">
                              <div style="font-size: 12px; color: #666; margin-bottom: 5px; font-weight: 600;">PENGHUNI</div>
                              <div style="font-size: 16px; color: #333;">👤 ${namaUser}</div>
                          </div>
                          
                          <div style="margin-bottom: 15px;">
                              <div style="font-size: 12px; color: #666; margin-bottom: 5px; font-weight: 600;">KAMAR</div>
                              <div style="font-size: 16px; color: #333;">🚪 ${namaKamar}</div>
                          </div>
                          
                          <div style="margin-bottom: 15px;">
                              <div style="font-size: 12px; color: #666; margin-bottom: 5px; font-weight: 600;">JUMLAH TAGIHAN</div>
                              <div style="font-size: 20px; color: #28a745; font-weight: bold;">💰 Rp ${data.jumlah.toLocaleString('id-ID')}</div>
                          </div>
                          
                          <div style="margin-bottom: 15px;">
                              <div style="font-size: 12px; color: #666; margin-bottom: 5px; font-weight: 600;">TANGGAL TAGIHAN</div>
                              <div style="font-size: 16px; color: #333;">📅 ${tglTagihan}</div>
                          </div>
                          
                          <div style="margin-bottom: 15px;">
                              <div style="font-size: 12px; color: #666; margin-bottom: 5px; font-weight: 600;">TANGGAL JATUH TEMPO</div>
                              <div style="font-size: 16px; color: #dc3545; font-weight: 600;">⏰ ${tglTempo}</div>
                          </div>
                          
                          <div style="margin-bottom: 15px;">
                              <div style="font-size: 12px; color: #666; margin-bottom: 5px; font-weight: 600;">METODE PEMBAYARAN</div>
                              <div style="font-size: 16px; color: #333;">💳 ${data.metode_pembayaran || '-'}</div>
                          </div>
                          
                          ${data.bukti_pembayaran ? `
                          <div style="margin-bottom: 15px;">
                              <div style="font-size: 12px; color: #666; margin-bottom: 5px; font-weight: 600;">BUKTI PEMBAYARAN</div>
                              <div style="position: relative;">
                                  <a href="${data.bukti_pembayaran}" target="_blank">
                                      <img src="${data.bukti_pembayaran}" 
                                           style="max-width: 100%; border-radius: 8px; border: 2px solid #e0e0e0; cursor: pointer;" 
                                           alt="Bukti Pembayaran"
                                           onerror="this.style.display='none'; this.parentElement.nextElementSibling.style.display='block';">
                                  </a>
                                  <div style="display: none; padding: 20px; background: #f8d7da; border-radius: 8px; text-align: center; color: #721c24;">
                                      <i class="fa fa-exclamation-triangle" style="font-size: 32px; margin-bottom: 10px; display: block;"></i>
                                      <strong>Gagal memuat gambar</strong>
                                      <div style="font-size: 12px; margin-top: 5px;">Pastikan Supabase Storage bucket sudah PUBLIC</div>
                                      <a href="${data.bukti_pembayaran}" target="_blank" style="display: inline-block; margin-top: 10px; padding: 5px 15px; background: #721c24; color: white; text-decoration: none; border-radius: 5px; font-size: 12px;">Coba Buka Link</a>
                                  </div>
                              </div>
                              <div style="font-size: 11px; color: #999; margin-top: 5px;">Klik gambar untuk melihat ukuran penuh</div>
                          </div>
                          ` : `
                          <div style="margin-bottom: 15px;">
                              <div style="font-size: 12px; color: #666; margin-bottom: 5px; font-weight: 600;">BUKTI PEMBAYARAN</div>
                              <div style="padding: 20px; background: #f8f9fa; border-radius: 8px; text-align: center; color: #999;">
                                  <i class="fa fa-image" style="font-size: 32px; margin-bottom: 10px; display: block;"></i>
                                  Belum ada bukti pembayaran
                              </div>
                          </div>
                          `}
                          
                          <div style="margin-bottom: 0;">
                              <div style="font-size: 12px; color: #666; margin-bottom: 5px; font-weight: 600;">STATUS PEMBAYARAN</div>
                              <span style="display: inline-block; padding: 8px 20px; border-radius: 20px; font-size: 14px; font-weight: bold; 
                                  ${data.status_pembayaran === 'lunas' ? 'background: #d4edda; color: #155724;' : 
                                    data.status_pembayaran === 'pending' ? 'background: #fff3cd; color: #856404;' : 
                                    'background: #f8d7da; color: #721c24;'}">
                                  ${data.status_pembayaran.toUpperCase()}
                              </span>
                          </div>
                      </div>
                  </div>
              `;
              
              document.body.insertAdjacentHTML('beforeend', modalHtml);
              
              document.getElementById('modalDetail').addEventListener('click', function(e) {
                  if (e.target.id === 'modalDetail') {
                      this.remove();
                  }
              });
          }
      } catch (error) {
          console.error('Error:', error);
          alert('❌ Gagal memuat detail tagihan');
      }
  }
  
  async function approvePayment(id) {
      if (!confirm('✅ Approve pembayaran ini?\n\nStatus akan diubah menjadi LUNAS.')) {
          return;
      }
      
      try {
          const response = await fetch('api/tagihan.php?action=approve&id=' + id, {
              method: 'POST'
          });
          
          const result = await response.json();
          
          if (result.success) {
              alert('✅ Pembayaran berhasil di-approve!');
              location.reload();
          } else {
              alert('❌ Gagal approve pembayaran: ' + (result.message || 'Unknown error'));
          }
      } catch (error) {
          console.error('Error:', error);
          alert('❌ Terjadi kesalahan saat approve pembayaran');
      }
  }
  
  async function rejectPayment(id) {
      const reason = prompt('❌ Reject pembayaran ini?\n\nMasukkan alasan penolakan:');
      
      if (!reason) {
          return;
      }
      
      try {
          const response = await fetch('api/tagihan.php?action=reject&id=' + id, {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json'
              },
              body: JSON.stringify({ reason: reason })
          });
          
          const result = await response.json();
          
          if (result.success) {
              alert('✅ Pembayaran berhasil di-reject!');
              location.reload();
          } else {
              alert('❌ Gagal reject pembayaran: ' + (result.message || 'Unknown error'));
          }
      } catch (error) {
          console.error('Error:', error);
          alert('❌ Terjadi kesalahan saat reject pembayaran');
      }
  }
</script>
