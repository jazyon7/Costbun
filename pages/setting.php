<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pengaturan</title>
  <link rel="stylesheet" href="style.css">
  <script src="navigasi.js"></script>
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet">
</head>
    <main>
      <header class="main-header">
        <div>
          <h2>Pengaturan</h2>
        </div>
      </header>

      <!-- ✅ Diubah dari div menjadi section -->
      <section class="content">

        <!-- ✅ Diubah menjadi section -->
        <section class="settings-container">

          <!-- ✅ Diubah menjadi section -->
          <section class="settings-card">
            <h3>Pengaturan Akun</h3>

            <div class="form-group">
              <label>Nama Admin</label>
              <input type="text" placeholder="Nama lengkap">
            </div>

            <div class="form-group">
              <label>Email</label>
              <input type="email" placeholder="Email admin">
            </div>

            <div class="form-group">
              <label>Password Baru</label>
              <input type="password" placeholder="Kosongkan jika tidak mengganti">
            </div>

            <button class="btn-save">Simpan Perubahan</button>
          </section>

          <!-- ✅ Diubah menjadi section -->
          <section class="settings-card">
            <h3>Pengaturan Sistem</h3>

            <div class="form-group">
              <label>Bahasa</label>
              <select>
                <option>Indonesia</option>
                <option>English</option>
              </select>
            </div>

            <div class="form-group">
              <label>Tema Tampilan</label>
              <select>
                <option>Light</option>
                <option>Dark</option>
                <option>Auto</option>
              </select>
            </div>

            <button class="btn-save">Simpan Pengaturan</button>
          </section>

          <!-- ✅ Diubah menjadi section -->
          <section class="settings-card">
            <h3>Pengaturan Notifikasi</h3>

            <div class="checkbox-group">
              <input type="checkbox" id="notif1" checked>
              <label for="notif1">Notifikasi Laporan Baru</label>
            </div>

            <div class="checkbox-group">
              <input type="checkbox" id="notif2">
              <label for="notif2">Notifikasi Laporan Selesai</label>
            </div>

            <div class="checkbox-group">
              <input type="checkbox" id="notif3" checked>
              <label for="notif3">Notifikasi Error Sistem</label>
            </div>

            <button class="btn-save">Simpan Notifikasi</button>
          </section>

        </section>
      </section>

    </main>
