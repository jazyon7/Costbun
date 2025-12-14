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
</head>

      <!-- ✅ Diubah dari div menjadi section -->
      <section class="content">

        <!-- ✅ Tetap dibiarkan kosong sesuai UI -->
        <section class="page-header"></section>

        <!-- ✅ Diubah menjadi section -->
        <section class="filter-box">
          <div class="filter-group">
            <label>Status</label>
            <select id="filterStatus">
              <option value="">Semua</option>
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
                <th>Tanggal Lapor</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>

            <tbody>
              <?php
              require_once __DIR__ . '/../config/supabase_helper.php';

              $laporanList = getLaporan();
              $no = 1;

              if (!empty($laporanList)) {
                  foreach($laporanList as $row) {
                      $statusClass = strtolower($row['status_laporan']) == 'selesai' ? 'success' : 'pending';
                      $pelapor = isset($row['user']['nama']) ? $row['user']['nama'] : 'Unknown';
                      $tanggal = date('Y-m-d', strtotime($row['created_at']));
              ?>
              <tr data-status="<?= strtolower($row['status_laporan']); ?>">
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($pelapor); ?></td>
                <td><?= htmlspecialchars($row['judul_laporan']); ?></td>
                <td><?= htmlspecialchars($row['deskripsi']); ?></td>
                <td><?= $tanggal; ?></td>
                <td><span class="status <?= $statusClass; ?>"><?= htmlspecialchars($row['status_laporan']); ?></span></td>
                <td>
                  <button class="btn-detail" onclick="detailLaporan(<?= $row['id_laporan']; ?>)">Detail</button>
                  <button class="btn-edit" onclick="updateStatus(<?= $row['id_laporan']; ?>, '<?= $row['status_laporan']; ?>')">Ubah Status</button>
                </td>
              </tr>
              <?php 
                  }
              } else {
              ?>
              <tr>
                <td colspan="7" style="text-align: center;">Tidak ada data laporan</td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </section>

      </section>

      <script>
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
          alert('Detail laporan ID: ' + id);
          // window.location.href = 'detail_laporan.php?id=' + id;
      }

      function updateStatus(id, currentStatus) {
          const newStatus = currentStatus.toLowerCase() === 'diproses' ? 'selesai' : 'diproses';
          if (confirm('Ubah status menjadi ' + newStatus + '?')) {
              window.location.href = 'api/laporan.php?action=update_status&id=' + id + '&status=' + newStatus;
          }
      }
      </script>