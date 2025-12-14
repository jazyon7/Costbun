<section class="data-kamar-section">

  <header class="main-header">
      <h2>Data Kamar</h2>
  </header>

  <div class="room-container">
      <div class="room-grid">

      <?php
      require_once __DIR__ . '/../config/supabase_helper.php';

      $kamarList = getKamar();
      if (!empty($kamarList)) {
          foreach($kamarList as $row) {
              // status class untuk warna UI
              $statusClass = ($row['status'] == 'terisi') ? 'kosong' : 'available';
      ?>

          <div class="room-card">
              <div class="room-number"><?= htmlspecialchars($row['nama']); ?></div>

              <div class="room-status <?= $statusClass; ?>">
                  <?= htmlspecialchars($row['status']); ?>
              </div>

              <button class="btn-status" data-id="<?= $row['id_kamar']; ?>">
                  Ubah Status
              </button>
          </div>

      <?php 
          }
      } 
      ?>

          <!-- Tombol Tambah -->
          <div class="room-card add-card" onclick="tambahKamar()">
              <div class="add-plus">+</div>
              <p class="add-text">Tambah<br>Kamar Baru</p>
          </div>

      </div>
  </div>

</section>

<script>
// Ubah status kamar
document.querySelectorAll('.btn-status').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const currentStatus = this.closest('.room-card').querySelector('.room-status').textContent.trim();
        const newStatus = (currentStatus === 'terisi') ? 'kosong' : 'terisi';
        
        if (confirm('Ubah status kamar menjadi ' + newStatus + '?')) {
            window.location.href = 'api/kamar.php?action=update_status&id=' + id + '&status=' + newStatus;
        }
    });
});

function tambahKamar() {
    const nama = prompt('Nama Kamar:');
    if (!nama) return;
    
    const kasur = prompt('Jumlah Kasur:', '1');
    const kipas = prompt('Jumlah Kipas:', '1');
    const lemari = prompt('Jumlah Lemari:', '1');
    const keranjang_sampah = prompt('Jumlah Keranjang Sampah:', '1');
    const ac = prompt('Jumlah AC:', '0');
    const harga = prompt('Harga Sewa:');
    
    if (nama && harga) {
        window.location.href = `api/kamar.php?action=create&nama=${nama}&kasur=${kasur}&kipas=${kipas}&lemari=${lemari}&keranjang_sampah=${keranjang_sampah}&ac=${ac}&harga=${harga}&status=kosong`;
    }
}
</script>
