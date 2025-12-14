<style>
    /* Summary Cards */
    .summary-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }
    
    .summary-card {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      padding: 25px;
      border-radius: 15px;
      color: white;
      box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
      transition: transform 0.3s;
    }
    
    .summary-card:hover {
      transform: translateY(-5px);
    }
    
    .summary-card.pemasukan {
      background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    
    .summary-card.pengeluaran {
      background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
    }
    
    .summary-card.saldo {
      background: linear-gradient(135deg, #4776e6 0%, #8e54e9 100%);
    }
    
    .summary-card .icon {
      font-size: 40px;
      margin-bottom: 10px;
      opacity: 0.8;
    }
    
    .summary-card .label {
      font-size: 14px;
      opacity: 0.9;
      margin-bottom: 5px;
    }
    
    .summary-card .value {
      font-size: 28px;
      font-weight: 700;
    }
    
    /* Filter Bar */
    .filter-bar {
      background: white;
      padding: 20px;
      border-radius: 12px;
      margin-bottom: 25px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
      align-items: center;
    }
    
    .filter-group {
      display: flex;
      flex-direction: column;
      gap: 5px;
    }
    
    .filter-group label {
      font-size: 12px;
      font-weight: 600;
      color: #666;
    }
    
    .filter-group select,
    .filter-group input {
      padding: 10px 15px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 14px;
      min-width: 150px;
    }
    
    .btn-tambah {
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
      margin-left: auto;
    }
    
    .btn-tambah:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }
    
    /* Table Styles */
    .table-container {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    
    table {
      width: 100%;
      border-collapse: collapse;
    }
    
    thead {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }
    
    th, td {
      padding: 15px;
      text-align: left;
    }
    
    tbody tr {
      border-bottom: 1px solid #f0f0f0;
      transition: background 0.2s;
    }
    
    tbody tr:hover {
      background: #f8f9fa;
    }
    
    .badge {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      display: inline-block;
    }
    
    .badge.pemasukan {
      background: #d4edda;
      color: #155724;
    }
    
    .badge.pengeluaran {
      background: #f8d7da;
      color: #721c24;
    }
    
    .amount {
      font-weight: 700;
      font-size: 16px;
    }
    
    .amount.pemasukan {
      color: #28a745;
    }
    
    .amount.pengeluaran {
      color: #dc3545;
    }
    
    .btn-action {
      padding: 6px 12px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 12px;
      margin-right: 5px;
      transition: all 0.2s;
    }
    
    .btn-edit {
      background: #ffc107;
      color: #000;
    }
    
    .btn-edit:hover {
      background: #e0a800;
    }
    
    .btn-delete {
      background: #dc3545;
      color: white;
    }
    
    .btn-delete:hover {
      background: #c82333;
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
      margin: 3% auto;
      padding: 30px;
      border-radius: 15px;
      width: 90%;
      max-width: 600px;
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
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: #555;
      font-weight: 600;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 12px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 14px;
      box-sizing: border-box;
    }
    
    .form-group textarea {
      resize: vertical;
      min-height: 80px;
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
  </style>

<section class="content">
    
    <?php
    require_once __DIR__ . '/../config/supabase_helper.php';
    
    // ========================================
    // ACCESS CONTROL: ADMIN ONLY
    // ========================================
    $userRole = $_SESSION['role'] ?? 'penghuni kos';
    $isAdmin = ($userRole === 'admin');
    
    // Redirect non-admin users
    if (!$isAdmin) {
        echo '<div style="text-align: center; padding: 100px 20px;">';
        echo '<i class="fa fa-lock" style="font-size: 80px; color: #ccc; margin-bottom: 20px;"></i>';
        echo '<h2 style="color: #333;">Akses Ditolak</h2>';
        echo '<p style="color: #666; margin-bottom: 30px;">Halaman keuangan hanya dapat diakses oleh <strong>Admin</strong>.</p>';
        echo '<a href="index.php?page=dashboard" style="display: inline-block; padding: 12px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 8px; font-weight: 600;">';
        echo '<i class="fa fa-home"></i> Kembali ke Dashboard';
        echo '</a>';
        echo '</div>';
        echo '</section>';
        return; // Stop execution
    }
    
    // Get all keuangan data
    $keuanganList = getKeuangan();
    
    // Calculate summary
    $totalPemasukan = 0;
    $totalPengeluaran = 0;
    
    if (!empty($keuanganList)) {
        foreach ($keuanganList as $item) {
            $jenis = strtolower($item['jenis']);
            if ($jenis === 'pemasukan') {
                $totalPemasukan += $item['jumlah'];
            } else if ($jenis === 'pengeluaran') {
                $totalPengeluaran += $item['jumlah'];
            }
        }
    }
    
    $saldo = $totalPemasukan - $totalPengeluaran;
    $jumlahTransaksi = count($keuanganList);
    
    // Format currency
    function formatRupiah($angka) {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
    ?>
    
    <!-- Summary Cards -->
    <div class="summary-cards">
      <div class="summary-card pemasukan">
        <div class="icon">💰</div>
        <div class="label">Total Pemasukan</div>
        <div class="value"><?= formatRupiah($totalPemasukan) ?></div>
      </div>
      
      <div class="summary-card pengeluaran">
        <div class="icon">💸</div>
        <div class="label">Total Pengeluaran</div>
        <div class="value"><?= formatRupiah($totalPengeluaran) ?></div>
      </div>
      
      <div class="summary-card saldo">
        <div class="icon">💵</div>
        <div class="label">Saldo</div>
        <div class="value"><?= formatRupiah($saldo) ?></div>
      </div>
      
      <div class="summary-card">
        <div class="icon">📊</div>
        <div class="label">Jumlah Transaksi</div>
        <div class="value"><?= $jumlahTransaksi ?> Records</div>
      </div>
    </div>
    
    <!-- Filter Bar -->
    <div class="filter-bar">
      <div class="filter-group">
        <label>Jenis</label>
        <select id="filterJenis">
          <option value="">Semua</option>
          <option value="pemasukan">Pemasukan</option>
          <option value="pengeluaran">Pengeluaran</option>
        </select>
      </div>
      
      <div class="filter-group">
        <label>Search</label>
        <input type="text" id="searchInput" placeholder="Cari keterangan/sumber...">
      </div>
      
      <?php if ($isAdmin): ?>
      <button class="btn-tambah" onclick="openModalTambah()">
        <i class="fa fa-plus"></i> Tambah Transaksi
      </button>
      <?php endif; ?>
    </div>
    
    <!-- Table Container -->
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Jenis</th>
            <th>Keterangan</th>
            <th>Sumber</th>
            <th>Jumlah</th>
            <?php if ($isAdmin): ?>
            <th>Aksi</th>
            <?php endif; ?>
          </tr>
        </thead>
        
        <tbody id="tableBody">
          <?php
          if (!empty($keuanganList)):
              $no = 1;
              foreach($keuanganList as $row):
                  $jenis = strtolower($row['jenis']);
                  $jenisClass = ($jenis === 'pemasukan') ? 'pemasukan' : 'pengeluaran';
                  $tanggal = date('d M Y', strtotime($row['tanggal_tranksaksi']));
          ?>
          <tr data-jenis="<?= $jenis ?>">
            <td><?= $no++ ?></td>
            <td><?= $tanggal ?></td>
            <td>
              <span class="badge <?= $jenisClass ?>">
                <?= strtoupper($jenis) ?>
              </span>
            </td>
            <td><?= htmlspecialchars($row['keterangan']) ?></td>
            <td><?= htmlspecialchars($row['sumber']) ?></td>
            <td class="amount <?= $jenisClass ?>">
              <?= formatRupiah($row['jumlah']) ?>
            </td>
            <?php if ($isAdmin): ?>
            <td>
              <button class="btn-action btn-edit" onclick='editTransaksi(<?= json_encode($row) ?>)'>
                <i class="fa fa-edit"></i> Edit
              </button>
              <button class="btn-action btn-delete" onclick="deleteTransaksi(<?= $row['id_keuangan'] ?>)">
                <i class="fa fa-trash"></i> Hapus
              </button>
            </td>
            <?php endif; ?>
          </tr>
          <?php 
              endforeach;
          else:
          ?>
          <tr>
            <td colspan="<?= $isAdmin ? 7 : 6 ?>" style="text-align: center; padding: 40px;">
              <i class="fa fa-inbox" style="font-size: 48px; color: #ccc; margin-bottom: 10px;"></i>
              <p style="color: #999;">Belum ada data transaksi</p>
            </td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    
  </section>
  
  <!-- Modal Tambah/Edit Transaksi -->
  <div id="modalKeuangan" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2><i class="fa fa-money-bill-wave"></i> <span id="modalTitle">Tambah Transaksi</span></h2>
        <span class="close" onclick="closeModal()">&times;</span>
      </div>
      
      <form id="formKeuangan" onsubmit="submitForm(event)">
        <input type="hidden" id="id_keuangan" name="id_keuangan">
        
        <div class="form-group">
          <label for="tanggal_tranksaksi">
            <i class="fa fa-calendar"></i> Tanggal Transaksi *
          </label>
          <input 
            type="date" 
            id="tanggal_tranksaksi" 
            name="tanggal_tranksaksi" 
            required
          >
        </div>
        
        <div class="form-group">
          <label for="jenis">
            <i class="fa fa-tag"></i> Jenis Transaksi *
          </label>
          <select id="jenis" name="jenis" required>
            <option value="">-- Pilih Jenis --</option>
            <option value="pemasukan">Pemasukan</option>
            <option value="pengeluaran">Pengeluaran</option>
          </select>
        </div>
        
        <div class="form-group">
          <label for="keterangan">
            <i class="fa fa-align-left"></i> Keterangan *
          </label>
          <textarea 
            id="keterangan" 
            name="keterangan" 
            placeholder="Jelaskan detail transaksi..."
            required
          ></textarea>
        </div>
        
        <div class="form-group">
          <label for="sumber">
            <i class="fa fa-source"></i> Sumber *
          </label>
          <input 
            type="text" 
            id="sumber" 
            name="sumber" 
            placeholder="Contoh: Kamar 101, PLN, Maintenance"
            required
          >
        </div>
        
        <div class="form-group">
          <label for="jumlah">
            <i class="fa fa-money-bill"></i> Jumlah (Rp) *
          </label>
          <input 
            type="number" 
            id="jumlah" 
            name="jumlah" 
            placeholder="0"
            min="1"
            required
          >
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeModal()">
            <i class="fa fa-times"></i> Batal
          </button>
          <button type="submit" class="btn-submit">
            <i class="fa fa-save"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
  
  <script>
    let isEditMode = false;
    
    // Open modal tambah
    function openModalTambah() {
        isEditMode = false;
        document.getElementById('modalTitle').textContent = 'Tambah Transaksi';
        document.getElementById('formKeuangan').reset();
        document.getElementById('id_keuangan').value = '';
        document.getElementById('tanggal_tranksaksi').valueAsDate = new Date();
        document.getElementById('modalKeuangan').style.display = 'block';
    }
    
    // Edit transaksi
    function editTransaksi(data) {
        isEditMode = true;
        document.getElementById('modalTitle').textContent = 'Edit Transaksi';
        document.getElementById('id_keuangan').value = data.id_keuangan;
        document.getElementById('tanggal_tranksaksi').value = data.tanggal_tranksaksi;
        document.getElementById('jenis').value = data.jenis.toLowerCase();
        document.getElementById('keterangan').value = data.keterangan;
        document.getElementById('sumber').value = data.sumber;
        document.getElementById('jumlah').value = data.jumlah;
        document.getElementById('modalKeuangan').style.display = 'block';
    }
    
    // Close modal
    function closeModal() {
        document.getElementById('modalKeuangan').style.display = 'none';
        document.getElementById('formKeuangan').reset();
    }
    
    // Close when click outside
    window.onclick = function(event) {
        const modal = document.getElementById('modalKeuangan');
        if (event.target == modal) {
            closeModal();
        }
    }
    
    // Submit form
    async function submitForm(event) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        const data = {
            tanggal_tranksaksi: formData.get('tanggal_tranksaksi'),
            jenis: formData.get('jenis'),
            keterangan: formData.get('keterangan'),
            sumber: formData.get('sumber'),
            jumlah: parseInt(formData.get('jumlah'))
        };
        
        const id = formData.get('id_keuangan');
        const action = isEditMode ? 'update' : 'create';
        const url = isEditMode ? `api/keuangan.php?action=update&id=${id}` : 'api/keuangan.php?action=create';
        
        console.log('Submitting:', data);
        
        try {
            const response = await fetch(url, {
                method: isEditMode ? 'PATCH' : 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            console.log('Response:', result);
            
            if (result.success) {
                alert(`✅ Transaksi berhasil ${isEditMode ? 'diupdate' : 'ditambahkan'}!`);
                closeModal();
                location.reload();
            } else {
                alert('❌ Gagal menyimpan transaksi: ' + (result.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan saat menyimpan transaksi');
        }
    }
    
    // Delete transaksi
    async function deleteTransaksi(id) {
        if (!confirm('⚠️ Yakin ingin menghapus transaksi ini?\n\nData yang dihapus tidak dapat dikembalikan.')) {
            return;
        }
        
        try {
            const response = await fetch(`api/keuangan.php?action=delete&id=${id}`, {
                method: 'DELETE'
            });
            
            const result = await response.json();
            console.log('Delete response:', result);
            
            if (result.success) {
                alert('✅ Transaksi berhasil dihapus!');
                location.reload();
            } else {
                alert('❌ Gagal menghapus transaksi: ' + (result.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan saat menghapus transaksi');
        }
    }
    
    // Filter by jenis
    document.getElementById('filterJenis').addEventListener('change', function() {
        filterTable();
    });
    
    // Search
    document.getElementById('searchInput').addEventListener('keyup', function() {
        filterTable();
    });
    
    // Filter table function
    function filterTable() {
        const jenis = document.getElementById('filterJenis').value.toLowerCase();
        const search = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#tableBody tr[data-jenis]');
        
        rows.forEach(row => {
            const rowJenis = row.getAttribute('data-jenis');
            const keterangan = row.cells[3].textContent.toLowerCase();
            const sumber = row.cells[4].textContent.toLowerCase();
            
            const matchJenis = jenis === '' || rowJenis === jenis;
            const matchSearch = search === '' || keterangan.includes(search) || sumber.includes(search);
            
            if (matchJenis && matchSearch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
  </script>
