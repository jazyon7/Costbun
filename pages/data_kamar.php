<section class="data-kamar-section">

  <header class="main-header">
      <h2>Data Kamar</h2>
  </header>

  <div class="room-container">
      <div class="room-grid">

      <?php
      include "koneksi.php";

      $query = mysqli_query($conn, "SELECT * FROM kamar");
      while($row = mysqli_fetch_assoc($query)){

          // status class untuk warna UI
          $statusClass = ($row['status'] == 'terisi') ? 'kosong' : 'available';
      ?>

          <div class="room-card">
              <div class="room-number"><?= $row['nama']; ?></div>

              <div class="room-status <?= $statusClass; ?>">
                  <?= $row['status']; ?>
              </div>

              <button class="btn-status" data-id="<?= $row['id_kamar']; ?>">
                  Ubah Status
              </button>
          </div>

      <?php } ?>

          <!-- Tombol Tambah -->
          <div class="room-card add-card">
              <div class="add-plus">+</div>
              <p class="add-text">Tambah<br>Kamar Baru</p>
          </div>

      </div>
  </div>

</section>
