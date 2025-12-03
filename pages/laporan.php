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
            <select>
              <option>Semua</option>
              <option>Diproses</option>
              <option>Selesai</option>
            </select>
          </div>

          <button class="btn-filter">Filter</button>
        </section>

        <!-- ✅ Diubah menjadi section -->
        <section class="table-container">
          <table>
            <thead>
              <tr>
                <th>No</th>
                <th>Pelapor</th>
                <th>Fasilitas</th>
                <th>Keterangan</th>
                <th>Tanggal Lapor</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>

            <tbody>
              <tr>
                <td>1</td>
                <td>Sukron</td>
                <td>Gayung</td>
                <td>Gayung pecah</td>
                <td>2025-11-12</td>
                <td><span class="status success">Selesai</span></td>
                <td>
                  <button class="btn-detail">Detail</button>
                </td>
              </tr>

              <tr>
                <td>2</td>
                <td>zen</td>
                <td>Pintu</td>
                <td>engsel pintu copot</td>
                <td>2025-11-25</td>
                <td><span class="status pending">Diproses</span></td>
                <td>
                  <button class="btn-detail">Detail</button>
                </td>
              </tr>

              <tr>
                <td>3</td>
                <td>dika</td>
                <td>meja</td>
                <td>meja belah dua</td>
                <td>2025-11-30</td>
                <td><span class="status pending">Diproses</span></td>
                <td>
                  <button class="btn-detail">Detail</button>
                </td>
              </tr>
            </tbody>
          </table>
        </section>

      </section>