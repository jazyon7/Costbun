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
                      <th>Status</th>
                      <th>Aksi</th>
                  </tr>
              </thead>

              <tbody>
                  <?php
                  include "koneksi.php";

                  // Ambil penyewa dari tabel user (opsional: khusus role penyewa)
                  $no = 1;
                  $query = mysqli_query($conn, "SELECT * FROM user ORDER BY id_user DESC");

                  while($row = mysqli_fetch_assoc($query)){

                      // Status contoh (aktif jika punya nomor, tidak aktif jika kosong)
                      $status = !empty($row['nomor']) ? 'Aktif' : 'Tidak Aktif';
                      $statusClass = ($status == 'Aktif') ? 'status-active' : 'status-inactive';

                      // Sementara kolom kamar belum ada, jadi tampilkan '-'
                      $kamar = "-";
                  ?>
                  
                  <tr>
                      <td><?= $no++; ?></td>
                      <td><?= $row['nama']; ?></td>
                      <td><?= $kamar; ?></td>
                      <td><?= $row['nomor']; ?></td>

                      <td><span class="<?= $statusClass; ?>"><?= $status; ?></span></td>

                      <td>
                          <button class="btn-detail">Detail Data</button>
                          <button class="btn-edit">Edit</button>
                          <button class="btn-delete">Hapus</button>
                      </td>
                  </tr>

                  <?php } ?>
              </tbody>
          </table>
      </div>

  </div>

</section>
